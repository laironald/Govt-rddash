<script>
	function update(html, id) { 
		$(id).children().children('.content').children().html(html); 
		$(id).children().children('.content').children().tinyscrollbar();
	}
</script>

<?php
	include_once 'config/config.php';
	include_once 'framework/framework.php';

	$conn = get_db_conn();
	$obs = 50;

	$where = array();
	if (isset($_GET['year'])) {
		$year = split("[-]", $_GET['year']);
		$where[] = (count($year)==1)?vsprintf("Year='%s'", $year):vsprintf("Year BETWEEN '%s' AND '%s'", $year);
	}

	foreach($_GET as $k=>$v)
		if (!in_array($k, array('mode', 'year', 'page', 'var', 'id', 'agency')))
			$where[] = vsprintf("%s='%s'", array($k, $v));

	if ($_GET['page']=='')
		$_GET['page']='0';
	$_GET['page'] = intval($_GET['page']);

	//echo var_dump($_GET);



	function navigate() {
		global $_GET;
		global $conn;
		global $obs;
		global $where;
		$cnt = @mysql_fetch_assoc(@mysql_query(vsprintf("SELECT count(distinct %s) AS cnt FROM pat%s WHERE %s", array($_GET['var'], $_GET['var'], implode(" AND ", $where)))));
		?>
			<a class='nav' onclick="refreshMapGeo('x|<?=$_GET['var']?>')">Reset View</a> | pg <?=$_GET['page']+1?> of <?=ceil($cnt['cnt']/$obs)?><br/>
			<? if ($_GET['page'] > 0) { ?>
				<a class='nav' onclick="$.ajax({ url:'stats.php?<?=posturl($_GET, array('page'=>$_GET['page']-1))?>', success: function(html){ update(html, '#<?=$_GET['id']?>'); }});">Previous</a>
			<? } ?>
			<? if ($cnt['cnt'] > $obs*($_GET['page']+1)) { ?>
				<a class='nav' onclick="$.ajax({ url:'stats.php?<?=posturl($_GET, array('page'=>$_GET['page']+1))?>', success: function(html){ update(html, '#<?=$_GET['id']?>'); }});">Next</a>
			<? } ?>
		<?
	}

	function drawtops() {
		global $_GET;
		global $conn;
		global $obs;
		global $where;

		//I need to somehow incorporate Agency into this...

		$query = vsprintf("SELECT %sName, %s, count(*) AS cnt FROM pat%s WHERE %s GROUP BY %s ORDER BY cnt DESC LIMIT %d, %d", array($_GET['var'], $_GET['var'], $_GET['var'], implode(" AND ", $where), $_GET['var'], $obs*$_GET['page'], $obs));
		$cnt = $obs*$_GET['page'];
		$res = @mysql_query($query, $conn);		
		while ($datum = @mysql_fetch_assoc($res)) {
			$cnt++;
			?>
			<div class="ol" onclick="refreshMapGeo('<?=$datum[$_GET['var']].'|'.$_GET['var']?>');">
				<div class="s1"><?=$cnt?>)</div>
				<div class="s2"><?=($datum[$_GET['var'].'Name']=="")?"[Blank]":$datum[$_GET['var'].'Name'];?> <?//=$datum['cnt']?></div>
			</div>
			<?
		}
		echo "</ol>";
	}
?>
<div class="statsside">
	<div class="top"><? navigate(); ?></div>
	<div class="scroll sidebar">
		<div class="scrollbar"><div class="track"><div class="thumb"><div class="end"></div></div></div></div>
		<div class="viewport"><div class="overview"><? drawtops(); ?></div></div>
	</div>
</div>

