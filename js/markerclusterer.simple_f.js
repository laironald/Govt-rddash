//NOTE dataB -- scary...

// ==ClosureCompiler==
// @compilation_level ADVANCED_OPTIMIZATIONS
// @externs_url http://closure-compiler.googlecode.com/svn/trunk/contrib/externs/maps/google_maps_api_v3.js
// ==/ClosureCompiler==

/**
 * @name MarkerClusterer for Google Maps v3
 * @version version 1.0
 * @author Luke Mahe
 * @fileoverview
 * The library creates and manages per-zoom-level clusters for large amounts of
 * markers.
 * <br/>
 * This is a v3 implementation of the
 * <a href="http://gmaps-utility-library-dev.googlecode.com/svn/tags/markerclusterer/"
 * >v2 MarkerClusterer</a>.
 */

/**
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */


/**
 * A Marker Clusterer that clusters markers.
 *
 * @param {google.maps.Map} map The Google map to attach to.
 * @param {Array.<google.maps.Marker>} opt_markers Optional markers to add to
 *   the cluster.
 * @param {Object} opt_options support the following options:
 *     'gridSize': (number) The grid size of a cluster in pixels.
 *     'maxZoom': (number) The maximum zoom level that a marker can be part of a
 *                cluster.
 *     'zoomOnClick': (boolean) Whether the default behaviour of clicking on a
 *                    cluster is to zoom into it.
 *     'averageCenter': (boolean) Wether the center of each cluster should be
 *                      the average of all markers in the cluster.
 *     'styles': (object) An object that has style properties:
 *       'url': (string) The image url.
 *       'height': (number) The image height.
 *       'width': (number) The image width.
 *       'anchor': (Array) The anchor position of the label text.
 *       'textColor': (string) The text color.
 *       'textSize': (number) The text size.
 * @constructor
 * @extends google.maps.OverlayView
 */
 
 //RL EDIT -- ADD LINES
 
//function MarkerClusterer(map, opt_markers, opt_options) {
function MarkerClusterer(map, opt_markers, opt_lines, opt_options) { //RL EDIT
  // MarkerClusterer implements google.maps.OverlayView interface. We use the
  // extend function to extend MarkerClusterer with google.maps.OverlayView
  // because it might not always be available when the code is defined so we
  // look for it at the last possible moment. If it doesn't exist now then
  // there is no point going ahead :)
  this.extend(MarkerClusterer, google.maps.OverlayView);
  this.map_ = map;

  /**
   * @type {Array.<google.maps.Marker>}
   * @private
   */
  this.markers_ = [];
  this.lines_ = [];

  /**
   *  @type {Array.<Cluster>}
   */
  this.clusters_ = [];
  this.sizes = [53, 56, 66, 78, 90];

  /**
   * @private
   */
  this.styles_ = [];

  /**
   * @type {boolean}
   * @private
   */
  this.ready_ = false;

  var options = opt_options || {};

  /**
   * @type {number}
   * @private
   */
  this.gridSize_ = options['gridSize'] || 60;

  /**
   * @type {?number}
   * @private
   */
  this.maxZoom_ = options['maxZoom'] || null;

  this.styles_ = options['styles'] || [];

  /**
   * @type {string}
   * @private
   */
  this.imagePath_ = options['imagePath'] || this.MARKER_CLUSTER_IMAGE_PATH_;

  /**
   * @type {string}
   * @private
   */
  this.imageExtension_ = options['imageExtension'] || this.MARKER_CLUSTER_IMAGE_EXTENSION_;

  /**
   * @type {boolean}
   * @private
   */
  this.zoomOnClick_ = true;

  if (options['zoomOnClick'] != undefined) {
    this.zoomOnClick_ = options['zoomOnClick'];
  }

  /**
   * @type {boolean}
   * @private
   */
  this.averageCenter_ = false;
  if (options['averageCenter'] != undefined) {
    this.averageCenter_ = options['averageCenter'];
  }
  this.setupStyles_();
  this.setMap(map);

  /**
   * @type {number}
   * @private
   */
  this.prevZoom_ = this.map_.getZoom();

  // Add the map event listeners
  var that = this;
  google.maps.event.addListener(this.map_, 'zoom_changed', function() {
    var maxZoom = that.map_.mapTypes[that.map_.getMapTypeId()].maxZoom;
    var zoom = that.map_.getZoom();
    if (zoom < 0 || zoom > maxZoom) {
      return;
    }

    if (that.prevZoom_ != zoom) {
      that.prevZoom_ = that.map_.getZoom();
      that.resetViewport();
    }
  });

  google.maps.event.addListener(this.map_, 'idle', function() {
    that.redraw();
  });

  // Finally, add the markers
  if (opt_markers && opt_markers.length) {
    this.addMarkers(opt_markers, opt_lines, false);
  }
}


/**
 * The marker cluster image path.
 *
 * @type {string}
 * @private
 */
MarkerClusterer.prototype.MARKER_CLUSTER_IMAGE_PATH_ =
    'http://google-maps-utility-library-v3.googlecode.com/svn/trunk/markerclusterer/images/m';

