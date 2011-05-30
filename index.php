<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<?
	include_once 'config/config.php';
	include_once 'framework/framework.php';

	$section = $_GET['section'];
	if ($section=="")
		$section = "home"; 

	if (!file_exists($section.".index.php"))
		$section = "404";

	switch($section) {
		case 'home': 		$title = "Home"; break;
		case 'investment': 	$title = "Investments"; break;
		case 'patent': 		$title = "Patents"; break;
		case 'application': $title = "Patent Applications"; break;
		case 'pub': 		$title = "Publications"; break;
		case '404': 		$title = "404 Error"; break;
	}

	$production = false;

?>
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
		<meta http-equiv="x-ua-compatible" content="IE=8" />
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<title>R+D Dashboard - <?=$title?></title>

		<link rel="stylesheet" type="text/css" href="css/boiler.css" media="all" />
		<link rel="stylesheet" type="text/css" href="css/reset-fonts-base-min.css" media="screen" />
		<link rel="stylesheet" type="text/css" href="css/960.css" media="screen" />
		<link rel="stylesheet" type="text/css" href="css/jqtheme/jquery-ui-1.8.6.custom.css" media="screen" />
		<style type="text/css" title="currentStyle">
			@import "css/demo_page.css";
			@import "css/demo_table_jui.css";
		</style>    
		<link rel="stylesheet" type="text/css" href="css/skin.css" media="screen" />
		<link rel="stylesheet" type="text/css" href="css/themes/base/jquery.ui.selectmenu.css" />
		<link rel="stylesheet" type="text/css" href="css/map.style.simple_f.css" />
		<link rel="stylesheet" type="text/css" href="css/tiptip/tipTip.css" media="screen" />    		    
		<link rel="stylesheet" type="text/css" href="js/jq/fancybox-1.3.4/fancybox/jquery.fancybox-1.3.4.css" media="screen" />    		    

		<script type="text/javascript" src="http://www.google.com/jsapi"></script>
		<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.4/jquery.min.js"></script>
		<script type="text/javascript" src="js/knockout-1.1.1.js"></script>	
		
		<script type="text/x-jquery-tmpl" id="navclicky">
			<a type="${ type }" key="${ key }" href="#" style="text-decoration:none; color:#fff;">${ label } <br/> ${ label_ex }</a>
		</script>
    </head>
    
	<body class="jqtheme">
		<div id="status"></div>
		<div class="group" id="container">
			<div style="border-bottom: #444 1px solid;">
				<div style="background:url(img/dash_base.gif); height:130px; border-bottom:#111 2px solid;">
					<div style="position: absolute; top: 52px; left: 200px; color:#ccc; font-family: arial; font-size: 9px;">BETA</div>
					<div id="tabs">
						<ul class="navs">
							<li <?=($section=="home")?"class='active'":""?> id=""><a href="home.html" >Home</a></li>				
							<li <?=($section=="investment")?"class='active'":""?> id=""><a href="investment.html">Investments</a></li>				
							<li class='down <?=($section=="patent" or $section=="application" or $section=="pub")?"active":""?>' id=""><a href="pub.html">Outputs</a>
								<ul class="sub-navs" style="width:200px; display:none;">
									<li><a href="pub.html">Publications</a></li>
									<li><a href="patent.html">Patents</a></li>
									<li><a href="application.html">Patent Applications</a></li>
								</ul>
							</li>      
						    <li <?=($section=="about")?"class='active'":""?> id=""><a href="about.html">About</a></li>		
						    <li <?=($section=="contact")?"class='active'":""?> id=""><a href="contact.html">Contact</a></li>	
						</ul>		
					</div>
				</div>
			</div>
			<div class="content-container group" style="min-height: 600px">
				<? require_once(strtolower($section).'.index.php'); ?>
			</div>
		</div>
		<div id="mousemove"></div><div id="mapToolTip"></div>

	<script type="text/javascript" src="http://ajax.microsoft.com/ajax/jquery.templates/beta1/jquery.tmpl.min.js"></script>

	<? if ($_SERVER["HTTP_HOST"]=="readidata.nitrd.gov") { ?>
		<script>
			//http://closure-compiler.appspot.com/home
		
			// ==ClosureCompiler==
			// @output_file_name closurer.js
			// @compilation_level SIMPLE_OPTIMIZATIONS
			// @code_url http://readidata.nitrd.gov/rddash/js/jq/tiptip/jquery.tipTip.minified.js
			// @code_url http://readidata.nitrd.gov/rddash/js/underscore-min.js
			// @code_url http://readidata.nitrd.gov/rddash/js/ui/jquery.ui.core.js
			// @code_url http://readidata.nitrd.gov/rddash/js/ui/jquery.ui.widget.js
			// @code_url http://readidata.nitrd.gov/rddash/js/ui/jquery.ui.position.js
			// @code_url http://readidata.nitrd.gov/rddash/js/ui/jquery.ui.selectmenu.js
			// @code_url http://readidata.nitrd.gov/rddash/js/jquery.ba-throttle-debounce.min.js
			// @code_url http://readidata.nitrd.gov/rddash/js/jquery.dataTables.js
			// @code_url http://readidata.nitrd.gov/rddash/js/visual.simple_f.js
			// @code_url http://readidata.nitrd.gov/rddash/js/visual-more.js
			// @code_url http://readidata.nitrd.gov/rddash/js/markerclusterer.simple_f.js
			// @code_url http://readidata.nitrd.gov/rddash/js/jq/fancybox-1.3.4/fancybox/jquery.fancybox-1.3.4.js
			// ==/ClosureCompiler==
		</script>
		<script type="text/javascript" src="js/jq/tiptip/jquery.tipTip.minified.js"></script>	
		<script type="text/javascript" src="js/underscore-min.js"></script>
		<script type="text/javascript" src="js/ui/jquery.ui.core.js"></script>
		<script type="text/javascript" src="js/ui/jquery.ui.widget.js"></script>
		<script type="text/javascript" src="js/ui/jquery.ui.position.js"></script>
		<script type="text/javascript" src="js/ui/jquery.ui.selectmenu.js"></script>
		<script type="text/javascript" src="js/jquery.ba-throttle-debounce.min.js"></script>
		<script type="text/javascript" src="js/jquery.dataTables.js"></script>	
		<script type="text/javascript" src="js/visual.simple_f.js"></script>	
		<script type="text/javascript" src="js/visual-more.js"></script>	
		<script type="text/javascript" src="js/markerclusterer.simple_f.js"></script>	
		<script type="text/javascript" src="js/jq/fancybox-1.3.4/fancybox/jquery.fancybox-1.3.4.js"></script>	
	<? } else { ?>
		<script type="text/javascript" src="js/closurer.js"></script>
	<? } ?>
		
	<script>
	<? if ($_SERVER["HTTP_HOST"]=="readidata.nitrd.gov") { ?>
		var _gaq = [['_setAccount', 'UA-20352348-1'], ['_trackPageview']];
	<? } else { ?>
		var _gaq = [['_setAccount', 'UA-21095974-1'], ['_trackPageview']];
	<? } ?>
		(function(d, t) {
		var g = d.createElement(t),
			s = d.getElementsByTagName(t)[0];
		g.async = true;
		g.src = ('https:' == location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
		s.parentNode.insertBefore(g, s);
		})(document, 'script');
	</script>

  </body>
</html>
