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

require_once 'template/top.php';

if (isset($_POST["plan"])) {
	require_once 'plans/'.$_POST["plan"];
}
require_once 'template/run.php';
require_once 'template/bottom.php';

?>