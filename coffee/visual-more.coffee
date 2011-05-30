###
// Here's my data model
var viewModel = { 
    firstName : ko.observable("Planet"),
    lastName : ko.observable("Earth")
};
viewModel.fullName = ko.dependentObservable(function () {
    // Knockout tracks dependencies automatically. It knows that fullName depends on firstName and lastName, because these get called when evaluating fullName.
    return viewModel.firstName() + " " + viewModel.lastName(); 
});
###


class legendModel
	OrgList:   ko.observableArray([])
	LabelList: ko.observableArray([])

lMod = new legendModel 
ko.applyBindings lMod

###-----------------
	KNOCKOUT.JS
###
###
class ViewModel
	firstName: ko.observable()
	lastName:  ko.observable()
	doit: -> 
		cssColor=prompt("FD")
		@firstName(cssColor)
		#alert(@firstName())

vM = new ViewModel
vM.fullName = ko.dependentObservable -> "#{vM.firstName()} #{vM.lastName()}"
vM.firstName("ron").lastName('lai')
ko.applyBindings vM

#viewModel.doit()
###





###-----------------
	GLOBAL VARIABLES
###


# UI - ToolTip
mapToolTip = (html="") ->
	if html is ""
		$("#mapToolTip").css "display", "none"
	else
		if mousey?
			$('#mapToolTip').html(html).css('top', mousey.pageY+25).css('left', mousey.pageX-5).css('display', 'block');


# side attributes in coffeescript.  yay!
sideAttrs = (type="") ->
	attrAdd = (i) -> 
		for type in _.keys attr
			attr[type].add dataB.marks[type][i]

	latlng = $.data $("#map")[0], "latlng"
	switch type
		when "" 	 then attr = { "Label": new dict, "Org": new dict }
		when "Label" then attr = { "Org":   new dict }
		when "Org"	 then attr = { "Label": new dict }

	allMarks = true
	if latlng?
		if latlng.markers.length > 0
			allMarks = false
	
	if allMarks
		$(dataB.marks.cnt).each (i) -> attrAdd(i)
	else
		$(latlng['markers']).each (k, i) -> attrAdd(i)

	for type in _.keys attr
		template = []
		if not params[type]?
			params[type] = []
			
		if params[type].length is 0
			attr[type].sort()

			for i in [0.._.min([99, attr[type].ranked.length-1])]
				key = attr[type].ranked[i][0]
				if dataB.colors["#{key}|#{type}"]? and key isnt ""
					label = dataB.colors["#{key}|#{type}"][1]
					template.push { 
						type:type
						key:key
						label: if key is "" then "Unattributed" else label
						label_ex: (if params.table in ["pat", "app"] then " (USPTO Class# #{key})" else "") if type is "Label" and key isnt ""
					}
			$("#sect#{type}").html $("#navclicky").tmpl(template)			



#jQuery events
$("div.list a").live "click", (event) ->
	$(this).toggleClass "sel"
	type = $(this).attr("type")
	keys = $.data $("#map")[0], "params"
	keys["Org"] = []
	keys["Label"] = []
	$.each $(this).parent().children(".sel"), (i, k) -> keys[type].push $(this).attr("key")
	setVal {}
	event.preventDefault()
