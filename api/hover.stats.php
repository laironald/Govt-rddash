<?php

	require_once '../config/config.php';
	require_once '../framework/framework.php';

	$conn = get_db_conn();
	//memcache

	//NEED TO TAKE INTO ACCOUNT YEARS, AGENCIES, ETC
	$mkey = "hover_".implode("|", $_GET);
	$json = (strlen($mkey)<=250)? memcach_d($key=$mkey): false;

	if ($json === false) {
		$table = $_GET['table']."Geo";

		$where = array();
		if ($table == "grantGeo")
			$extra = ", sum(Amount) as amt";

		if ($_GET['year']!="") {
			$year = preg_split("/[-]/", $_GET['year']);
			if (count($year)==1)
				$where[] = "Year = '".mysql_real_escape_string($year[0])."'";
			else
				$where[] = "Year BETWEEN '".mysql_real_escape_string($year[0])."' AND '".mysql_real_escape_string($year[1])."'";
		}
		if ($_GET['amt']!="") {
			$amt = preg_split("/[-]/", $_GET['amt']);
			if (count($amt)==1)
				$where[] = "Amount = ".mysql_real_escape_string($amt[0]*1000000);
			else
				$where[] = "Amount BETWEEN ".mysql_real_escape_string($amt[0]*1000000)." AND ".mysql_real_escape_string($amt[1]*1000000);
		}

		$awhere = $where;
		if ($table == "patGeo" && $_GET['degree']!='') {
			$degree = preg_split("/[-]/", $_GET['degree']);
			if (count($degree)==1)
				$awhere[] = "degree = ".mysql_real_escape_string($degree[0]);
			else
				$awhere[] = "degree <= ".mysql_real_escape_string($degree[1]);
		}

		if (count($where)>0)
			$whereStr = "WHERE Level=1".(count($where)>0?(" AND ".implode(" AND ", $where)):"");
		if (count($awhere)>0)
			$awhereStr = "WHERE Level=1".(count($awhere)>0?(" AND ".implode(" AND ", $awhere)):"");


		$json = array('US'=>array(), 'CD'=>array());
	/*
		if ($table == "grantGeo")
			$query = "SELECT concat(State,Agency) AS var,count(*) AS cnt, sum(Amount) AS amt FROM ".$table.' '.$whereStr." GROUP BY State, Agency";
		else
			$query = "SELECT State AS var,count(*) AS cnt FROM ".$table.' '.$whereStr." GROUP BY State";
	*/
		$query = "SELECT concat_ws('|',Agency,State) AS var,count(*) AS cnt".$extra." FROM ".$table.' '.$awhereStr." GROUP BY State, Agency";
		$res = @mysql_query($query, $conn);		
		$ag = array();
		$type='US';
		while ($datum = @mysql_fetch_assoc($res)) {
			$agency = preg_split("/[|]/", $datum['var']);
			foreach(preg_split("/[\/]/", $agency[0]) as $x) {
				$curr = $x."|".implode("|", array_slice($agency, 1));
				$ag[$curr]['cnt'] = $ag[$curr]['cnt'] + $datum['cnt'];
				$ag[$curr]['amt'] = $ag[$curr]['amt'] + $datum['amt'];
				$json[$type][$curr] = number_format($ag[$curr]['cnt']).(($ag[$curr]['amt']>0)?(" ($".number_format($ag[$curr]['amt']).")"):"");
			}
		}
		/*extra*/
		$query = "SELECT State AS var,count(*) AS cnt FROM ".$table.' '.$whereStr." GROUP BY State";
		$res = @mysql_query($query, $conn);		
		while ($datum = @mysql_fetch_assoc($res)) 
			$json[$type][$datum['var']] = number_format($datum['cnt']);



	/*
		if ($table == "grantGeo")
			$query = "SELECT concat(State,CD,Agency) AS var,count(*) AS cnt, sum(Amount) AS amt FROM ".$table.' '.$whereStr." GROUP BY State, CD, Agency";
		else	
			$query = "SELECT concat(State,CD) AS var,count(*) AS cnt FROM ".$table.' '.$whereStr." GROUP BY State, CD";
	*/



		if (count($where)>0)
			$whereStr = count($where)>0?(" WHERE ".implode(" AND ", $where)):"";
		if (count($awhere)>0)
			$awhereStr = count($awhere)>0?(" WHERE ".implode(" AND ", $awhere)):"";

		$query = "SELECT concat_ws('|',Agency,State,CD) AS var,count(*) AS cnt".$extra." FROM ".$table.' '.$awhereStr." GROUP BY State, CD, Agency";
		$res = @mysql_query($query, $conn);		
		$ag = array();
		$type='CD';
		while ($datum = @mysql_fetch_assoc($res)) {
			$agency = preg_split("/[|]/", $datum['var']);
			foreach(preg_split("/[\/]/", $agency[0]) as $x) {
				$curr = $x."|".implode("|", array_slice($agency, 1));
				$ag[$curr]['cnt'] = $ag[$curr]['cnt'] + $datum['cnt'];
				$ag[$curr]['amt'] = $ag[$curr]['amt'] + $datum['amt'];
				$json[$type][$curr] = number_format($ag[$curr]['cnt']).(($ag[$curr]['amt']>0)?(" ($".number_format($ag[$curr]['amt']).")"):"");
			}
		}
		/*extra*/
		$query = "SELECT concat_ws('|',State,CD) AS var,count(*) AS cnt FROM ".$table.' '.$whereStr." GROUP BY State, CD";
		$res = @mysql_query($query, $conn);		
		while ($datum = @mysql_fetch_assoc($res))
			$json['CD'][$datum['var']] = number_format($datum['cnt']);
		
		memcach_d($key=$mkey, $json);
	}
	echo json_encode($json);
?>

