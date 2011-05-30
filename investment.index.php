<div class="grid_4 spaced-small" style="margin-right: 30px;">
    <div class="ucd-pod ui-corner-all" id="map-inputs">
		<h1 class="close-above">Grants Awarded</h1>
		<p class="close-above">Discover Grants by clicking on map or using the options below.</p>
	    
		<? require_once('map.menu'); ?>
		<fieldset>
			<select id="agency" class="tooltip" title="Select a federal agency to see the<br/>grants they have funded.">
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
					<div class="ui-slider-range ui-widget-header" ></div>
					</div>
				</div>
			</div>					
			<div class="tooltip" title="Use the sliders on both ends to select<br/> grants awarded by dollar amount.">
				<p>&nbsp;Amount: <span class="slider" id="amtRg">0.00-130.00</span>m</p>
				<div class="slider">
					<div class="ui-slider ui-slider-horizontal ui-widget ui-widget-content ui-corner-all" id="amtSlider">
						<div class="ui-slider-range ui-widget-header"></div>
						<a style="left: 0%;" class="ui-slider-handle ui-state-default ui-corner-all" href="#"></a>
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
		$.data($("#map")[0], "params", { table:"grant" });
		$.data($("#map")[0], "latlng", null);

		$("#yearSlider").slider({
			range: true,
			values: [2000, 2009],
			min: 2000, max: 2009,
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

