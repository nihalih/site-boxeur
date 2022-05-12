<?php
/*
 * On indique que les chemins des fichiers qu'on inclut
 * seront relatifs au répertoire src.
 */
set_include_path("./src");
require_once("./src/model/boxeur_model/BoxeurStorageMySQL.php");
require_once("./src/model/account_model/AccountStorageMySQL.php");
require_once('/users/22008897/private/mysql_config.php');
/* Inclusion des classes utilisées dans ce fichier */
require_once("Router.php");

/*
 * Cette page est simplement le point d'arrivée de l'internaute
 * sur notre site. On se contente de créer un routeur
 * et de lancer son main.
 */
$router = new Router();
//$fichierAnimal=new AnimalStorageFile($_SERVER['TMPDIR'].'/animal_db.txt');
//$fichierAnimal->reinit();

$dsn ="mysql:host=".MYSQL_HOST.";port=".MYSQL_PORT.";dbname=".MYSQL_DB.";charset=utf8mb4";
$bd = new PDO($dsn,MYSQL_USER, MYSQL_PASSWORD);
$bd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$BoxeurStorageSQL=new BoxeurStorageMySQL($bd);
$AccountStorageSQL = new AccountStorageMySQL($bd);
$router->main($BoxeurStorageSQL,$AccountStorageSQL);
//echo($_SERVER['PATH_INFO'])
?>