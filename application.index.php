<div class="grid_4 spaced-small" style="margin-right: 30px;">
    <div class="ucd-pod ui-corner-all" id="map-inputs">
		<h1 class="close-above ">Patent Applications</h1>
		<p class="close-above">Discover applied for Patents by clicking on map or using the options below.</p>

		<? require_once('map.menu'); ?>
		<fieldset>
			<select id="agency" class="tooltip" title="Select all application patents or those that have<br/> been influenced by funded grants.">
				<option value="">All patent applications</option>
				<option value="NIH">National Institutes of Health</option>
				<option value="NSF">National Science Foundation</option>
			</select>
		</fieldset>
		<fieldset>
			<div class="tooltip" title="Use the sliders on both ends to narrow the<br/>time range for years grants were awarded.">
				<p>&nbsp;Year(s): <span class="slider" id="yrRg">2001-2009</span></p>
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
		$.data($("#map")[0], "params", { table:"app" });
		$.data($("#map")[0], "latlng", null);

		$("#yearSlider").slider({
			range: true,
			values: [2001, 2009],
			min: 2001, max: 2009,
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