/**
 * The marker cluster image path.
 *
 * @type {string}
 * @private
 */
MarkerClusterer.prototype.MARKER_CLUSTER_IMAGE_EXTENSION_ = 'png';


/**
 * Extends a objects prototype by anothers.
 *
 * @param {Object} obj1 The object to be extended.
 * @param {Object} obj2 The object to extend with.
 * @return {Object} The new extended object.
 * @ignore
 */
MarkerClusterer.prototype.extend = function(obj1, obj2) {
  return (function(object) {
    for (property in object.prototype) {
      this.prototype[property] = object.prototype[property];
    }
    return this;
  }).apply(obj1, [obj2]);
};


/**
 * Implementaion of the interface method.
 * @ignore
 */
MarkerClusterer.prototype.onAdd = function() {
  this.setReady_(true);
};


/**
 * Implementation of the interface.
 * @ignore
 */
MarkerClusterer.prototype.draw = function() {};


/**
 * Sets up the styles object.
 *
 * @private
 */
MarkerClusterer.prototype.setupStyles_ = function() {
  if (this.styles_.length) {
    return;
  }
  for (var i = 0, size; size = this.sizes[i]; i++) {
    this.styles_.push({
      url: this.imagePath_ + (i + 1) + '.' + this.imageExtension_,
      height: size,
      width: size
    });
  }
};


/**
 *  Sets the styles.
 *
 *  @param {Object} styles The style to set.
 */
MarkerClusterer.prototype.setStyles = function(styles) {
  this.styles_ = styles;
};


/**
 *  Gets the styles.
 *
 *  @return {Object} The styles object.
 */
MarkerClusterer.prototype.getStyles = function() {
  return this.styles_;
};


/**
 * Whether zoom on click is set.
 *
 * @return {boolean} True if zoomOnClick_ is set.
 */
MarkerClusterer.prototype.isZoomOnClick = function() {
  return this.zoomOnClick_;
};

/**
 * Whether average center is set.
 *
 * @return {boolean} True if averageCenter_ is set.
 */
MarkerClusterer.prototype.isAverageCenter = function() {
  return this.averageCenter_;
};


/**
 *  Returns the array of markers in the clusterer.
 *
 *  @return {Array.<google.maps.Marker>} The markers.
 */
MarkerClusterer.prototype.getMarkers = function() {
  return this.markers_;
};


/**
 *  Returns the array of markers in the clusterer.
 *
 *  @return {Array.<google.maps.Marker>} The number of markers.
 */
MarkerClusterer.prototype.getTotalMarkers = function() {
  return this.markers_;
};


/**
 *  Sets the max zoom for the clusterer.
 *
 *  @param {number} maxZoom The max zoom level.
 */
MarkerClusterer.prototype.setMaxZoom = function(maxZoom) {
  this.maxZoom_ = maxZoom;
};


/**
 *  Gets the max zoom for the clusterer.
 *
 *  @return {number} The max zoom level.
 */
MarkerClusterer.prototype.getMaxZoom = function() {
  return this.maxZoom_ || this.map_.mapTypes[this.map_.getMapTypeId()].maxZoom;
};


/**
 *  The function for calculating the cluster icon image.
 *
 *  @param {Array.<google.maps.Marker>} markers The markers in the clusterer.
 *  @param {number} numStyles The number of styles available.
 *  @return {Object} A object properties: 'text' (string) and 'index' (number).
 *  @private
 */
MarkerClusterer.prototype.calculator_ = function(markers, numStyles) {
  var index = 0;
  var count = markers.length;
  var dv = count;
  while (dv !== 0) {
    dv = parseInt(dv / 10, 10);
    index++;
  }

  index = Math.min(index, numStyles);
  return {
    text: count,
    index: index
  };
};


/**
 * Set the calculator function.
 *
 * @param {function(Array, number)} calculator The function to set as the
 *     calculator. The function should return a object properties:
 *     'text' (string) and 'index' (number).
 *
 */
MarkerClusterer.prototype.setCalculator = function(calculator) {
  this.calculator_ = calculator;
};


/**
 * Get the calculator function.
 *
 * @return {function(Array, number)} the calculator function.
 */
MarkerClusterer.prototype.getCalculator = function() {
  return this.calculator_;
};


/**
 * Add an array of markers to the clusterer.
 *
 * @param {Array.<google.maps.Marker>} markers The markers to add.
 * @param {boolean} opt_nodraw Whether to redraw the clusters.
 */
MarkerClusterer.prototype.addMarkers = function(markers, lines, opt_nodraw) {
  for (var i = 0, marker; marker = markers[i]; i++) {
	line = lines[i];
    this.pushMarkerTo_(marker, line);
  }
  if (!opt_nodraw) {
    this.redraw();
  }
};


/**
 * Pushes a marker to the clusterer.
 *
 * @param {google.maps.Marker} marker The marker to add.
 * @private
 */
