$("#agency").change(function() { hoverStats = null; setVal({}); });
$("#polyselect").change(function() { 
	if ($("#polyselect").val() == "") {
		setVal({ CD:null, State:null, Org:null, Label:null }); 
		map.setZoom(4); 
		map.panTo(new G.LatLng(38.115320836, -96.6304735));		
	} else if ($("#polyselect").val().split("-").length == 2) {
		polyS = $("#polyselect").val().split("-");
		setVal({ CD:parseInt(polyS[1]), State:polyS[0], Org:null, Label:null }); 
	} else
		setVal({ CD:0, State:$("#polyselect").val(), Org:null, Label:null }); 
});
$("#tabletabs").tabs();
$("#topTbl").accordion({ fillSpace: true });

// Mousemovers
$("#map").mousemove(function (evt) { mousey = evt; });
$("a.data").live("mouseover", function(event) {
	$("a.data").fancybox({
		'width'				: 800,
		'height'			: "100%",
		'autoScale'			: false,
		'transitionIn'		: 'none',
		'transitionOut'		: 'none',
		'type'				: 'iframe'
	});
	event.preventDefault();
});
//ctrl press

$(document).keyup(function(e) { if (e.which==17) ctrl=false; })
$(document).keydown(function(e) { if (e.which==17) ctrl=true; })

function slideChg(id, key, ui, update) {
	$(id).html((ui.values.length==1)?ui.values[0]:((ui.values[0]==ui.values[1])?ui.values[0]:(ui.values[0] + "-" + ui.values[1])));
	hoverStats = null;
	if (update) {
		var attrb = new Object();
		attrb[key] = $(id).html();
		setVal(attrb);
	}
}
//set jQuery UI
function navbar() {
    $("ul.sub-navs").hide();
    $(".navs li").hover(
        function(){
            subtab = $(this).children("ul"); 
            thisoffset = $(this).position();
            subtab.css({"left":thisoffset.left ,"top":thisoffset.top +42});
            subtab.show();
        },
        function(){
            subtab.hide();
	    });
    $(".navs li.down a").focus(
        function(){
            subtab = $(".navs li.down").children("ul"); 
            thisoffset = $(".navs li.down").position();
            subtab.css({"left":thisoffset.left ,"top":thisoffset.top +42});
            subtab.show();
        });
    $(".navs li.down a").blur(
        function(){
            subtab.hide();
	    });
    $("ul.sub-navs").mouseleave(function(e){
        $(this).hide();
    });
};
navbar();

/*
$(document).scroll( $.throttle(250, function() {
	$("#map-inputs").css("position", ($(document).scrollTop() > 140)?"fixed": "absolute")
					.css("top", 	 ($(document).scrollTop() > 140)?"20px": "0px")
					.css("left", "auto");//	$("#map-inputs").css("top", _.max([0, $(window).scrollTop() - 140]) + "px");
}));
*/
$(document).scroll( function() {
	$("#map-inputs").css("top", _.max([0, $(window).scrollTop() - 140]) + "px");
});

/*
$(".tooltip").qtip({ 
	content: { text: false },
	style: { tip: { corner: 'leftMiddle', size: {x:10, y:8} }, name: 'dark' },
	position: { corner: { target: 'rightMiddle', tooltip: 'leftMiddle' } },
	show: { solo: true },
	hide: 'unfocus'
});
*/
$(".tooltip").tipTip({ activation:"hover", delay: 200, defaultPosition: "right", maxWidth: "auto", edgeOffset: 10 });

