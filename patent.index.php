<?
	$degree = $_GET['degree'];
	if ($degree == "")
		$degree = 2;
?>
<div class="grid_4 spaced-small" style="margin-right: 30px;">
    <div class="ucd-pod ui-corner-all" id="map-inputs">
		<h1 class="close-above ">Patents Awarded</h1>
		<p class="close-above">Discover awarded Patents by clicking on map or using the options below.</p>
    
		<? require_once('map.menu'); ?>
		<fieldset>
			<select id="agency" class="tooltip" title="Select all patents or those that have<br/> been influenced by funded grants.">
				<option value="">All granted patents</option>
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
			<div class="tooltip" title="Move the slider to select patents related to federal<br/> funding by a specified degree of seperation.">
				<p>&nbsp;Citation within <span id="degreeTxt">2</span> degrees</p><span style="display:none" id="degree"><?=$degree?></span>
				<div class="slider">
					<div class="ui-slider ui-slider-horizontal ui-widget ui-widget-content ui-corner-all" id="degreeSlider">
						<div class="ui-slider-range ui-widget-header"></div>
						<a style="left: 0%;" class="ui-slider-handle ui-state-default ui-corner-all" href="#"></a>
					<div class="ui-slider-range ui-widget-header" ></div>
					</div>
				</div>
			</div>
		</fieldset>
		<fieldset>
        	<p>Refine results by selecting institutions or classifications:</p>
			<div id="topTbl" >
				<h3><a href="#">Top Research Institutions</a></h3>
				<div id="sectOrg" class="list tooltip" title="Select multiple institutions by clicking on<br/>each institution; to de-select, click again."></div>
				<h3><a href="#">Top Classifications</a></h3>
				<div id="sectLabel" class="list tooltip" title="Select multiple classifications by clicking on<br/>each class; to de-select, click again."></div>
			</div>
		</fieldset>
		<div id="downloadcsv"></div>
    </div>
</div>
<div class="grid_11 alpha omega spaced-small">
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
		$.data($("#map")[0], "params", { table:"pat", degree:"0-<?=$degree?>" });
		$.data($("#map")[0], "latlng", null);

		$("#yearSlider").slider({
			range: true,
			values: [2000, 2009],
			min: 2000, max: 2009,
			slide: function(event, ui) { slideChg("#yrRg", "year", ui, false);	},
			stop: function(event, ui) { slideChg("#yrRg", "year", ui, true); }
		});

		$("#degreeSlider").slider({
			values: [<?=$degree?>],
			min: 0, max: 3,
			slide: function(event, ui) { 
				slideChg("#degree", "degree", {values: [0, ui.values[0]]}, false); 
				$("#degreeTxt").html(ui.values[0]);
			},
			stop: function(event, ui) { 
				slideChg("#degree", "degree", {values: [0, ui.values[0]]}, true); 
				$("#degreeTxt").html(ui.values[0]);
			}
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
