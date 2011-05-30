<?php

	include_once 'config/config.php';
	include_once 'framework/framework.php';

	$obs = ($_GET['obs']=="")?100:$_GET['obs'];

	$conn = get_db_conn();
	$res = @mysql_query('SELECT * FROM invpat LIMIT '.$obs, $conn);

	$json = array();
	while ($datum = @mysql_fetch_assoc($res)) {
		$json[] = $datum;
	}
	echo json_encode($json);

?>

