<?
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
?>