MarkerClusterer.prototype.pushMarkerTo_ = function(marker, line) {
  marker.setVisible(false);
  marker.setMap(null);
  line.setMap(null);
  marker.isAdded = false;
  if (marker['draggable']) {
    // If the marker is draggable add a listener so we update the clusters on
    // the drag end.
    var that = this;
    google.maps.event.addListener(marker, 'dragend', function() {
      marker.isAdded = false;
      that.resetViewport();
      that.redraw();
    });
  }
  this.markers_.push(marker);
  this.lines_.push(line)
};


/**
 * Adds a marker to the clusterer and redraws if needed.
 *
 * @param {google.maps.Marker} marker The marker to add.
 * @param {boolean} opt_nodraw Whether to redraw the clusters.
 */
MarkerClusterer.prototype.addMarker = function(marker, opt_nodraw) {
  this.pushMarkerTo_(marker);
  if (!opt_nodraw) {
    this.redraw();
  }
};


/**
 * Remove a marker from the cluster.
 *
 * @param {google.maps.Marker} marker The marker to remove.
 * @return {boolean} True if the marker was removed.
 */
MarkerClusterer.prototype.removeMarker = function(marker) {
  var index = -1;
  if (this.markers_.indexOf) {
    index = this.markers_.indexOf(marker);
  } else {
    for (var i = 0, m; m = this.markers_[i]; i++) {
      if (m == marker) {
        index = i;
        continue;
      }
    }
  }

  if (index == -1) {
    // Marker is not in our list of markers.
    return false;
  }

  this.markers_.splice(index, 1);
  marker.setVisible(false);
  marker.setMap(null);

  this.resetViewport();
  this.redraw();
  return true;
};


/**
 * Sets the clusterer's ready state.
 *
 * @param {boolean} ready The state.
 * @private
 */
MarkerClusterer.prototype.setReady_ = function(ready) {
  if (!this.ready_) {
    this.ready_ = ready;
    this.createClusters_();
  }
};


/**
 * Returns the number of clusters in the clusterer.
 *
 * @return {number} The number of clusters.
 */
MarkerClusterer.prototype.getTotalClusters = function() {
  return this.clusters_.length;
};


/**
 * Returns the google map that the clusterer is associated with.
 *
 * @return {google.maps.Map} The map.
 */
MarkerClusterer.prototype.getMap = function() {
  return this.map_;
};


/**
 * Sets the google map that the clusterer is associated with.
 *
 * @param {google.maps.Map} map The map.
 */
MarkerClusterer.prototype.setMap = function(map) {
  this.map_ = map;
};


/**
 * Returns the size of the grid.
 *
 * @return {number} The grid size.
 */
MarkerClusterer.prototype.getGridSize = function() {
  return this.gridSize_;
};


/**
 * Returns the size of the grid.
 *
 * @param {number} size The grid size.
 */
MarkerClusterer.prototype.setGridSize = function(size) {
  this.gridSize_ = size;
};


/**
 * Extends a bounds object by the grid size.
 *
 * @param {google.maps.LatLngBounds} bounds The bounds to extend.
 * @return {google.maps.LatLngBounds} The extended bounds.
 */
MarkerClusterer.prototype.getExtendedBounds = function(bounds) {
  var projection = this.getProjection();

  // Turn the bounds into latlng.
  var tr = new google.maps.LatLng(bounds.getNorthEast().lat(),
      bounds.getNorthEast().lng());
  var bl = new google.maps.LatLng(bounds.getSouthWest().lat(),
      bounds.getSouthWest().lng());

  // Convert the points to pixels and the extend out by the grid size.
  var trPix = projection.fromLatLngToDivPixel(tr);
  trPix.x += this.gridSize_;
  trPix.y -= this.gridSize_;

  var blPix = projection.fromLatLngToDivPixel(bl);
  blPix.x -= this.gridSize_;
  blPix.y += this.gridSize_;

  // Convert the pixel points back to LatLng
  var ne = projection.fromDivPixelToLatLng(trPix);
  var sw = projection.fromDivPixelToLatLng(blPix);

  // Extend the bounds to contain the new bounds.
  bounds.extend(ne);
  bounds.extend(sw);

  return bounds;
};


/**
 * Determins if a marker is contained in a bounds.
 *
 * @param {google.maps.Marker} marker The marker to check.
 * @param {google.maps.LatLngBounds} bounds The bounds to check against.
 * @return {boolean} True if the marker is in the bounds.
 * @private
 */
MarkerClusterer.prototype.isMarkerInBounds_ = function(marker, bounds) {
  return bounds.contains(marker.getPosition());
};


/**
 * Clears all clusters and markers from the clusterer.
 */
MarkerClusterer.prototype.clearMarkers = function() {
  this.resetViewport();

  // Set the markers a empty array.
  this.markers_ = [];
  this.lines_ = [];
};


/**
 * Clears all existing clusters and recreates them.
 */
