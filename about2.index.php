<div class="grid_4">
  <div class="ui-widget-content ui-corner-all sidebar">
    <h1>About</h1>
    <ul>
      <li><a href="#prototype">The Prototype</a></li>
      <li><a href="#how">How to Use</a></li>
      <li><a href="#datasources">Data Sources</a></li>
      <li><a href="#approaches">Approaches</a></li>
    </ul>
  </div>
  
  <img src="img/howto_grants.jpg" class="figureimage" />
  <img src="img/howto_patents.jpg" class="figureimage" />
  <img src="img/howto_top_research.jpg" class="figureimage" />
  <img src="img/howto_top_classifications.jpg" class="figureimage" />
  <img src="img/howto_csv.jpg" class="figureimage" />
  
</div>

<div class="grid_11 alpha omega">
  <div style="margin-top: 10px; padding:0px 60px 10px 10px;">
  <a name="protoype"></a>
  <h1>About the Prototype</h1>
    <p>The R&amp;D Dashboard prototypes a grant and award reporting system
      which presents information derived from publicly reported federal agency data
      from the National Science Foundation and the National Institutes of
      Health.  A next stage production system would
      be fully integrated with all available federal agency databases; this prototype
      reports data only from two agencies from 2001 to 2009. A full system would also
      include much broader outputs than simply publications, patents and patent
      applications; this prototype used currently available data.</p>
      
<a name="how"></a>    
<h1>How to Use the Prototype</h1>
<h2>Information Views</h2>
<p>All information views are provided to allow for drill down within data and combinations of data based on user preference. In the beta version a hover over each state produces a summation of data available to be reviewed by tab sub-element. </p>

<p>The information types included in this beta release version are: </p>
<ul>
    <li>NSF and NIH Grants in the <a href="investment.html">Investments</a> tab</li>
    <li>Publications found under the  <a href="pub.html">Outputs</a> tab</li>
    <li>Patents found under the  <a href="patent.html">Outputs</a> tab</li>
    <li>Patent Applications found under the  <a href="application.html">Outputs</a> tab</li>
</ul>

<p>Most users may click on the map to view data for any state.  Hawaii and Alaska can viewed by zooming out.  However, more refined results may be found by using the form fields on the left side of the map. </p>
<ul>
<li>Alternatively, the first dropdown can select states, and also Congressional Districts. An individual state or Congressional District must be selected before data is displayed.</li>
<li>The second dropdown restrains the data to activities by NIH, NSF, or both agencies. </li>
<li>The years slider restrains the data to any time period within the specified range.  
The data in this prototype cover activities from the above sources from FY 2000 through FY 2009.</li>
<li>For Grants, the Amount slider restrains the data to the grant dollar amount range specified.</li> 
<li>For Patents, the Citation slider allows users to select the applicable years of patent approval or submission, and select display of data from 0-2 degrees of separation from the primary patent submission. Degrees of separation entail any reference or citation within the document which points to another related patent. This feature is intended to help enrich or provide linkages to related patents if the data resides within the patent data descriptions. </li>
</ul>

<h2>Refining Output Data</h2>
<p>A tooltip appears when hovering over the state and city hotspots.  When clicking on a city or state, the data in the table below the map automatically.  Also, the data in the Top Research Institutions and Top Topics update.</p>
<ul>
<li>When clicking on the Top Research Institutions picklist, the data can be further refined to that institution.  The data table will update automatically to reflect this choice. Multiple institutions  can be selected by simply clicking on them.  Click again to deselect.</li>
<li> Click on Top Topics to open the pick list. (In Patents it is called Top Classifications). When clicking on the Top Topics picklist, the data can be further refined to that topic aggregation.  The data table will update automatically to reflect this choice. Multiple topics can be selected by simply clicking on them.  Click again to deselect.<br />
The topics are naturally occurring based on frequency of terms and is based on a modeling algorithm that entails frequency of terms associated with the article topic description used. See the description of the <a href="topic_modeling.html">topic modeling approach.</a></li>
</ul>

<h2>Data Table Views and data download</h2>
<p>For each information drill down option selected and displayed via the map, a data table located below the map is also displayed corresponding to the map results. </p>
<ul>
<li>Within the table users may sort on the available columns. </li>
<li>The Search box further restrains the data to the entered terms.</li>
<li>Note the horizontal scrollbar at the bottom of the table</li>
</ul>
<p>The table data can be downloaded by the user in acomma separated variable (csv) format for custom reporting needs.</p>    
      
  <a name="datasources"></a>     
  <h1>Data Sources</h1>
      <p class="close-above">All data available on the R&D Dashboard beta web site is publicly available data from the following U.S. government agencies. </p>
    <ul>
      <li>National Science Foundation award data at <a href="http://www.research.gov/" target="_blank">Research.gov</a></li>
      <li>National Institutes of Health award data at <a href="http://www.report.nih.gov" target="_blank">RePORT</a> and <a href="http://projectreporter.nih.gov/exporter/" target="_blank">ExPORTER</a></li>
      <li>National Institutes of Health publication data at <a href="http://www.ncbi.nlm.nih.gov/pubmed" target="_blank">Pubmed</a></li>
      <li>United States Patent and Trademark Office Patent -- <a href="http://www.uspto.gov/patents/process/search/index.jsp#heading-1" target="_blank">Patents approved and Patents applied for</a></li>
    </ul>
<p>For each record displayed, a hyper link to the original agency data source is made available to the user if further detail on the information presented is sought. The location of the link is within the data table supplied on the results page located below the map results.</p>    
    
    
    
<a name="approaches"></a>
  <h1>Approaches</h1>
      <p class="close-above">The website introduces several innovations and approaches
      applied to the data presented; we provide more information on the approaches
      used, provide the raw data files used to populate this prototype and offer additional clarification if requested. </p>
    <ul>
      <li>Geographic detail </li>
        <ul>
          <li><a href="http://www.census.gov/geo/www/cob/bdy_files.html" target="_blank">Census Boundary Files</a></li>
        </ul>
      <li>Representation of scientific fields through topic modeling
        <ul>
          <li><a href="topic_modeling.html">Topic Modeling Approach</a></li>
          <li><a href="assets/newman-uci-topicmodeler.ppt" target="_blank">Topic Modeling Powerpoint</a></li>
        </ul>
      </li>
      <li>Links to patent and firm data
        <ul>
          <li><a href="assets/disambiguation_of_uspto.doc" target="_blank">Patent Data Disambiguation Approach</a></li>
          <li><a href="http://www.iq.harvard.edu/programs/patent_collaboration_network" target="_blank">Harvard  Business School Patent Collaboration Network</a></li>
        </ul>
      </li>
      <li>Links to agency funding data (2000 - 2009)
        <ul>
          <li><a href="/files/NIH%202000-2009.zip" target="_blank">National Institutes of Health</a></li>
          <li><a href="/files/NSF%202000-2009.zip" target="_blank">National Science Foundation</a></li>
        </ul>
      </li>
    </ul>
    
    
    
    
  </div>
</div>
<script src="js/map_framework_f.js" type="text/javascript"></script>
<script type="text/javascript" charset="utf-8">
	google.load("jqueryui", "1.8.4");
	$(document).ready(function() {	
		<? require_once("js/map.js"); ?>
	});	
</script>
