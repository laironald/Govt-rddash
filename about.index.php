<div class="grid_4">
  <div class="ui-widget-content ui-corner-all sidebar">
    <h1>About</h1>
    <ul>
      <li><a href="#prototype">The Prototype</a></li>
      <li><a href="#datasources">Data Sources</a></li>
    </ul>
  </div>
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
      reports data only from two agencies from 2001 to 2010. A full system would also
      include much broader outputs than simply publications, patents and patent
      applications; this prototype used currently available data.</p>
      
  <a name="datasources"></a>     
  <h1>Data Sources</h1>
      <p class="close-above">All data available on the R&D Dashboard beta web site is publicly available data from the following U.S. government agencies. </p>
    <ul>
      <li>National Science Foundation award data at <a href="http://www.research.gov/" target="_blank">Research.gov</a></li>
      <li>National Institutes of Health award data at <a href="http://www.report.nih.gov" target="_blank">RePORT</a> and <a href="http://projectreporter.nih.gov/exporter/" target="_blank">ExPORTER</a></li>
      <li>National Institutes of Health publication data at <a href="http://www.ncbi.nlm.nih.gov/pubmed" target="_blank">Pubmed</a></li>
      <li>United States Patent and Trademark Office Patent -- <a href="http://www.uspto.gov/patents/process/search/index.jsp#heading-1" target="_blank">Patents approved and Patents applied for</a></li>
    </ul>
    <ul>
      <li>Geographic detail </li>
        <ul>
          <li><a target="_blank" href="http://www.census.gov/geo/www/cob/bdy_files.html">Census Boundary Files</a></li>
        </ul>
      <li>Representation of scientific fields through topic modeling
        <ul>
          <li><a href="topic_modeling.html">Topic Modeling Approach</a></li>
          <li><a target="_blank" href="assets/newman-uci-topicmodeler.ppt">Topic Modeling Powerpoint</a></li>
        </ul>
      </li>
      <li>Links to patent and firm data
        <ul>
          <li><a target="_blank" href="assets/disambiguation_of_uspto.doc">Patent Data Disambiguation Approach</a></li>
          <li><a target="_blank" href="http://www.iq.harvard.edu/programs/patent_collaboration_network">Harvard  Business School Patent Collaboration Network</a></li>
        </ul>
      </li>
      <li>Links to agency funding data (2000 - 2009)
        <ul>
          <li><a target="_blank" href="/files/NIH%202000-2009.zip">National Institutes of Health</a></li>
          <li><a target="_blank" href="/files/NSF%202000-2009.zip">National Science Foundation</a></li>
        </ul>
      </li>
      

 


      <li>The data for NIH publications were derived from the following source:
        <ul>
          <li><a href="http://projectreporter.nih.gov/exporter/ExPORTER_Catalog.aspx?sid=2&index=2">NIH ExPORTER</a></li>
        </ul>
      </li>
      <li>The data for NSF publications were derived from the following source:
        <ul>
          <li><a href="http://www.nsf.gov/publications">NSF Publications</a></li>
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
