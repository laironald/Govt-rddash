<div class="grid_4">
    &nbsp;
</div>

<div class="grid_11 alpha omega">
  <div style="margin-top: 10px; padding:0px 60px 10px 10px;">

<h1>Topic Modeling Approach</h1>
<h2>The rationale for using topic models</h2>
<p>A major goal of the website is to provide the public with information about the areas in which science investments being made. This is done by analyzing the information in NIH and NSF grant abstracts in titles using a technique known as topic modeling.  This approach provides a powerful and flexible framework for representing, summarizing and analyzing the contents of large document collections.</p>

<p>The topic model is a probabilistic model that automatically learns a set of topics (categories) that describe a collection of documents, based on the words in those documents.  Each document is considered to consist of a small number of topics, each of which is dominated by only a fraction of all possible words.  As such, the topics define a simplified representation of the documents.  A topic model of a document collection is a highly useful representation, but is not necessarily the only or even the best, representation.  </p>

<h2>Technical Details</h2>
<p>The topics were modeled based on a collection of titles and abstracts from all NSF and NIH grants from 2000-2009.  This collection included over 100,000 NSF grants, and over 600,000 NIH grants.  The topic model was computed using the widely-used Gibbs sampled topic model [2] using a threshold of 200 topics. This approach generates, for each grant, the probability that each topic is represented in the grant. 
When a topic is selected on the website, the report that is generated reflects, in descending order, the grants that have the highest probability of containing information pertaining to the selected topic.</p>

<h2>For more reading:</h2>
<p>[1] Blei, D. M., Ng, A. Y., and Jordan, M. I. (2003). Latent Dirichlet Allocation. Journal of Machine Learning Research, 3:993-1022. </p>
<p>[2] Griffiths, T., & Steyvers, M. (2004). Finding Scientific Topics. Proceedings of the National Academy of Sciences, 101 (suppl. 1), 5228-5235.</p>
<p>[3] Newman, D., A. Asuncion, P. Smyth, M. Welling. "Distributed Inference for Latent Dirichlet Allocation." Neural Information Processing Systems (NIPS), 2007. </p>
<p>[4] Newman, D Topic Modeling NSF Proposals: Results from the Enclave <a href="assets/newman-uci-topicmodeler.ppt" target="_blank">Powerpoint</a></p>

  </div>
</div>
<script src="js/map_framework_f.js" type="text/javascript"></script>
<script type="text/javascript" charset="utf-8">
	google.load("jqueryui", "1.8.4");
	$(document).ready(function() {	
		<? require_once("js/map.js"); ?>
	});	
</script>
