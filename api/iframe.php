<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<?php
	$urls = preg_split("/[|]/", $_GET['q']);


?>
<html>
<style>
	body {
		font-family: Tahoma;
		font-size: 8pt;
		padding: 0px;
	}
	div { 
		height: 40px; 
		margin-bottom: 10px;
		position: relative;
	}
	div img {
		position: relative;
		height: 20px;
		padding: 5px;
		margin: 5px;
     -moz-border-radius: 8px;
  -webkit-border-radius: 8px;
          border-radius: 8px;
     -moz-box-shadow: 2px 2px 2px #666666; 
  -webkit-box-shadow: 2px 2px 2px #666666; 
          box-shadow: 2px 2px 2px #666666; 
     -moz-transition: all 0.5s ease-in; 
  -webkit-transition: all 0.5s ease-in; 
          transition: all 0.5s ease-in; 
	border: 1px solid #ccc;

	}
	div img:hover {
		left:-2px;
		top:-2px;
		cursor: pointer;
     -moz-box-shadow: 5px 5px 5px #666666; 
  -webkit-box-shadow: 5px 5px 5px #666666; 
          box-shadow: 5px 5px 5px #666666; 
	}
	div a {
		position: absolute;
		color: #0000ff;
		right: 0px;
		top: 20%;
	}
		
</style>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.js"></script>
<script>
	$(document).ready(function() {	
		maxH = $(document).height();
		header = $("<div></div>");
		<? 
			foreach($urls as $x) {
				$img = "";
				if (strpos($x, "projectreporter.nih.gov") !== false)
					$img = "http://nih.gov/favicon.ico";
				elseif (strpos($x, "nsf.gov") !== false)
					$img = "http://nsf.gov/favicon.ico";
				elseif (strpos($x, "research.gov") !== false)
					$img = "http://www.research.gov/images/Rgoc-Logo.jpg";
				elseif (strpos($x, "usaspending.gov") !== false)
					$img = "http://usaspending.gov/sites/all/themes/usaspendingv2/images/homelogo.gif";
				elseif (strpos($x, "uspto.gov") !== false)
					$img = "http://www.uspto.gov/favicon.ico";
				elseif (strpos($x, "google.com/patents") !== false)
					$img = "http://www.google.com/favicon.ico";
				elseif (strpos($x, "/pubmed/") !== false)
					$img = "http://www.ncbi.nlm.nih.gov/favicon.ico";

				if ($img != "") { ?>
					header.append("<img src='<?=$img?>' href='<?=$x?>' />");
			<?	} 
			}
		?>
		header.append("<a id='new' href='<?=$urls[0]?>' target='_blank'>Open in new window</a>");
		$("body").append(header);
		$("body").append($("<iframe></iframe>").attr({ width: "100%", height: (maxH-80)+"px", src: "<?=$urls[0]?>", frameborder: 0}));
		$("img").live("click", function(event) {
			$("iframe").attr("src", $(this).attr("href"));
			$("#new").attr("href", $(this).attr("href"));
		});
	});
</script>

<body height="100%"></body>
</html>
