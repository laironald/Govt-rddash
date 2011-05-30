<?php

require_once '../config/config.php';


//example: http://localhost/mapTile.php?x=4&y=6&z=4&c=cd

header("Content-type: image/png");

$link = mysql_connect($db_host, $db_user, $db_pass) or die('Could not connect: '.mysql_error());
mysql_select_db("RD") or die(mysql_error());
$query = "SELECT count(*) FROM mapTile WHERE coordx={$_GET['x']} and coordy={$_GET['y']} and zoom={$_GET['z']} and category='{$_GET['c']}'";
$result = mysql_query("$query") or die("Invalid query: " . mysql_error());

if (mysql_result($result, 0)=="0") {
	exec("python drawTile.py {$_GET['x']} {$_GET['y']} {$_GET['z']} {$_GET['c']}");
}

$query = "SELECT img FROM mapTile WHERE coordx={$_GET['x']} and coordy={$_GET['y']} and zoom={$_GET['z']} and category='{$_GET['c']}'";
$result = mysql_query("$query") or die("Invalid query: " . mysql_error());

echo mysql_result($result, 0);
mysql_close($link);
