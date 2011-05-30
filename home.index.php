<div class="grid_4">
	<div class="ui-widget-content ui-corner-all sidebar">
          <h1>Highlights</h1>
          <ul>
          <li><a href="http://www.whitehouse.gov/sites/default/files/microsites/ostp/ostp-open-gov-plan-v12.pdf" target="_blank">Office of Science and Technology Policy  of the White House - Orszag/Holdren memos to science agencies</a></li>
          <li><a href="about.html#datasources">Data  Sources (and Assumptions)</a></li>
          <li><a href="about.html#downloading">Downloading Data and Tool Tips &ndash; making the most of this site</a></li>
          </ul>
	</div>
</div>

<div class="grid_11 alpha omega">
	<div style="margin-top: 10px; padding:0 60px 10px 10px;">
                	<a name="1"></a>
                    <h1>The R&amp;D Dashboard</h1>
                    <p class="close-above">In response to the <a href="">eGov Act of 2002 Section 207</a>, the R&amp;D Dashboard beta web site provides  an initial look at U.S. Federal Investments in Science and Research from two agencies; the National Institutes of Health (NIH) and the National Science Foundation (NSF) from years 2000-2009. The R&amp;D Dashboard will expand in a future iteration to include ALL federal research and development spending and expanded information on outputs. </p>
                    
                    <h1>What's available</h1>
                    <p class="close-above">
                    The information presented reports data on the grants issued by the Federal government to research institutions (“investments”), and the publications and patent activity produced by researchers funded by those investments (“outputs”). The site reports “where” investments have been made at the state, congressional district and research institution.  In addition, the site provides information on “what” investments have been made by providing the user the ability to select topic areas at the same geographic levels of detail.
					</p>
                    
                    <h1>This is a Beta Site</h1>
                    <p class="close-above">The R&amp;D Dashboard is a beta site and feedback is welcome.  Please direct comments or questions to our <a href="contact.html">contact page</a>.</p>

		<? if (isIE()) { ?>
			<h1>Microsoft Explorer</h1>
            <p class="close-above">This website works best with browsers such as Google Chrome or Firefox. Functionality may be slowed with Microsoft Internet Explorer.</p>
		<? } ?>
					<p class="caption">Sample View - Search of Ohio Research Institutions and Grant activity by Institution</p>
                    <img src="img/grants-capture-sized.jpg" class="figureimage" />
	</div>
</div>


<script src="js/map_framework_f.js" type="text/javascript"></script>
<script type="text/javascript" charset="utf-8">
	google.load("jqueryui", "1.8.4");
	$(document).ready(function() {	
		<? require_once("js/map.js"); ?>
	});	
</script>

