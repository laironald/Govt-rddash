<?php

	$CD = $_GET['CD'];
	$State = $_GET['State'];

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

<div class="grid_4 spaced-small" >
    <div class="ucd-pod ui-corner-all" id="map-inputs" style="height:500px; width: 200px; position: fixed;">
    <form>
    
    	<h1 class="close-above ">Grants Awarded</h1>
    	<p class="close-above">Use this map to discover information on Grants that are awarded in your state and congressional district.</p>
		<!--
    	<fieldset>
        <p>Select Agency:</p>
        <p><input type="radio" name="NIH-NSF" value="NIH" checked /> NIH Total Awards<br/>
		<input type="radio" name="NIH-NSF" value="NSF" /> NSF Total Awards</p>
		</fieldset>
		-->
        
        <fieldset>
        <p id="polyselect">United States</p>
		</fieldset>

		<fieldset>
			<div id="topTbl">
				<h3><a href="#">Attributes</a></h3>
				<div style="padding: 5px">
					<fieldset class="dropdown">
					<label for="agency"></label>
					<select id="agency">
						<option value="">NIH/NSF funded</option>
						<option value="NIH">NIH funded</option>
						<option value="NSF">NSF funded</option>
					</select>
					</fieldset>

					<fieldset>
					<p>&nbsp;Year(s): <span class="slider" id="yrRg">2001-2009</span></p>
					<div class="slider">
					    <div class="ui-slider ui-slider-horizontal ui-widget ui-widget-content ui-corner-all" id="yearSlider">
					        <div class="ui-slider-range ui-widget-header"></div>
					        <a style="left: 0%;" class="ui-slider-handle ui-state-default ui-corner-all" href="#"></a>
					    <div class="ui-slider-range ui-widget-header" ></div>
					    </div>
					</div>
					</fieldset>

					<fieldset>
					<p>&nbsp;Amount: <span class="slider" id="amtRg">0.00-130.00</span>m</p>
					<div class="slider">
					    <div class="ui-slider ui-slider-horizontal ui-widget ui-widget-content ui-corner-all" id="amtSlider">
					        <div class="ui-slider-range ui-widget-header"></div>
					        <a style="left: 0%;" class="ui-slider-handle ui-state-default ui-corner-all" href="#"></a>
					    <div class="ui-slider-range ui-widget-header" ></div>
					    </div>
					</div>
					</fieldset>
				</div>
				<h3><a href="#">Top Research Institutions</a></h3>
				<div id="sectOrg" class="list"></div>
				<h3><a href="#">Top Topics</a></h3>
				<div id="sectLabel" class="list"></div>
			</div>
		</fieldset>            
	</form>
    </div>
</div>
<div class="grid_12 omega spaced-small">
    <div style="border:1px solid #ccc;height:500px; margin-bottom:10px;">    
		<!-- RL ADD START -->
		<div id="map"></div>
		<!-- RL ADD END -->
    </div>

	<div style="height:700px" class="dataTables_wrapper" id="example_wrapper"></div> 
</div>    










<!-- RL EDIT START -->
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
		$.data($("#map")[0], "params", { table:"grant" });
		$.data($("#map")[0], "latlng", null);

		$("#yearSlider").slider({
			range: true,
			values: [2001, 2009],
			min: 2001, max: 2009,
			slide: function(event, ui) { slideChg("#yrRg", "year", ui, false); },
			stop: function(event, ui) { slideChg("#yrRg", "year", ui, true); }
			/*
				values: [2009],
				min: 2000, max: 2009,
				slide: function(event, ui) { slideChg("#yrRg", "year", ui);	}
			*/
		});
		$("#amtSlider").slider({
			range: true,
			values: [0, 62],
			min: 0, max: 62,
			slide: function(event, ui) { 
				$("#amtRg").html(amtRg(ui));
			},
			stop: function(event, ui) { 
				$("#amtRg").html(amtRg(ui));
				hoverStats = null;
				var attrb = new Object();
				attrb["amt"] = $("#amtRg").html();
				setVal(attrb);
			}
			/*
				values: [2009],
				min: 2000, max: 2009,
				slide: function(event, ui) { slideChg("#yrRg", "year", ui);	}
			*/
		});

		initializeGeo("#map", false);
		<? if ($CD=="") { ?>
			setVal({});
		<? } else { ?>
			setVal({CD:"<?=$CD?>", State:"<?=$State?>"});
		<? } ?>
	});	
	function amtRg(ui) {
		rg = [];
		for(i=0; i<2; i++)
			if (ui.values[i] <= 20)
				rg.push(((ui.values[i]*50000)/1000000).toFixed(2));
			else if (ui.values[i] <= 38)
				rg.push((((ui.values[i]-20)*500000+1000000)/1000000).toFixed(2));
			else
				rg.push((((ui.values[i]-38)*5000000+10000000)/1000000).toFixed(2));
		return rg.join("-");
	}




</script>
<script src="js/keydragzoom_packed.js" type="text/javascript"></script>
<!-- RL EDIT END -->