MarkerClusterer.prototype.resetViewport = function() {
  // Remove all the clusters
  for (var i = 0, cluster; cluster = this.clusters_[i]; i++) {
    cluster.remove();
  }

  // Reset the markers to not be added and to be invisible.
  for (var i = 0, marker; marker = this.markers_[i]; i++) {
    line = this.lines_[i];
    marker.isAdded = false;
    marker.setMap(null);
    line.setMap(null);
    marker.setVisible(false);
  }

  this.clusters_ = [];
};


/**
 * Redraws the clusters.
 */
MarkerClusterer.prototype.redraw = function() {
  this.createClusters_();
};


/**
 * Creates the clusters.
 *
 * @private
 */
MarkerClusterer.prototype.createClusters_ = function() {
  if (!this.ready_) {
    return;
  }

  // Get our current map view bounds.
  // Create a new bounds object so we don't affect the map.
  var mapBounds = new google.maps.LatLngBounds(this.map_.getBounds().getSouthWest(),
      this.map_.getBounds().getNorthEast());
  var bounds = this.getExtendedBounds(mapBounds);

  for (var i = 0, marker; marker = this.markers_[i]; i++) {
    line = this.lines_[i];  
    var added = false;
    if (!marker.isAdded && this.isMarkerInBounds_(marker, bounds)) {
      for (var j = 0, cluster; cluster = this.clusters_[j]; j++) {
        if (!added && cluster.getCenter() &&
            cluster.isMarkerInClusterBounds(marker)) {
          added = true;
          cluster.addMarker(marker, line);
          break;
        }
      }

      if (!added) {
        // Create a new cluster.
        var cluster = new Cluster(this);
        cluster.addMarker(marker, line);
        this.clusters_.push(cluster);
      }
    }
  }
};


/**
 * A cluster that contains markers.
 *
 * @param {MarkerClusterer} markerClusterer The markerclusterer that this
 *     cluster is associated with.
 * @constructor
 * @ignore
 */
function Cluster(markerClusterer) {
  this.markerClusterer_ = markerClusterer;
  this.map_ = markerClusterer.getMap();
  this.gridSize_ = markerClusterer.getGridSize();
  this.averageCenter_ = markerClusterer.isAverageCenter();
  this.center_ = null;
  this.markers_ = [];
  this.lines_ = [];
  this.bounds_ = null;
  this.clusterIcon_ = new ClusterIcon(this, markerClusterer);
}

/**
 * Determins if a marker is already added to the cluster.
 *
 * @param {google.maps.Marker} marker The marker to check.
 * @return {boolean} True if the marker is already added.
 */
Cluster.prototype.isMarkerAlreadyAdded = function(marker) {
  if (this.markers_.indexOf) {
    return this.markers_.indexOf(marker) != -1;
  } else {
    for (var i = 0, m; m = this.markers_[i]; i++) {
      if (m == marker) {
        return true;
      }
    }
  }
  return false;
};


/**
 * Add a marker the cluster.
 *
 * @param {google.maps.Marker} marker The marker to add.
 * @return {boolean} True if the marker was added.
 */
Cluster.prototype.addMarker = function(marker, line) {
  if (this.isMarkerAlreadyAdded(marker)) {
    return false;
  }

  if (!this.center_) {
    this.center_ = marker.getPosition();
    this.calculateBounds_();
  } else {
    if (this.averageCenter_) {
      var lat = (this.center_.lat() + marker.getPosition().lat()) / 2;
      var lng = (this.center_.lng() + marker.getPosition().lng()) / 2;
      this.center_ = new google.maps.LatLng(lat, lng);
      this.calculateBounds_();
    }
  }


/*
  if (this.markers_.length == 0) {
    // Only 1 marker in this cluster so show the marker.
//    marker.setMap(this.map_);
//	line.setMap(this.map_);
 //   marker.setVisible(true);
	this.markers_.push(marker);
  } 
  if (this.markers_.length == 1) {
    // Hide the 1 marker that was showing.
    this.markers_[0].setMap(null);
    this.markers_[0].setVisible(false);
  }
*/
  marker.isAdded = true;
  this.markers_.push(marker);

  this.updateIcon();
  return true;
};


/**
 * Returns the marker clusterer that the cluster is associated with.
 *
 * @return {MarkerClusterer} The associated marker clusterer.
 */
Cluster.prototype.getMarkerClusterer = function() {
  return this.markerClusterer_;
};


/**
 * Returns the bounds of the cluster.
 *
 * @return {google.maps.LatLngBounds} the cluster bounds.
 */
Cluster.prototype.getBounds = function() {
  this.calculateBounds_();
  return this.bounds_;
};


/**
 * Removes the cluster
 */
Cluster.prototype.remove = function() {
  this.clusterIcon_.remove();
  this.markers_.length = 0;
  delete this.markers_;
};


/**
 * Returns the center of the cluster.
 *
 * @return {number} The cluster center.
 */
Cluster.prototype.getSize = function() {
  return this.markers_.length;
};


/**
 * Returns the center of the cluster.
 *
 * @return {Array.<google.maps.Marker>} The cluster center.
 */
Cluster.prototype.getMarkers = function() {
  return this.markers_;
};


