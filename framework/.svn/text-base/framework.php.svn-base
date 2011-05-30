<?php
//	phpinfo();

/*
	$memcache = new Memcache;
	$memcache->connect('localhost', 11211) or die ( "Could not connect" );
	$memcache->flush();
	$memcache->set('key', 'ron', MEMCACHE_COMPRESSED, 10);
	$version = $memcache->getVersion();
	echo "Version ".$version."<br/>";


	$tmp_obj = 'test';
	$memcache->set('key', $tmp_obj, false, 10) or die ("Failed to save data at the server");
	$get = $memcache->get('key');

	var_dump($get);
*/

// memcached -d -m 1024 -u root -l 127.0.0.1 -p 11211
function memcach_d($key=null, $setVal=null) {
	$memcache = new Memcache;
	$memcache->connect('localhost', 11211) or die ( "Could not connect to memcache" );

	if ($key === null)
		return $memcache;
	else {
		if ($setVal === null) {
			$get = $memcache->get($key);
			return $get;
		} else {
			$memcache->set($key, $setVal, MEMCACHE_COMPRESSED);
			return $memcache;
		}
	}
}

function get_db_conn() {
	$conn = @mysql_connect($GLOBALS['db_ip'], $GLOBALS['db_user'], $GLOBALS['db_pass']);
	@mysql_select_db($GLOBALS['db_name'], $conn);
	return $conn;
}

function posturl($array, $replace=array()) {
	foreach($array as $k=>$v)		
		$where[] = vsprintf("%s=%s", array($k, urlencode((array_key_exists($k, $replace))?$replace[$k]:$v)));
	echo implode("&", $where);
}

function isIE() {
	if (isset($_SERVER['HTTP_USER_AGENT']) && (strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== false)) 
		return true;
	else
		return false;
}
