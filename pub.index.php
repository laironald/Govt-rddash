<?php

	$CD = $_GET['CD'];
	$State = $_GET['State'];
	$degree = $_GET['degree'];
	if ($degree == "")
		$degree = 2;

	//Specify city as the entry point
	if ($_GET['City']!='' and $_GET['State']!='') {
		$conn = get_db_conn();
		$query = 'SELECT CD FROM USCities WHERE City="'.$_GET['City'].'" AND State="'.$_GET['State'].'" LIMIT 1';
		$res = @mysql_fetch_assoc(@mysql_query($query, $conn));
		$State = $_GET['State'];
		$CD = $res['CD'];
	} elseif ($_GET['Zipcode']!='') {
		$conn = get_db_conn();
		$query = 'SELECT CD,State FROM USCities WHERE Zipcode='.intval($_GET['Zipcode']);
		$res = @mysql_fetch_assoc(@mysql_query($query, $conn));
		$CD = $res['CD'];
		$State = $res['State'];
	}

?>
<div class="grid_4 spaced-small" style="margin-right: 30px;">
    <div class="ucd-pod ui-corner-all" id="map-inputs">
		<h1 class="close-above ">Publications</h1>
		<p class="close-above">Discover Publications by clicking on map or using the options below.</p>
    
		<? require_once('map.menu'); ?>
		<fieldset>
			<select id="agency" class="tooltip" title="Select a federal agency to see the publications<br/> attributed to funded grants.">
				<option value="">NIH/NSF</option>
				<option value="NIH">National Institutes of Health</option>
				<option value="NSF">National Science Foundation</option>
			</select>
		</fieldset>
		<fieldset class="close-below">
			<div class="tooltip" title="Use the sliders on both ends to narrow the<br/>time range for years grants were awarded.">
				<p>&nbsp;Year(s): <span class="slider" id="yrRg">2000-2009</span></p>
				<div class="slider">
					<div class="ui-slider ui-slider-horizontal ui-widget ui-widget-content ui-corner-all" id="yearSlider">
						<div class="ui-slider-range ui-widget-header"></div>
						<a style="left: 0%;" class="ui-slider-handle ui-state-default ui-corner-all" href="#"></a>
						<a style="left: 100%;" class="ui-slider-handle ui-state-default ui-corner-all" href="#"></a>
					<div class="ui-slider-range ui-widget-header" ></div>
					</div>
				</div>
			</div>
		</fieldset>
		<fieldset>
        	<p>Refine results by selecting institutions or topics:</p>
			<div id="topTbl">
				<h3><a href="#">Top Research Institutions</a></h3>
				<div id="sectOrg" class="list tooltip" title="Select multiple institutions by clicking on<br/>each institution; to de-select, click again."></div>
				<h3><a href="#">Top Topics</a></h3>
				<div id="sectLabel" class="list tooltip" title="Select multiple topics by clicking on<br/>each topic; to de-select, click again."></div>
			</div>
		</fieldset>
		<div id="downloadcsv"></div>
    </div>
</div>
<div class="grid_11 omega spaced-small">
    <div class="mapWrapper ui-widget-content ui-corner-all">
		<div id="map"></div>
		<div class="dataTables_wrapper" id="example_wrapper"></div> 
    </div>
</div>    


<script src="js/map_framework_f.js" type="text/javascript"></script>
<script type="text/javascript" charset="utf-8">
	/* INIT STUFF -- MAY NOT BE ALL NECESSARY NOW */
	google.load('maps', '3', { other_params: 'sensor=false' });
	google.load("jqueryui", "1.8.4");
	var G = google.maps;
	var styles = [];
	var markerClusterer = null;
	var map = null;
	var info = null;
	var init = true;
	var dataB = null;
	var prevLine = null;
	var kml = null;		
	var mousey = null;
	var ctrl = false;
	
	$(document).ready(function() {	
		<? require_once("js/map.js"); ?>
		//set initial table
		$.data($("#map")[0], "params", { table:"pub" });
		$.data($("#map")[0], "latlng", null);

		$("#yearSlider").slider({
			range: true,
			values: [2000, 2009],
			min: 2000, max: 2009,
			slide: function(event, ui) { slideChg("#yrRg", "year", ui, false);	},
			stop: function(event, ui) { slideChg("#yrRg", "year", ui, true); }
		});

		initializeGeo("#map", false);
		<? if ($CD=="") { ?>
			setVal({});
		<? } else { ?>
			setVal({CD:"<?=$CD?>", State:"<?=$State?>"});
		<? } ?>
		<? require_once("js/map.js"); ?>
	});	
</script>
<script src="js/keydragzoom_packed.js" type="text/javascript"></script>
<!-- RL EDIT END -->

