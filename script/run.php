<?php

//parse command line arguments into the $ARG variable
parse_str(implode('&', array_slice($argv, 1)), $ARG);

//include libraries
require_once 'include/idiorm/idiorm.php';
require_once 'include/paris/paris.php';
require_once 'include/Faker/src/autoload.php';
require_once 'include/func.php';


//ORM configuration
ORM::configure('mysql:host=localhost;dbname='.$ARG["dbname"]);
ORM::configure('username', $ARG["dbuser"]);
ORM::configure('password', $ARG["dbpass"]);


//Models
if (isset($ARG["models"])) {
	require_once $ARG["models"];
}


//Faker instance
$faker = Faker\Factory::create();


//Mysqli configuration
$mysqli = new mysqli('localhost', $ARG["dbuser"], $ARG["dbpass"]); 


//Drop database
$mysqli->query('DROP DATABASE IF EXISTS '.$ARG["dbname"]);


//Create database
$result = $mysqli->query('CREATE DATABASE '.$ARG["dbname"]);
if (!$result) {
    die('Неверный запрос: ' . $mysqli->error);
}


//Select database
$mysqli->select_db($ARG["dbname"]);


//Create tables
$filename = $ARG["structure"];
$file = fopen($filename, "r") or die("Unable to open file!");
$contents = fread($file, filesize($filename));
fclose($file);
$result = $mysqli->multi_query($contents);
if (!$result) {
    die('Неверный запрос: ' . $mysqli->error);
}
while($mysqli->next_result()) $mysqli->store_result();


//Create views
$views = $ARG["views"];
foreach ($views as $view) {
	$filename = $view;
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
if (isset($ARG["plan"])) {
	require_once $ARG["plan"];
}


//Get views
$mysqli->select_db("information_schema.TABLES");
$result = $mysqli->query("SELECT TABLE_NAME as view_name FROM information_schema.TABLES WHERE TABLE_TYPE LIKE 'VIEW' AND TABLE_SCHEMA LIKE '".$ARG["dbname"]."'");
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


//Print Result
echo "\nTest results: \n \n";
foreach ($test_counts as $test_count) {
	echo "View name: ".$test_count["view_name"]."\nCount: ".$test_count["count"]."\n\n";
}


?>