/**
 * Returns the center of the cluster.
 *
 * @return {google.maps.LatLng} The cluster center.
 */
Cluster.prototype.getCenter = function() {
  return this.center_;
};


/**
 * Calculated the bounds of the cluster with the grid.
 *
 * @private
 */
Cluster.prototype.calculateBounds_ = function() {
  var bounds = new google.maps.LatLngBounds(this.center_, this.center_);
  this.bounds_ = this.markerClusterer_.getExtendedBounds(bounds);
};


/**
 * Determines if a marker lies in the clusters bounds.
 *
 * @param {google.maps.Marker} marker The marker to check.
 * @return {boolean} True if the marker lies in the bounds.
 */
Cluster.prototype.isMarkerInClusterBounds = function(marker) {
  return this.bounds_.contains(marker.getPosition());
};


/**
 * Returns the map that the cluster is associated with.
 *
 * @return {google.maps.Map} The map.
 */
Cluster.prototype.getMap = function() {
  return this.map_;
};


/**
 * Updates the cluster icon
 */
Cluster.prototype.updateIcon = function() {
  var zoom = this.map_.getZoom();
  var mz = this.markerClusterer_.getMaxZoom();

  if (zoom > mz) {
    // The zoom is greater than our max zoom so show all the markers in cluster.
    for (var i = 0, marker; marker = this.markers_[i]; i++) {
      marker.setMap(this.map_);
	  //line = this.lines_[i];
	  //line.setMap(this.map_);
      marker.setVisible(true);
    }
    return;
  }

//  if (this.markers_.length < 2) {
    // We have 0 or 1 markers so hide the icon.
  if (this.markers_.length < 1) {
    this.clusterIcon_.hide();
    return;
  }

  var numStyles = this.markerClusterer_.getStyles().length;
  var sums = this.markerClusterer_.getCalculator()(this.markers_, numStyles);
  this.clusterIcon_.setCenter(this.center_);
  this.clusterIcon_.setSums(sums);
  this.clusterIcon_.show();
};








/**
 * A cluster icon
 *
 * @param {Cluster} cluster The cluster to be associated with.
 * @param {Object} styles An object that has style properties:
 *     'url': (string) The image url.
 *     'height': (number) The image height.
 *     'width': (number) The image width.
 *     'anchor': (Array) The anchor position of the label text.
 *     'textColor': (string) The text color.
 *     'textSize': (number) The text size.
 * @param {number} opt_padding Optional padding to apply to the cluster icon.
 * @constructor
 * @extends google.maps.OverlayView
 * @ignore
 */
function ClusterIcon(cluster, markerClusterer) {
  cluster.getMarkerClusterer().extend(ClusterIcon, google.maps.OverlayView);  
  this.markerClusterer_ = markerClusterer;
  this.styles_ = markerClusterer.getStyles();
  this.padding_ = markerClusterer.getGridSize() || 0;
  this.cluster_ = cluster;
  this.center_ = null;
  this.map_ = cluster.getMap();
  this.div_ = null;
  this.sums_ = null;
  this.visible_ = false;

  this.setMap(this.map_);
}

/**
 * Triggers the clusterclick event and zoom's if the option is set.
 */
ClusterIcon.prototype.triggerClusterClick = function() {

	//$("#map").append($('<div></div>').addClass('floater').html('Data Loaded').effect('drop', {}, 1000));
	if (ctrl === false) {
		$(this.markerClusterer_.clusters_).each(function() { $(this.clusterIcon_.div_).removeClass('mapCircleSelected'); });
	}
	$(this.div_).toggleClass('mapCircleSelected');
	sel = $(this.div_).hasClass('mapCircleSelected');
	val = $.data($("#map")[0], "params");
	latlng = $.data($("#map")[0], "latlng");
	if (latlng === null || ctrl === false) {
		latlng = { coords:[], markers:[] };
	}
/*
	var posLat = [];
	var posLng = [];
*/
	var orgs = new dict();
	$(this.cluster_.markers_).each(function(i) {
		orgs.add(dataB.marks["City"][this.idx]+"~"+dataB.marks["cnt"][this.idx]); //show the top city
		var lat = this.position.lat();
		var lng = this.position.lng();
/*
		if (posLat.length==0) {
			posLat = [lat, lat];
			posLng = [lng, lng];
		}
		posLat = [(posLat[0]>lat)?lat:posLat[0], (posLat[1]<lat)?lat:posLat[1]]
		posLng = [(posLng[0]>lng)?lng:posLng[0], (posLng[1]<lng)?lng:posLng[1]]
*/
		if (sel) {
			latlng['markers'].push(this.idx);
			latlng['coords'].push([lat, lng]);
		} else {
			pos = $.inArray(this.idx, latlng['markers']);
			latlng['markers'].splice(pos, 1);
 			latlng['coords'].splice(pos, 1);
		}
	});
	orgs.sort();
/*
	$("#polyselect").html(orgs.ranked[0][0]+", "+vals.State);
	if (sel) {
		latlng['lat'].push(posLat);
		latlng['lng'].push(posLng);
	} else {
		latlng['lat'].splice($.inArray(posLat, latlng['lat']), 1);
		latlng['lng'].splice($.inArray(posLng, latlng['lng']), 1);
	}
*/
	$.data($("#map")[0], "latlng", latlng);

	if (latlng['coords'].length == 0)
		createDataTable({});
	else	
		createDataTable({"coords":latlng['coords'], "mode2":"latlng"});
	sideAttrs("");
	if ($.browser.msie)
		refreshMapGeo(latlng['markers']);
};

