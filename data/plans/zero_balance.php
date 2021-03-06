<?php

//Generation options
$objects_count = 5;
$professtions_count = 5;
$workers_count = 10;

$foremans_count = 5;
$managers_count = 2;
$special_professions_count = 2;

$min_worker_salary = 1;
$max_worker_salary = 5;
$min_worker_payment = 10;
$max_worker_payment = 100;
$min_material_cost = 10;
$max_material_cost = 100;
$max_purchases_on_object = 2;
$start_date = "2010-01-01";
$end_date = "now";


//Generate Actions
$action_names = array( 
	"watch object",    //1
	"edit object",     //2
	"add purchases",   //3
	"watch purchases", //4
	"edit purchases"   //5
);
foreach ($action_names as $action_name) {
	$action = Model::factory('Actions')->create();
	$action->name = $action_name;
	$action->save();
}


//Generate Foreman Profession
$profession = Model::factory('Professions')->create();
$profession->name = "foreman";
$profession->save();


//Generate Manager Profession
$profession = Model::factory('Professions')->create();
$profession->name = "manager";
$profession->save();


//Generate Other Professions
for ($i=0; $i < $professtions_count; $i++) {
	$profession = Model::factory('Professions')->create();
	$profession->name = $faker->word;
	$profession->save();
}


//Generate Foremans
for ($i=0; $i < $foremans_count; $i++) {
	$worker = Model::factory('Workers')->create();
	$worker->name = $faker->name;
	$worker->salary = 0;
	$worker->profession_id = Model::factory('Professions')->where('name', 'foreman')->find_one()->id;
	$worker->save();
}


//Generate Managers
for ($i=0; $i < $managers_count; $i++) {
	$worker = Model::factory('Workers')->create();
	$worker->name = $faker->name;
	$worker->salary = 0;
	$worker->profession_id = Model::factory('Professions')->where('name', 'manager')->find_one()->id;
	$worker->save();
}


//Generate Other Workers
for ($i=0; $i < $workers_count; $i++) {
	$worker = Model::factory('Workers')->create();
	$worker->name = $faker->name;
	$min_id = $special_professions_count + 1;
	$max_id = Model::factory('Professions')->count();
	$worker->profession_id = rand($min_id, $max_id);
	$salary = array(0, rand($min_worker_salary, $max_worker_salary));
	$worker->salary = $salary[array_rand($salary)];	
	$worker->save();
}


//Add Foremans To Objects
//and permissions to Foremans
for ($i=1; $i <= $objects_count; $i++) {
	$min_id  = 1;
	$max_id  = $foremans_count;
	$objects_workers = Model::factory('ObjectsWorkers')->create();
	$objects_workers->object_id = $i;
	$objects_workers->worker_id = rand($min_id, $max_id);;
	$objects_workers->save();	
	
	$permission = Model::factory('Permissions')->create();
	$permission->worker_id = $objects_workers->worker_id;
	$permission->object_id = $objects_workers->object_id;
	$permission->action_id = 1; //watch object
	$permission->save();
	
	$permission = Model::factory('Permissions')->create();
	$permission->worker_id = $objects_workers->worker_id;
	$permission->object_id = $objects_workers->object_id;
	$permission->action_id = 4; //watch purchases
	$permission->save();
	
	$permission = Model::factory('Permissions')->create();
	$permission->worker_id = $objects_workers->worker_id;
	$permission->object_id = $objects_workers->object_id;
	$permission->action_id = 3; //add purchases
	$permission->save();	
}

//Add Managers To Objects
for ($i=1; $i <= $objects_count; $i++) {
	$min_id  = $foremans_count + 1;
	$max_id  = $foremans_count + $managers_count;
	$objects_workers = Model::factory('ObjectsWorkers')->create();
	$objects_workers->object_id = $i;
	$objects_workers->worker_id = rand($min_id, $max_id);;
	$objects_workers->save();	
	
	$permission = Model::factory('Permissions')->create();
	$permission->worker_id = $objects_workers->worker_id;
	$permission->object_id = $objects_workers->object_id;
	$permission->action_id = 1; //watch object
	$permission->save();
	
	$permission = Model::factory('Permissions')->create();
	$permission->worker_id = $objects_workers->worker_id;
	$permission->object_id = $objects_workers->object_id;
	$permission->action_id = 2; //edit object
	$permission->save();

	$permission = Model::factory('Permissions')->create();
	$permission->worker_id = $objects_workers->worker_id;
	$permission->object_id = $objects_workers->object_id;
	$permission->action_id = 4; //watch purchaces
	$permission->save();	
	
	$permission = Model::factory('Permissions')->create();
	$permission->worker_id = $objects_workers->worker_id;
	$permission->object_id = $objects_workers->object_id;
	$permission->action_id = 5; //edit purchaces
	$permission->save();	
}


//Add Each Other Worker To Objects
$min_id = $foremans_count+$managers_count+1;
$max_id = $workers_count+$foremans_count+$managers_count;
for ($i=$min_id; $i <= $max_id; $i++) {
	$objects_workers = Model::factory('ObjectsWorkers')->create();	
	$objects_workers->object_id = rand(1,$objects_count);
	$objects_workers->worker_id = $i;	
	$objects_workers->save();	
}


//Generate Payments
$workers = Model::factory('Workers')->where('salary', 0)->where_not_equal('profession_id',1)->find_many();
foreach ($workers as $worker) {
	$payment = Model::factory('Payments')->create();
	$payment->worker_id = $worker->id;
	$payment->amount = rand($min_worker_payment, $max_worker_payment);
	$payment->object_id = Model::factory('ObjectsWorkers')->where('worker_id', $worker->id)->find_one()->object_id;
	$payment->save();
}


//Generate Purchases
for ($i=1; $i <= $objects_count; $i++) {
	for ($k=0; $k < rand(1, $max_purchases_on_object); $k++) {
		$purchase = Model::factory('Purchases')->create();
		$purchase->object_id = $i;
		$purchase->worker_id = Model::factory('Foremans')->where('object_id', $i)->find_one()->worker_id;
		$purchase->material = $faker->word;
		$purchase->cost = rand($min_material_cost, $max_material_cost);
		$purchase->save();
	}
}


//Generate Objects
for ($i=1; $i <= $objects_count; $i++) {
	$object = Model::factory('Objects')->create();
	$object->name = $faker->company;
	$object->date_start = $faker->dateTimeBetween($start_date, $end_date)->format('Y-m-d');
	$object->date_end = $faker->dateTimeBetween($object->date_start, $end_date)->format('Y-m-d');
	$object->date_real_end = $faker->dateTimeBetween($object->date_start, $end_date)->format('Y-m-d');
	$object->work = 0;
	$object->material = 0;
	$object->save();
}


//Add work and material to Objects
for ($i=1; $i <= $objects_count; $i++) {
	$object = Model::factory('Objects')->find_one($i);
	$work_balance = Model::factory('WorkBalances')->find_one($i);
	$material_balance = Model::factory('MaterialBalances')->find_one($i);
	$object->work = 0 - $work_balance->value;
	$object->material = 0 - $material_balance->value;
	$object->save();
}