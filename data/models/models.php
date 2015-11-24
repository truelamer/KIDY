<?
//Models
class Objects extends Model {}
class Professions extends Model {}
class Purchases extends Model {}
class Workers extends Model {}
class ObjectsWorkers extends Model {}
class Payments extends Model {}
class MaterialBalances extends Model { 
	public static $_id_column = 'object_id';
}
class WorkBalances extends Model {
	public static $_id_column = 'object_id';
}
class Actions extends Model {}
class Permissions extends Model {}
class Foremans extends Model {}
?>