/**
 * Adding the cluster icon to the dom.
 * @ignore
 */
ClusterIcon.prototype.onAdd = function() {
	this.div_ = document.createElement('DIV');
	if (this.visible_) {
		this.div_.style.cssText = this.createCss();

		if (!$.browser.msie) {
			$(this.div_).addClass('mapCircle');
			if (this.sel_)
				$(this.div_).addClass('mapCircleSelected');
		}
		//$(this.div_).html(this.counter_);
		//content = this.tooltip_;
		//$(this.div_).tipTip({delay:400, keepAlive:false, maxWidth:'300px', edgeOffset:5, defaultPosition:"right", content:content});
	}
  
  this.getPanes().overlayImage.appendChild(this.div_);
  var that = this;
  G.event.addDomListener(this.div_, 'click', 	 function() { detailhover=true;  that.triggerClusterClick(); });
  G.event.addDomListener(this.div_, 'mousemove', function() { detailhover=true;  mapToolTip(that.tooltip_); });
  G.event.addDomListener(this.div_, 'mouseout',  function() { detailhover=false; mapToolTip(""); });
};


/**
 * Returns the position to place the div dending on the latlng.
 *
 * @param {google.maps.LatLng} latlng The position in latlng.
 * @return {google.maps.Point} The position in pixels.
 * @private
 */
ClusterIcon.prototype.getPosFromLatLng_ = function(latlng) {
	var pos = this.getProjection().fromLatLngToDivPixel(latlng);
	pos.x -= parseInt(this.width_ / 2, 10);
	pos.y -= parseInt(this.height_ / 2, 10);
	return pos;
};


/**
 * Draw the icon.
 * @ignore
 */
ClusterIcon.prototype.draw = function() {
	if (this.visible_) {
		var pos = this.getPosFromLatLng_(this.center_);
		$(this.div).css('top', pos.y + 'px');
		$(this.div).css('left', pos.x + 'px');
	}
};


/**
 * Hide the icon.
 */
ClusterIcon.prototype.hide = function() {
  if (this.div_) {
    this.div_.style.display = 'none';
  }
  this.visible_ = false;
};


/**
 * Position and show the icon.
 */
ClusterIcon.prototype.show = function() {
  if (this.div_) {
    this.div_.style.cssText = this.createCss();
	if (!$.browser.msie) {
		$(this.div_).addClass('mapCircle');
		if (this.sel_)
			$(this.div_).addClass('mapCircleSelected');
	}
    this.div_.style.display = '';
  }
  this.visible_ = true;
};


/**
 * Remove the icon from the map
 */
ClusterIcon.prototype.remove = function() {
  this.setMap(null);
};


/**
 * Implementation of the onRemove interface.
 * @ignore
 */
ClusterIcon.prototype.onRemove = function() {
  if (this.div_ && this.div_.parentNode) {
    this.hide();
    this.div_.parentNode.removeChild(this.div_);
    this.div_ = null;
  }
  if (this.div2_ && this.div2_.parentNode) {
    this.div2_.parentNode.removeChild(this.div2_);
    this.div2_ = null;
  }
};


/**
 * Set the sums of the icon.
 *
 * @param {Object} sums The sums containing:
 *   'text': (string) The text to display in the icon.
 *   'index': (number) The style index of the icon.
 */
ClusterIcon.prototype.setSums = function(sums) {
  this.sums_ = sums;
  this.text_ = sums.text;
  this.index_ = sums.index;
  if (this.div_) {
    this.div_.innerHTML = "";
  }

  this.useStyle();
};


/**
 * Sets the icon to the the styles.
 */
ClusterIcon.prototype.useStyle = function() {
  var index = Math.max(0, this.sums_.index - 1);
  index = Math.min(this.styles_.length - 1, index);
  var style = this.styles_[index];
  this.url_ = style['url'];
  this.height_ = style['height'];
  this.width_ = style['width'];
  this.textColor_ = style['textColor'];
  this.anchor = style['anchor'];
  this.textSize_ = style['textSize'];
};


/**
 * Sets the center of the icon.
 *
 * @param {google.maps.LatLng} center The latlng to set as the center.
 */
ClusterIcon.prototype.setCenter = function(center) {
  this.center_ = center;
};


/**
 * Create the css text based on the position of the icon.
 *
 * @param {google.maps.Point} pos The position.
 * @return {string} The css style text.
 */









colorMode = 2;
//0 is default
//1 = same size, one color, different intensities
//2 = different size, one color

