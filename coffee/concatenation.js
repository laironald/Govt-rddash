/*
// Here's my data model
var viewModel = {
    firstName : ko.observable("Planet"),
    lastName : ko.observable("Earth")
};
viewModel.fullName = ko.dependentObservable(function () {
    // Knockout tracks dependencies automatically. It knows that fullName depends on firstName and lastName, because these get called when evaluating fullName.
    return viewModel.firstName() + " " + viewModel.lastName();
});
*/var ViewModel, mapToolTip, sideAttrs, viewModel;
ViewModel = (function() {
  function ViewModel() {}
  ViewModel.prototype.firstName = ko.observable();
  ViewModel.prototype.lastName = ko.observable();
  ViewModel.prototype.doit = function() {
    var cssColor;
    cssColor = prompt("FD");
    return this.firstName(cssColor);
  };
  return ViewModel;
})();
viewModel = new ViewModel;
viewModel.fullName = ko.dependentObservable(function() {
  return "" + (viewModel.firstName()) + " " + (viewModel.lastName());
});
viewModel.firstName("ron").lastName('lai');
ko.applyBindings(viewModel);
/*-----------------
	GLOBAL VARIABLES
*/
ich.addTemplate("navclicky", '{{# items }}\n	<div type="{{ type }}" key="{{ key }}">\n		<a href="#" style="text-decoration:none; color:#fff;" class="{{ class }}">{{ label }} <br/> {{{ label_ex }}}</a>\n	</div>\n{{/ items }}');
mapToolTip = function(html) {
  if (html == null) {
    html = "";
  }
  if (html === "") {
    return $("#mapToolTip").css("display", "none");
  } else {
    if (typeof mousey != "undefined" && mousey !== null) {
      return $('#mapToolTip').html(html).css('top', mousey.pageY + 25).css('left', mousey.pageX - 5).css('display', 'block');
    }
  }
};
sideAttrs = function(type) {
  var allMarks, attr, attrAdd, classN, i, key, label, latlng, newparam, params, template, _i, _len, _ref, _ref2, _ref3, _results;
  if (type == null) {
    type = "";
  }
  attrAdd = function(i) {
    var type, _i, _len, _ref, _results;
    _ref = _.keys(attr);
    _results = [];
    for (_i = 0, _len = _ref.length; _i < _len; _i++) {
      type = _ref[_i];
      _results.push(attr[type].add(dataB.marks[type][i]));
    }
    return _results;
  };
  latlng = $.data($("#map")[0], "latlng");
  params = $.data($("#map")[0], "params");
  switch (type) {
    case "":
      attr = {
        "Label": new dict,
        "Org": new dict
      };
      break;
    case "Label":
      attr = {
        "Org": new dict
      };
      break;
    case "Org":
      attr = {
        "Label": new dict
      };
  }
  allMarks = true;
  if (latlng != null) {
    if (latlng.markers.length > 0) {
      allMarks = false;
    }
  }
  if (allMarks) {
    $(dataB.marks.cnt).each(function(i) {
      return attrAdd(i);
    });
  } else {
    $(latlng['markers']).each(function(k, i) {
      return attrAdd(i);
    });
  }
  _ref = _.keys(attr);
  _results = [];
  for (_i = 0, _len = _ref.length; _i < _len; _i++) {
    type = _ref[_i];
    template = {
      items: []
    };
    attr[type].sort();
    newparam = [];
    $("#sect" + type + " div").detach();
    for (i = 0, _ref2 = _.min([99, attr[type].ranked.length - 1]); (0 <= _ref2 ? i <= _ref2 : i >= _ref2); (0 <= _ref2 ? i += 1 : i -= 1)) {
      classN = "";
      if (-1 !== $.inArray(attr[type].ranked[i][0], params[type])) {
        classN = "selected";
        newparam.push(attr[type].ranked[i][0]);
      }
      key = attr[type].ranked[i][0];
      if (dataB.colors["" + key + "|" + type] != null) {
        label = dataB.colors["" + key + "|" + type][1];
        template.items.push({
          type: type,
          key: key,
          "class": classN,
          label: key === "" ? "Unattributed" : label,
          label_ex: type === "Label" && key !== "" ? ((_ref3 = params.table) === "pat" || _ref3 === "app" ? " (USPTO Class# " + key + ")" : "(Topic # " + key + ")") : void 0
        });
      }
    }
    if (params[type] != null) {
      params[type] = newparam;
    }
    _results.push($("#sect" + type).html(ich.navclicky(template)));
  }
  return _results;
};
/*
	}
	$("#sectOrg div").live("click", function(event) {
		$(this).toggleClass("selected");
		sectVal("Org", $(this).attr("key"), $(this).hasClass("selected"));
		event.preventDefault();
	});
	$("#sectLabel div").live("click", function(event) {
		$(this).toggleClass("selected");
		sectVal("Label", $(this).attr("key"), $(this).hasClass("selected"));
		event.preventDefault();
	});
	function sectVal(type, value, selected) {
		params = $.data($("#map")[0], "params");
		if (params[type] == null)
			params[type] = [];
		if (selected)
			params[type].push(value);
		else
			params[type].splice($.inArray(value, params[type]), 1);
		if (params[type].length == 0)
			params[type] = null;
		setVal({});
	}
*/
/*
// Here's my data model
var viewModel = {
    firstName : ko.observable("Planet"),
    lastName : ko.observable("Earth")
};
viewModel.fullName = ko.dependentObservable(function () {
    // Knockout tracks dependencies automatically. It knows that fullName depends on firstName and lastName, because these get called when evaluating fullName.
    return viewModel.firstName() + " " + viewModel.lastName();
});
*/
ViewModel = (function() {
  function ViewModel() {}
  ViewModel.prototype.firstName = ko.observable();
  ViewModel.prototype.lastName = ko.observable();
  ViewModel.prototype.doit = function() {
    var cssColor;
    cssColor = prompt("FD");
    return this.firstName(cssColor);
  };
  return ViewModel;
})();
viewModel = new ViewModel;
viewModel.fullName = ko.dependentObservable(function() {
  return "" + (viewModel.firstName()) + " " + (viewModel.lastName());
});
viewModel.firstName("ron").lastName('lai');
ko.applyBindings(viewModel);
/*-----------------
	GLOBAL VARIABLES
*/
ich.addTemplate("navclicky", '{{# items }}\n	<div type="{{ type }}" key="{{ key }}">\n		<a href="#" style="text-decoration:none; color:#fff;" class="{{ class }}">{{ label }} <br/> {{{ label_ex }}}</a>\n	</div>\n{{/ items }}');
mapToolTip = function(html) {
  if (html == null) {
    html = "";
  }
  if (html === "") {
    return $("#mapToolTip").css("display", "none");
  } else {
    if (typeof mousey != "undefined" && mousey !== null) {
      return $('#mapToolTip').html(html).css('top', mousey.pageY + 25).css('left', mousey.pageX - 5).css('display', 'block');
    }
  }
};
sideAttrs = function(type) {
  var allMarks, attr, attrAdd, classN, i, key, label, latlng, newparam, params, template, _i, _len, _ref, _ref2, _ref3, _results;
  if (type == null) {
    type = "";
  }
  attrAdd = function(i) {
    var type, _i, _len, _ref, _results;
    _ref = _.keys(attr);
    _results = [];
    for (_i = 0, _len = _ref.length; _i < _len; _i++) {
      type = _ref[_i];
      _results.push(attr[type].add(dataB.marks[type][i]));
    }
    return _results;
  };
  latlng = $.data($("#map")[0], "latlng");
  params = $.data($("#map")[0], "params");
  switch (type) {
    case "":
      attr = {
        "Label": new dict,
        "Org": new dict
      };
      break;
    case "Label":
      attr = {
        "Org": new dict
      };
      break;
    case "Org":
      attr = {
        "Label": new dict
      };
  }
  allMarks = true;
  if (latlng != null) {
    if (latlng.markers.length > 0) {
      allMarks = false;
    }
  }
  if (allMarks) {
    $(dataB.marks.cnt).each(function(i) {
      return attrAdd(i);
    });
  } else {
    $(latlng['markers']).each(function(k, i) {
      return attrAdd(i);
    });
  }
  _ref = _.keys(attr);
  _results = [];
  for (_i = 0, _len = _ref.length; _i < _len; _i++) {
    type = _ref[_i];
    template = {
      items: []
    };
    attr[type].sort();
    newparam = [];
    $("#sect" + type + " div").detach();
    for (i = 0, _ref2 = _.min([99, attr[type].ranked.length - 1]); (0 <= _ref2 ? i <= _ref2 : i >= _ref2); (0 <= _ref2 ? i += 1 : i -= 1)) {
      classN = "";
      if (-1 !== $.inArray(attr[type].ranked[i][0], params[type])) {
        classN = "selected";
        newparam.push(attr[type].ranked[i][0]);
      }
      key = attr[type].ranked[i][0];
      if (dataB.colors["" + key + "|" + type] != null) {
        label = dataB.colors["" + key + "|" + type][1];
        template.items.push({
          type: type,
          key: key,
          "class": classN,
          label: key === "" ? "Unattributed" : label,
          label_ex: type === "Label" && key !== "" ? ((_ref3 = params.table) === "pat" || _ref3 === "app" ? " (USPTO Class# " + key + ")" : "(Topic # " + key + ")") : void 0
        });
      }
    }
    if (params[type] != null) {
      params[type] = newparam;
    }
    _results.push($("#sect" + type).html(ich.navclicky(template)));
  }
  return _results;
};
/*
	}
	$("#sectOrg div").live("click", function(event) {
		$(this).toggleClass("selected");
		sectVal("Org", $(this).attr("key"), $(this).hasClass("selected"));
		event.preventDefault();
	});
	$("#sectLabel div").live("click", function(event) {
		$(this).toggleClass("selected");
		sectVal("Label", $(this).attr("key"), $(this).hasClass("selected"));
		event.preventDefault();
	});
	function sectVal(type, value, selected) {
		params = $.data($("#map")[0], "params");
		if (params[type] == null)
			params[type] = [];
		if (selected)
			params[type].push(value);
		else
			params[type].splice($.inArray(value, params[type]), 1);
		if (params[type].length == 0)
			params[type] = null;
		setVal({});
	}
*/