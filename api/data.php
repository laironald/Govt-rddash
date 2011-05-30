<?php

	require_once '../config/config.php';
	require_once '../framework/framework.php';


	if ($_POST['CD'] == "" && $_POST['State'] == "")
		return;

	if ($_POST['mode'] == "csv") {
		$filename = $_POST['table']."_download_".$_POST['State'].$_POST['CD'].(( $_POST['mode2']=='latlng' )?"_sub":"").".csv";
		header("Content-type: application/csv");
		header("Content-Disposition: attachment; filename=".$filename);
		header("Pragma: no-cache");
		header("Expires: 0");
	}

	/*
	 * Script:    DataTables server-side script for PHP and MySQL
	 * Copyright: 2010 - Allan Jardine
	 * License:   GPL v2 or BSD (3-point)
	 */
	
	/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
	 * Easy set variables
	 */
	
	/* Array of database columns which should be read and sent back to DataTables. Use a space where
	 * you want to insert a non-database field (for example a counter or static image)
	 */

/*
	$mc  = new Mongo();
	$db  = $mc->test;
	$col = $db->foo;
	
	$col->insert(array("POST" => $_POST));
*/

	$mkey = "hover_".implode("|", $_POST);
	$output = false;
	$output = (strlen($mkey)<=250)? memcach_d($key=$mkey): false;

	if ($output === false) {
		if ($_POST['table'] == "pat") {
			if ($_POST['mode'] == "csv") {
				$aColumns = array( 'IDNum', 'Agency', 'degree', 'OrgName', 'Title', 'Date', 'Label', 'City', 'State', 'Lng', 'Lat' );
				$sColumns = array( 'Patent', 'Agency', 'degree', 'Assignee', 'Title', 'AppDate', 'Technology', 'City', 'State', 'Lng', 'Lat' );
			}
			else
				$aColumns = array( 'IDNum', 'Agency', 'OrgName', 'Title', 'Date', 'Label', 'City', 'State', 'degree', 'PILinkDegree');
		} else if ($_POST['table'] == "app") {
			if ($_POST['mode'] == "csv") {
				$aColumns = array( 'IDNum', 'Agency', 'OrgName', 'Date', 'Label', 'City', 'State', 'Lng', 'Lat' );
				$sColumns = array( 'Patent', 'Agency', 'Assignee', 'AppDate', 'Technology', 'City', 'State', 'Lng', 'Lat' );
			}
			else
				$aColumns = array( 'IDNum', 'Agency', 'OrgName', 'Date', 'Label', 'City', 'State', 'PILinkDegree');
		} else if ($_POST['table'] == "pub") {
			//labels = ['Year', 'Grant Number', 'Federal Agency', 'Receiving Institution', 'Description', 'Grant Amount', 'City', 'State'];
			if ($_POST['mode'] == "csv") {
				$aColumns = array( 'Year', 'IDNum', 'GrantID', 'Agency', 'OrgName', 'Title', 'Journal', 'JYear', 'City', 'State', 'Lng', 'Lat' );
				$sColumns = array( 'Year', 'IDNum', 'GrantID', 'Agency', 'Institution', 'Title', 'Journal', 'Publication Year', 'City', 'State', 'Lng', 'Lat' );
			}
			else
				$aColumns = array( 'Year', 'IDNum', 'GrantID', 'Agency', 'OrgName', 'Title', 'Journal', 'JYear', 'City', 'State', 'did' ); //IDNum2 = IGNORE!
		} else if ($_POST['table'] == "grant") {
			//labels = ['Year', 'Grant Number', 'Federal Agency', 'Receiving Institution', 'Description', 'Grant Amount', 'Topic', 'City', 'State'];
			if ($_POST['mode'] == "csv") {
				$aColumns = array( 'Year', 'GrantID', 'Agency', 'Amount', 'OrgName', 'Title', 'Label', 'City', 'State', 'Lng', 'Lat' );
				$sColumns = array( 'Year', 'GrantID', 'Agency', 'GrantAmount', 'Institution', 'Title', 'Topic', 'City', 'State', 'Lng', 'Lat' );
			}
			else
				$aColumns = array( 'Year', 'GrantID', 'Agency', 'Amount', 'OrgName', 'Title', 'Label', 'City', 'State', 'IDNum', 'IDNum2' ); //IDNums = IGNORE!
		}
		$sTable = $_POST['table']."Geo";


		/* DB table to use */
		/* Database connection information */
		$gaSql['user']       = $GLOBALS['db_user'];
		$gaSql['password']   = $GLOBALS['db_pass'];
		$gaSql['db']         = $GLOBALS['db_name'];
		$gaSql['server']     = $GLOBALS['db_ip'];
	
	
		/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
		 * If you just want to use the basic configuration for DataTables with PHP server-side, there is
		 * no need to edit below this line
		 */
	
		/* 
		 * MySQL connection
		 */
		$gaSql['link'] =  mysql_pconnect( $gaSql['server'], $gaSql['user'], $gaSql['password']  ) or
			die( 'Could not open connection to server' );
	
		mysql_select_db( $gaSql['db'], $gaSql['link'] ) or 
			die( 'Could not select database '. $gaSql['db'] );
	
	
		/* 
		 * Paging
		 */
		$sLimit = "";
		if ($_POST['mode'] == "csv")
			$sLimit = "LIMIT 0, 5000";
		else if ( isset( $_POST['iDisplayStart'] ) && $_POST['iDisplayLength'] != '-1' ) {
			$sLimit = "LIMIT ".mysql_real_escape_string( $_POST['iDisplayStart'] ).", ".
				mysql_real_escape_string( $_POST['iDisplayLength'] );
		}
	
		/*
		 * Ordering
		 */
		if ( isset( $_POST['iSortCol_0'] ) ) {
			$sOrder = "ORDER BY  ";
			for ( $i=0 ; $i<intval( $_POST['iSortingCols'] ) ; $i++ ) {
				if ( $_POST[ 'bSortable_'.intval($_POST['iSortCol_'.$i]) ] == "true" ) {
					$sOrder .= $aColumns[ intval( $_POST['iSortCol_'.$i] ) ]."
					 	".mysql_real_escape_string( $_POST['sSortDir_'.$i] ) .", ";
				}
			}
		
			$sOrder = substr_replace( $sOrder, "", -2 );
			if ( $sOrder == "ORDER BY" )
				$sOrder = "";
		}
	
	
		/* 
		 * Filtering
		 * NOTE this does not match the built-in DataTables filtering which does it
		 * word by word on any field. It's possible to do here, but concerned about efficiency
		 * on very large tables, and MySQL's regex functionality is very limited
		 */
		$sWhere = array();
		if ( $_POST['sSearch'] != "" ) {
			$filter = "(";
			for ( $i=0 ; $i<count($aColumns) ; $i++ )
				$filter .= $aColumns[$i]." LIKE '%".mysql_real_escape_string( $_POST['sSearch'] )."%' OR ";
			$filter = substr_replace( $filter, "", -3 );
			$filter .= ')';
			$sWhere[] = $filter;
		}
	
		/* Individual column filtering */
		for ( $i=0 ; $i<count($aColumns) ; $i++ )
			if ( $_POST['bSearchable_'.$i] == "true" && $_POST['sSearch_'.$i] != '' ) 
				$sWhere[] = $aColumns[$i]." LIKE '%".mysql_real_escape_string($_POST['sSearch_'.$i])."%' ";



	 	/* ---------------------------------------------

			HERE IS WHERE ALL THE WHERE STATEMENTS GO 

		   --------------------------------------------- */

		$sWhere[] = "State = '".mysql_real_escape_string($_POST['State'])."'";
		if ($_POST['CD'] != '0' && $_POST['CD'] != '')
			$sWhere[] = "CD = '".mysql_real_escape_string($_POST['CD'])."'";
		else
			$sWhere[] = "Level = 1";
		if (array_key_exists('Org', $_POST) and $_POST['Org']!="") {
			$org = preg_split("/[,]/", mysql_real_escape_string($_POST['Org']));
			/*
				//This converts A123 > A000000000123
				preg_match_all("/([A-Z])([0-9]+)/i", mysql_real_escape_string($_POST['Org']), $orgs);
				$org = array();
				$i = 0;
				foreach($orgs[2] as $x) {
					$org[] = $orgs[1][$i].sprintf("%012d", (int)$x);
					$i = $i + 1;
				}
			*/
			$sWhere[] = "Org in ('".implode("', '", $org)."')";
		}
		if (array_key_exists('Label', $_POST) and $_POST['Label']!="") {
			$label = array();
			foreach(preg_split("/[,]/", $_POST['Label']) as $x)
				$label[] = 'concat("/",Label,"/") LIKE "%/'.mysql_real_escape_string($x).'/%"';
			$sWhere[] = "(".implode(" OR ", $label).")";
		}
		$degree = preg_split("/[-]/", $_POST['degree']);
		if (array_key_exists('agency', $_POST))
			if ($_POST['agency']!="") {
				$sWhere[] = 'concat("/",agency,"/") LIKE "%/'.mysql_real_escape_string($_POST['agency']).'/%"';
				if ($_POST['degree']!="") {
					if (count($degree)==1)
						$degree[] = $degree[0];
					$sWhere[] = "degree <= ".mysql_real_escape_string($degree[1]);
				}
			}
		if (array_key_exists('amt', $_POST)) {
			$amt = preg_split("/[-]/", $_POST['amt']);
			if (count($amt)==1)
				$amt[] = $amt[0];
			$sWhere[] = "amount BETWEEN ".mysql_real_escape_string($amt[0]*1000000)." AND ".mysql_real_escape_string($amt[1]*1000000);
		}
		if ($_POST['year']!="") {
			$year = preg_split("/[-]/", $_POST['year']);
			if (count($year)==1)
				$year[] = $year[0];
			$sWhere[] = "Year BETWEEN '".mysql_real_escape_string($year[0])."' AND '".mysql_real_escape_string($year[1])."'";
		}

	/*
		if ($_POST['mode2']=='latlng') {
			$latlng = array();
			$lat = preg_split("/[,]/", $_POST['lat']);
			$lng = preg_split("/[,]/", $_POST['lng']);
			for($i=0; $i<count($lat); $i+=2) {
				$latlng[] = "(lat BETWEEN ".mysql_real_escape_string($lat[$i])." AND ".mysql_real_escape_string($lat[$i+1])." AND "
							."lng BETWEEN ".mysql_real_escape_string($lng[$i])." AND ".mysql_real_escape_string($lng[$i+1]).")";
			}
			$sWhere[] = "(".implode(" OR ", $latlng).")";
		}
	*/
		if ($_POST['mode2']=='latlng') {
			$latlng = array();
			$coords = preg_split("/[,]/", $_POST['coords']);
			for($i=0; $i<count($coords); $i+=2) {
				$latlng[] = "(lat=".mysql_real_escape_string($coords[$i])." AND lng=".mysql_real_escape_string($coords[$i+1]).")";
			}
			$sWhere[] = "(".implode(" OR ", $latlng).")";
		}
		$sWhere = count($sWhere)>0?("WHERE ".implode(" AND ", $sWhere)):"";

		/*
		 * SQL queries
		 * Get data to display
		 */

		$sQuery = "
			SELECT SQL_CALC_FOUND_ROWS ".str_replace(" , ", " ", implode(", ", $aColumns))."
			FROM   $sTable
			$sWhere
			$sOrder
			$sLimit
		";
		//if ($_POST['mode'] == 'csv')
		//	echo $sQuery;

		$rResult = mysql_query( $sQuery, $gaSql['link'] ) or die(mysql_error());
	
		/* Data set length after filtering */
		$sQuery = "
			SELECT FOUND_ROWS()
		";
		$rResultFilterTotal = mysql_query( $sQuery, $gaSql['link'] ) or die(mysql_error());
		$aResultFilterTotal = mysql_fetch_array($rResultFilterTotal);
		$iFilteredTotal = $aResultFilterTotal[0];
	
		/* Total data set length */
		$sQuery = "
			SELECT COUNT(*)
			FROM   $sTable
		";
		$rResultTotal = mysql_query( $sQuery, $gaSql['link'] ) or die(mysql_error());
		$aResultTotal = mysql_fetch_array($rResultTotal);
		$iTotal = $aResultTotal[0];
	
	
		/*
		 * Output
		 */

		if ($_POST['mode'] == "csv") {
			$output = array();

			$row = array();
			for ( $i=0 ; $i<count($sColumns) ; $i++ )
				if ( $sColumns[$i] != ' ' )
					$row[] = '"'.str_replace('"', '""', $sColumns[$i]).'"';
			$output[] = implode(',', $row);
	
			while ( $aRow = mysql_fetch_array( $rResult ) )	{
				$row = array();
				for ( $i=0 ; $i<count($aColumns) ; $i++ )
					if ( $sColumns[$i] != ' ' )
						$row[] = '"'.str_replace('"', '""', $aRow[ $aColumns[$i] ]).'"';
				$output[] = implode(',', $row);
			}
			if ($iFilteredTotal > 5000)
				$output[] = "\nResults limited to top 5000 records (of ".$iFilteredTotal.")";
			$output = implode("\n", $output);
		} else {

			$output = array(
				"sEcho" => intval($_POST['sEcho']),
				"iTotalRecords" => $iFilteredTotal,
				//"iTotalRecords" => $iTotal,
				"iTotalDisplayRecords" => $iFilteredTotal,
				"aaData" => array()
			);

			/*  */
			$res = @mysql_query("SELECT label, descrip FROM labels WHERE tbl='".$_POST['table']."'", $gaSql['link']);
			$lbl = array();
			while ($datum = @mysql_fetch_assoc($res))
				$lbl[$datum['label']] = $datum['descrip'];

			function labelIt($txt) {
				global $lbl;
				$lbls = array();
				foreach(preg_split("/[\/]/", $txt) as $x)
					if (in_array(trim($lbl[$x]), $lbls)==false)
						$lbls[] = trim($lbl[$x]);
				return implode("<br/>", $lbls);			
			}

			while ( $aRow = mysql_fetch_array( $rResult ) )	{
				$row = array();
				for ( $i=0 ; $i<count($aColumns) ; $i++ ) {
					if ( $_POST['table'] == "pat" ) {
						if ( $aColumns[$i] == "IDNum" ) {
							/* Special output formatting for 'version' column */
							$pat = $aRow[$aColumns[$i]];
							preg_match("/[A-Z]+/i", $pat, $lets);
							preg_match("/[0-9]+/",  $pat, $nums);
							$pat = $lets[0].intval($nums[0]);

							$url = "http://patft.uspto.gov/netacgi/nph-Parser?Sect1=PTO2&Sect2=HITOFF&u=%2Fnetahtml%2FPTO%2Fsearch-adv.htm&r=1&f=G&l=50&d=PTXT&p=1&p=1&S1=".$pat.".PN.|http://www.google.com/patents?q=".$pat;
							$row[] = "<a class='data' href='api/iframe.php?q=".urlencode($url)."' target='_blank'>".$aRow[ $aColumns[$i] ]."</a>";
						} else if ( $aColumns[$i] == "Agency") {
							if ($aRow['PILinkDegree']==1)
								$row[] = $aRow[ $aColumns[$i] ]."<br/> (Funded PI)";
							else if ($aRow['degree']>0 and $aRow['degree'] <= intval($degree[1]))
								$row[] = $aRow[ $aColumns[$i] ]."<br/> (".$aRow['degree']." degrees)";
							else if ($aRow['degree']==0)
								$row[] = $aRow[ $aColumns[$i] ];
							else
								$row[] = "";
						} else if ( $aColumns[$i] == "Label") {
							$row[] = labelIt($aRow[ $aColumns[$i] ]);
						} else if ( in_array($aColumns[$i], array(' ',  'degree', 'PILinkDegree')) === false )
							$row[] = $aRow[ $aColumns[$i] ];
					} else if ( $_POST['table'] == "app" ) {
						if ( $aColumns[$i] == "IDNum" ) {
							$url = "http://appft.uspto.gov/netacgi/nph-Parser?Sect1=PTO2&Sect2=HITOFF&u=/netahtml/PTO/search-adv.html&r=1&f=G&l=50&d=PG01&p=1&S1=".$aRow[$aColumns[$i]].".PGNR.|http://www.google.com/patents?q=%22US+".substr($aRow[$aColumns[$i]], 0, 4)."/".substr($aRow[$aColumns[$i]], 4)."%22";
							$row[] = "<a class='data' href='api/iframe.php?q=".urlencode($url)."' target='_blank'>".$aRow[ $aColumns[$i] ]."</a>";
						} else if ( $aColumns[$i] == "Agency") {
							if ($aRow['PILinkDegree']==1)
								$row[] = $aRow[ $aColumns[$i] ]."<br/> (Funded PI)";
							else 
								$row[] = $aRow[ $aColumns[$i] ];
						} else if ( $aColumns[$i] == "Label") {
							$row[] = labelIt($aRow[ $aColumns[$i] ]);
						} else if ( in_array($aColumns[$i], array(' ',  'degree', 'PILinkDegree')) === false )
							$row[] = $aRow[ $aColumns[$i] ];
					} else if ( $_POST['table'] == "pub" ) {
						if ( $aColumns[$i] == "GrantID" ) {
							/* Special output formatting for 'version' column */
							$ID = $aRow[$aColumns[$i]];
							if ($aRow['Agency']=="NSF") {
								$ID = (($ID<1000000)?'0':'').(($ID<100000)?'0':'').$ID;
								$url = "http://www.research.gov/fedAwardId/".$ID."|http://www.nsf.gov/awardsearch/showAward.do?AwardNumber=".$ID."|http://usaspending.gov/search?query=".$ID;
							} else if ($aRow['Agency']=="NIH")
								$url = "http://projectreporter.nih.gov/project_info_description.cfm?aid=".substr($aRow['did'], 4)."|http://usaspending.gov/search?query=".$aRow['IDNum2'];
							$row[] = "<a class='data' href='api/iframe.php?q=".urlencode($url)."' target='_blank'>".$ID."</a>";
						} else if ( $aColumns[$i] == "IDNum" && $aRow[$aColumns[$i+2]] == "NIH" ) {
							/* Special output formatting for 'version' column */
							$ID = $aRow[$aColumns[$i]];
							$url = "http://www.ncbi.nlm.nih.gov/pubmed/".substr($ID, 5);
							$row[] = "<a class='data' href='api/iframe.php?q=".urlencode($url)."' target='_blank'>".$ID."</a>";
						} else if ( $aColumns[$i] == "Label")
							$row[] = labelIt($aRow[ $aColumns[$i] ]);
						else if ( in_array($aColumns[$i], array(' ', 'did')) === false )
							$row[] = $aRow[ $aColumns[$i] ];
					} else if ( $_POST['table'] == "grant") {
						//$aColumns = array( 'Year', 'GrantID', 'Agency', 'OrgName', 'Title', 'Amount', 'Label', 'City', 'State', 'Lng', 'Lat' );
						if ( $aColumns[$i] == "GrantID" ) {
							/* Special output formatting for 'version' column */
							$ID = $aRow[$aColumns[$i]];
							if ($aRow['Agency']=="NSF") {
								$ID = (($ID<1000000)?'0':'').(($ID<100000)?'0':'').$ID;
								$url = "http://www.research.gov/fedAwardId/".$ID."|http://www.nsf.gov/awardsearch/showAward.do?AwardNumber=".$ID."|http://usaspending.gov/search?query=".$ID;
							} else if ($aRow['Agency']=="NIH") 
								$url = "http://projectreporter.nih.gov/project_info_description.cfm?aid=".substr($aRow['IDNum'], 4)."|http://usaspending.gov/search?query=".$aRow['IDNum2'];
							$row[] = "<a class='data' href='api/iframe.php?q=".urlencode($url)."' target='_blank'>".$ID."</a>";
						} else if ( $aColumns[$i] == "Amount")
							$row[] = "$".number_format($aRow[ $aColumns[$i] ]);
						else if ( $aColumns[$i] == "Label")
							$row[] = labelIt($aRow[ $aColumns[$i] ]);
						else if ( in_array($aColumns[$i], array(' ',  'IDNum2', 'IDNum')) === false )
							$row[] = $aRow[ $aColumns[$i] ];
					}
				}
				$output['aaData'][] = $row;
			}
			$output = json_encode($output);
		}
		memcach_d($key=$mkey, $output);
	}
	echo $output;
?>
