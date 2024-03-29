<?php
session_start();
//On démarre une session. C'est toujours la première session d'un fichier php.
?>
<!DOCTYPE html>
<html>
<head>
	<title>association medianet</title>
	<link rel="stylesheet" href="/projetmedianet/view/style.css">
</head>
<body>
<?php 
require("modele/User.php");
require("modele/Article.php");
require("controller/Dao.php");
$action = explode ("/",parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

$action=end($action);
echo $action;
$existe=1;

if ($action=="create") {
    $nom=$_GET["nom"];
    $prenom=$_GET["prenom"];
    $datenaiss=$_GET["datenaiss"];
    $login=$_GET["login"];
    $password=$_GET["password"];
    $email=$_GET["email"];
    $utilisateur=new User($nom,$prenom,$datenaiss,$login,$password,$email);
    $dao=Dao::getPdoGsb();
    //On appelle une methode qui compare le user saisi avec les user de la table user de la bdd//
    //et on récupère Vrai ou faux.
    $existeuser=$dao->verifierUser($utilisateur);
    //on teste si faux (booleean) s'il n'existe pas.
    if (!$existeuser){
    $dao->ajouterUser($utilisateur);
    include("view/login.php");
    }else {
    include("view/enregistrement.php");
    }
}

if ($action=="enregistrer"){
    include("view/enregistrement.php");
}

if ($action=="login"){
    $login=$_GET["login"];
    $password=$_GET["password"];
    $dao=Dao::getPdoGsb();
    $user=$dao->getInfosUser($login, $password);
    if ($user!=null){
        $liste=$dao->getLesArticles();
        $_SESSION["iduser"]=$user->getId();
        //On stocke l'id utilisateur pour le récupérer afin de le traiter ultérieurement.
        include("view/accueil.php");
    }else{
        $existe=0;
        include("view/login.php");
    }
}

if ($action=="index.php"){
    include("view/login.php");
}
//ici on traite la requete provenant de Readmore

if ($action=="details"){
    //on récupère l'id
    $id=$_GET["id"];
    $dao=Dao::getPdoGsb();
    //on recherche l'article correspondant à l'Id
    $article=$dao->getArticleById($id);
    //si l'article existe, on affiche la page details.
    if ($article!=null){
        include("view/details.php");
    }
}
if ($action=="ajouterpost"){
            
        include("view/ajouterpost.php");
}
if ($action=="insererpost"){
    $dao=Dao::getPdoGsb();
    $titre=$_GET["titre"];
    $date=$_GET["date"];
    $idauteur=$_GET["idauteur"];
    $article=$_GET["post"];
    //On instancie un objet article avec le nouveau constructeur
    $post=new Article($titre,$date,$idauteur,$article);
    //On fait appel à la méthode d'ajout du nouveau post.
    $dao->ajouterArticle($post);
    //On appelle tous les articles avec le nouveau. 
    $liste=$dao->getLesArticles();
    include("view/accueil.php");
}
if ($action=="accueil"){
    $dao=Dao::getPdoGsb();
    $liste=$dao->getLesArticles();
    include("view/accueil.php");

}
/* $dao=Dao::getPdoGsb();
 $liste=$dao->getLesUtilisateur();
 foreach($liste as $user1){
 echo "nom : ".$user1->getNom()." prenom :".$user1->getPrenom();
 }
 */
?>
</body>
</html>








