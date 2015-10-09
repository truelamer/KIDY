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
class Objects extends Model {
	public function workers() {
		return $this->has_many_through('Workers');
		}
}
class Professions extends Model {}
class Purchases extends Model {}
class Workers extends Model {}
class ObjectsWorkers extends Model {}


//Faker instance
$faker = Faker\Factory::create();


//Clear Data
$professions = Model::factory('Professions')->find_many();
foreach ($professions as $profession) {
     $profession->delete();
}
reset_auto_increment('professions');

$objects = Model::factory('Objects')->find_many();
foreach ($objects as $object) {
     $object->delete();
}
reset_auto_increment('objects');

$purchases = Model::factory('Purchases')->find_many();
foreach ($purchases as $purchase) {
     $purchase->delete();
}
reset_auto_increment('purchases');

$workers = Model::factory('Workers')->find_many();
foreach ($workers as $worker) {
     $worker->delete();
}
reset_auto_increment('workers');

$objects_workers = Model::factory('ObjectsWorkers')->find_many();
foreach ($objects_workers as $objects_worker) {
     $objects_worker->delete();
}
reset_auto_increment('objects_workers');

//exit;

//Generate Professions
$profession = Model::factory('Professions')->create();
$profession->name = "foreman";
$profession->save();

for ($i=0; $i < 5; $i++) {
	$profession = Model::factory('Professions')->create();
	$profession->name = $faker->word;
	$profession->save();
}


//Generate Objects
for ($i=0; $i < 5; $i++) {
	$object = Model::factory('Objects')->create();
	$object->name = $faker->company;
	$object->material = $faker->biasedNumberBetween($min = 50, $max = 200);
	$object->work = $faker->biasedNumberBetween($min = 50, $max = 200);
	$object->date_start = $faker->dateTimeBetween($startDate = '2010-01-01', $endDate = '-1 week')->format('Y-m-d');
	$object->date_end = $faker->dateTimeBetween($startDate = $object->date_start, $endDate = 'now')->format('Y-m-d');
	$object->date_real_end = $faker->dateTimeBetween($startDate = $object->date_start, $endDate = 'now')->format('Y-m-d');
	$object->foreman_id = 1;
	$object->save();
	//echo '<pre>';print_r($object->as_array());echo '</pre>';
}

//Generate Purchases
$objects = Model::factory('Objects')->find_many();
foreach ($objects as $object) {
	$money = $object->material;
	while ($money<>0)
	{
		$purchase = Model::factory('Purchases')->create();
		$purchase->object_id = $object->id;
		$purchase->material = $faker->word;
		
		$min = 10;
		if ($min>$money) $min = $money;
		$purchase->cost = $faker->biasedNumberBetween($min, $money);
		$purchase->save();
		$money -= $purchase->cost;
	}
}


//Generate Workers
for ($i=0; $i < 2; $i++) {
	$worker = Model::factory('Workers')->create();
	$worker->name = $faker->name;
	$worker->salary = 0;
	$worker->profession_id = Model::factory('Professions')->where('name', 'foreman')->find_one()->id;
	$worker->save();
}

for ($i=0; $i < 10; $i++) {
	$worker = Model::factory('Workers')->create();
	$worker->name = $faker->name;
	$min_id = 2;
	$max_id = Model::factory('Professions')->count();
	$worker->profession_id = $faker->biasedNumberBetween($min_id, $max_id);
	$worker->save();
}

//Add Foremans To Objects
$objects = Model::factory('Objects')->find_many();
foreach ($objects as $object) {
	$min_id  = 1;
	$max_id  = Model::factory('Workers')->where('profession_id', 1)->count();
	$object->foreman_id = $faker->biasedNumberBetween($min_id, $max_id);
	$object->save();
}

//Add Workers To Objects
$objects = Model::factory('Objects')->find_many();
foreach ($objects as $object) {
	$min_id  = Model::factory('Workers')->where('profession_id', 1)->count();
	$max_id  = Model::factory('Workers')->count();
	$objects_workers = Model::factory('ObjectsWorkers')->create();
	$objects_workers->object_id = $object->id;
	$objects_workers->worker_id = $faker->biasedNumberBetween($min_id, $max_id);;
	$objects_workers->save();
}