ClusterIcon.prototype.createCss = function() {
	var style = [];

	aBool = false;
	sel_ = false;
	params = $.data($("#map")[0], "params");
	latlng = $.data($("#map")[0], "latlng");
	if (latlng == null)
		latlng = { coords:[], markers:[] };

	var orgs = new dict();
	var city = new dict();
	totCnt = 0;
	cnt = {NIH:0, NSF:0};
	amt = {NIH:0, NSF:0};
	$(this.cluster_.markers_).each(function(i) {
		totCnt += dataB.marks.cnt[this.idx];
		if (params.table == "grant" && dataB.marks.amt[this.idx]!="") {
			try {
				agency = dataB.marks.Agency[this.idx].split("~")[0];
				cnt[agency] += parseInt(dataB.marks.cnt[this.idx]);
				amt[agency] += parseInt(dataB.marks.amt[this.idx]);
			} catch (error) {
				x=0;
			}
		}
		aBool = (this.alpha)?true: aBool;
		org_ = this.toggle;
		orgs.add(dataB.marks[org_[1]][this.idx]);
		city.add(dataB.marks["City"][this.idx]+"~"+dataB.marks["cnt"][this.idx]); //show the top city
		if (latlng != null) 
			sel_ = ($.inArray(this.idx, latlng['markers'])!=-1)||sel_;
	});


	orgs.sort();
	city.sort();
	this.sel_ = sel_;

	this.tooltip_ = city.ranked[0][0];
	if (city.ranked.length > 1)
	for (i=1; i<3 && i<city.ranked.length; ++i)
		this.tooltip_ = this.tooltip_ + ", " + city.ranked[i][0];
	if (city.ranked.length > 3)
		this.tooltip_ = this.tooltip_ + "<br/> &nbsp; and " + (city.ranked.length-3) + " neighboring areas (zoom for detail).";
	this.tooltip_ = this.tooltip_ + "<br/>"

	if (params.table == "pat")
		this.tooltip_ = this.tooltip_ + "Patents: " + addCommas(totCnt) + "<table>";
	else if (params.table == "app")
		this.tooltip_ = this.tooltip_ + "Patent Applications: " + addCommas(totCnt) + "<table>";
	else {
		if ((params.agency == "NIH" || params.agency == "") && cnt['NIH']>0)
			this.tooltip_ = this.tooltip_ + "NIH: " + addCommas(cnt['NIH']) + " ($" + addCommas(amt['NIH']) + ")<br/>"
		if ((params.agency == "NSF" || params.agency == "") && cnt['NSF']>0)
			this.tooltip_ = this.tooltip_ + "NSF: " + addCommas(cnt['NSF']) + " ($" + addCommas(amt['NSF']) + ")<br/>"
		this.tooltip_ = this.tooltip_ + "<table>";
	}
	maxed = false;


	topTool = "";
	colorKeys = $.keys(dataB.colors);
	for (i=0; i<5 && i<orgs.ranked.length; ++i) {
		curr = orgs.ranked[i];
		bold = false;

		Name = null;
		if ($.inArray(curr[0]+"|"+org_[1], colorKeys))
			if (dataB.colors[curr[0]+"|"+org_[1]]!=null)
				Name = dataB.colors[curr[0]+"|"+org_[1]][1];
		if (Name == "") 
			Name = null
			//Name = "Unattributed";

		if (Name !== null) {
			counter = curr[1];
			maxed = true;
			topTool = topTool + "<tr><td>" + Name + "</td><td></td></tr>";
		}
	}
	if (topTool != "") {
		if (params.table == "pat" || params.table == "app")
			this.tooltip_ = this.tooltip_ + "<caption>Top " + ((org_[1]=="Label")?"Technologies":"Organizations") + ":</caption>" + topTool;
		else
			this.tooltip_ = this.tooltip_ + "<caption>Top " + ((org_[1]=="Label")?"Topics":"Institutions") + ":</caption>" + topTool;
	}
		

	/*
		if (maxed==false && orgs.fetch[org_[0]]!=null) {
			colors = dataB.colors[org_.join("|")][0];
			Name = dataB.colors[org_.join("|")][1];
			curr[1] = orgs.fetch[org_[0]];
			counter = curr[1];
			this.tooltip_ = this.tooltip_ + '<br/>...<br/><br/>';
			this.tooltip_ = this.tooltip_ + "<li><b>" + Name + ", " + curr[1] + " patent" + ((curr[1]>1)?"s":"") + "**</b></li>";
		}
	*/
	this.tooltip_ = this.tooltip_ + "</table>"

	dim = Math.min(48, Math.round(15 + 2*Math.log(totCnt)));  

	/*
	if (orgs.fetch[org_[0]]==null && org_[0]!='x') {
		url = '/img/square.png?d='+dim+'&c1='+colors[0].substring(1)+'60&c2='+colors[1].substring(1)+'60';
	} else {
	*/
		if (colorMode == 0) {
			url = 'img/circle.png?d='+dim+'&c1='+colors[0].substring(1)+'&c2='+colors[1].substring(1);
		} else if (colorMode == 1) {
			dim = 10;
			dim2 = Math.min(100, Math.round(25 + 8*Math.log(totCnt)));  
			url = 'img/circle.png?d=10&c1=0000A0'+dim2+'&c2=0000A0'+dim2;
		} else if (colorMode == 2) {
			dim = Math.min(48, Math.round(5 + 2*Math.log(totCnt)));  
			dim = Math.min(48, Math.round(10 + 0.010*totCnt));  
			dim = Math.min(32, Math.round(8 + 0.010*totCnt));  

			if (sel_)
				url = 'img/circle.png?d='+dim+'&c1=ff9b0895&c2=00000000';
			else
				url = 'img/circle.png?d='+dim+'&c1=00000080&c2=00000000';
		}
	/* } */
	this.height_ = dim;
	this.width_ = dim;
	var pos = this.getPosFromLatLng_(this.center_);

	if ($.browser.msie)
		style.push((document.all)?'filter:progid:DXImageTransform.Microsoft.AlphaImageLoader(sizingMethod=scale,src="' + url + '");':'background:url(' + url + '); background-repeat:no-repeat;');
							 
	if (typeof this.anchor_ === 'object') {
		if (typeof this.anchor_[0] === 'number' && this.anchor_[0] > 0 && this.anchor_[0] < dim) {
		  style.push('height:' + (dim - this.anchor_[0]) + 'px; padding-top:' + this.anchor_[0] + 'px;');
		} else {
		  style.push('height:' + dim + 'px; line-height:' + dim + 'px;');
		}
		if (typeof this.anchor_[1] === 'number' && this.anchor_[1] > 0 && this.anchor_[1] < dim) {
		  style.push('width:' + (dim - this.anchor_[1]) + 'px; padding-left:' + this.anchor_[1] + 'px;');
		} else {
		  style.push('width:' + dim + 'px; text-align:center;');
		}
	} else {
		style.push('height:' + dim + 'px; line-height:' + dim + 'px; width:' + dim + 'px; text-align:center;');

  //var txtColor = '#'+cObj.id[1];
	}
	var txtColor = this.textColor_ ? this.textColor_ : 'black';
	var txtSize = this.textSize_ ? this.textSize_ : 11;

	style.push('cursor:pointer; top:' + pos.y + 'px; left:' +
				pos.x + 'px; color:' + txtColor + '; position:absolute; font-size:' +
				txtSize + 'px; font-family:Arial,sans-serif; font-weight:bold');

//alert(style.join(''));
	  
  return style.join('');
};
















