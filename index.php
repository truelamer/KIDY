<?php
$plans_directory = 'generation_plans';
$plans = array_diff(scandir($plans_directory), array('..', '.'));

require_once 'template/top.php';
require_once 'template/main.php';
require_once 'template/bottom.php';
?>