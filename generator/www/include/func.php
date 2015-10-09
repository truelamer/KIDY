<?
function reset_auto_increment($table_name)
{
	global $dbuser, $dbpass, $dbname;
	$chandle = mysql_connect("localhost", $dbuser, $dbpass);
	mysql_select_db($dbname, $chandle);
	mysql_query("ALTER TABLE ".$table_name." AUTO_INCREMENT = 1");
}
?>