// Export Symbols for Closure
// If you are not going to compile with closure then you can remove the
// code below.
window['MarkerClusterer'] = MarkerClusterer;
MarkerClusterer.prototype['addMarker'] = MarkerClusterer.prototype.addMarker;
MarkerClusterer.prototype['addMarkers'] = MarkerClusterer.prototype.addMarkers;
MarkerClusterer.prototype['clearMarkers'] =
    MarkerClusterer.prototype.clearMarkers;
MarkerClusterer.prototype['getCalculator'] =
    MarkerClusterer.prototype.getCalculator;
MarkerClusterer.prototype['getGridSize'] =
    MarkerClusterer.prototype.getGridSize;
MarkerClusterer.prototype['getMap'] = MarkerClusterer.prototype.getMap;
MarkerClusterer.prototype['getMarkers'] = MarkerClusterer.prototype.getMarkers;
MarkerClusterer.prototype['getMaxZoom'] = MarkerClusterer.prototype.getMaxZoom;
MarkerClusterer.prototype['getStyles'] = MarkerClusterer.prototype.getStyles;
MarkerClusterer.prototype['getTotalClusters'] =
    MarkerClusterer.prototype.getTotalClusters;
MarkerClusterer.prototype['getTotalMarkers'] =
    MarkerClusterer.prototype.getTotalMarkers;
MarkerClusterer.prototype['redraw'] = MarkerClusterer.prototype.redraw;
MarkerClusterer.prototype['removeMarker'] =
    MarkerClusterer.prototype.removeMarker;
MarkerClusterer.prototype['resetViewport'] =
    MarkerClusterer.prototype.resetViewport;
MarkerClusterer.prototype['setCalculator'] =
    MarkerClusterer.prototype.setCalculator;
MarkerClusterer.prototype['setGridSize'] =
    MarkerClusterer.prototype.setGridSize;
MarkerClusterer.prototype['onAdd'] = MarkerClusterer.prototype.onAdd;
MarkerClusterer.prototype['draw'] = MarkerClusterer.prototype.draw;

Cluster.prototype['getCenter'] = Cluster.prototype.getCenter;
Cluster.prototype['getSize'] = Cluster.prototype.getSize;
Cluster.prototype['getMarkers'] = Cluster.prototype.getMarkers;

ClusterIcon.prototype['onAdd'] = ClusterIcon.prototype.onAdd;
ClusterIcon.prototype['draw'] = ClusterIcon.prototype.draw;
ClusterIcon.prototype['onRemove'] = ClusterIcon.prototype.onRemove;
