<?php
require_once 'dbconf.php';

//include libraries
require_once 'include/idiorm/idiorm.php';
require_once 'include/paris/paris.php';
require_once 'include/Faker/src/autoload.php';
require_once 'include/func.php';


//ORM configuration
ORM::configure('mysql:host=localhost;dbname='.$dbname);
ORM::configure('username', $dbuser);
ORM::configure('password', $dbpass);


//Models
require_once 'models.php';


//Faker instance
$faker = Faker\Factory::create();


//Mysqli configuration
$mysqli = new mysqli('localhost', $dbuser, $dbpass); 


//Drop database
$mysqli->query('DROP DATABASE IF EXISTS analytics');


//Create database
$result = $mysqli->query('CREATE DATABASE analytics');
if (!$result) {
    die('Неверный запрос: ' . $mysqli->error);
}


//Select database
$mysqli->select_db($dbname);


//Create tables
$filename = "data/structure/create.sql";
$file = fopen($filename, "r") or die("Unable to open file!");
$contents = fread($file, filesize($filename));
fclose($file);
$result = $mysqli->multi_query($contents);
if (!$result) {
    die('Неверный запрос: ' . $mysqli->error);
}
while($mysqli->next_result()) $mysqli->store_result();


//Create views
$view_directory = "data/views";
$views = array_diff(scandir($view_directory), array('..', '.'));
foreach ($views as $view) {
	$filename = "data/views/".$view;
	$file = fopen($filename, "r") or die("Unable to open file!");
	$contents = fread($file, filesize($filename));
	fclose($file);
	$result = $mysqli->multi_query($contents);
	if (!$result) {
		die('Неверный запрос: ' . $mysqli->error);
	}
}
while($mysqli->next_result()) $mysqli->store_result();

//Generate data
if (isset($_POST["plan"])) {
	require_once 'generation_plans/'.$_POST["plan"];
}


//Get views
$mysqli->select_db("information_schema.TABLES");
$result = $mysqli->query("SELECT TABLE_NAME as view_name FROM information_schema.TABLES WHERE TABLE_TYPE LIKE 'VIEW' AND TABLE_SCHEMA LIKE 'analytics'");
if (!$result) {
    die('Неверный запрос: ' . $mysqli->error);
}
while( $row = $result->fetch_assoc() ){ 
	$view_names[] = $row['view_name']; 
} 


//Test Views
$mysqli->select_db($dbname);
foreach ($view_names as $view_name) {
	$result = $mysqli->query("SELECT COUNT(*) as count FROM (SELECT * FROM $view_name)  AS t");
	if (!$result) {
		die('Неверный запрос: ' . $mysqli->error);
	}
	$res = $result->fetch_assoc();
	$test_counts[] = array('view_name' => $view_name, 'count' => $res["count"]);
}


require_once 'template/top.php';
require_once 'template/run.php';
require_once 'template/bottom.php';
?>