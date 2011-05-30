<?php

	include_once 'config/config.php';
	include_once 'framework/framework.php';

	$obs = 100;
	$conn = get_db_conn();

	if ($_GET['type']=='name') {
		$terms = split("[,|-] ?", strtoupper($_GET['term']));
		$query = 'SELECT a.* FROM
			 		(SELECT  Lastname, Firstname, City, State, Invnum_N
					   FROM  invpat 
					  WHERE  L3 = "'.mysql_real_escape_string(substr($terms[0], 0, 3)).'" AND
							 F3 LIKE "'.mysql_real_escape_string(substr($terms[1], 0, 1)).'%") AS a
				  WHERE  a.Lastname  LIKE "'.mysql_real_escape_string($terms[0]).'%" AND
						 a.Firstname LIKE "'.mysql_real_escape_string($terms[1]).'%"
			   GROUP BY  a.Invnum_N
			   ORDER BY  Lastname, Firstname, City, State
				  LIMIT  '.$obs;
	} else if ($_GET['type']=='city') {
		$terms = split("[,|-] ?", strtoupper($_GET['term']));
		if (count($terms)==1)
			$query = 'SELECT a.* FROM
						(SELECT  CD, City, State  FROM  USCities 
						  WHERE  City1 = "'.mysql_real_escape_string(substr($terms[0], 0, 1)).'") AS a
					  WHERE  a.City LIKE "'.mysql_real_escape_string($terms[0]).'%"
				   GROUP BY  a.City, a.State
					  LIMIT  '.$obs;
		else if (count($terms)==2)
			$query = 'SELECT a.* FROM
 				 		(SELECT  CD, City, State  FROM  USCities
						  WHERE  City1 = "'.mysql_real_escape_string(substr($terms[0], 0, 1)).'" AND
								 State LIKE "'.mysql_real_escape_string($terms[1]).'%") AS a
					  WHERE  a.City LIKE "'.mysql_real_escape_string($terms[0]).'%"
				   GROUP BY  a.City, a.State
					  LIMIT  '.$obs;
	}
	$json = array();
	$res = @mysql_query($query, $conn);		
	while ($datum = @mysql_fetch_assoc($res)) {
		$json[] = $datum;
	}
	echo json_encode($json);
?>

