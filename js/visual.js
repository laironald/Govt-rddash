    function refreshMap() {
		panel = $('nodes');
		$('nodes').innerHTML = '';
        if (markerClusterer) {
          markerClusterer.clearMarkers();
        }
		function aPos(pos, idx) { //adjust position based on scale
			return ((idx===0)?1:-1)*dataB.sc[idx][1]*(pos[idx]-dataB.sc[idx][0]);
		}
		function eLatLng(num) {
			return new G.LatLng(aPos(dataB.marks[num].pos, 1), aPos(dataB.marks[num].pos, 0));
		}
        var markers = [];  
		var lines = [];
        for (var i = 0; i < dataB.marks.length; ++i) {
			var latlng = eLatLng(i);
			var ex = (dataB.marks[i].vBool.flag || dataB.marks[i].vBool.NIH || dataB.marks[i].vBool.NSF)?1 :0;
			var imgUrl = 'http://140.247.116.250/marker.py/normal?c1='+dataB.marks[i].col[0]+'&c2='+dataB.marks[i].col[1]+'&ex='+ex;
			//20 is min size, 4 in file -- (4 is min size)
			size = parseInt(2*dataB.marks[i].size+4);
			var marker = new G.Marker({
				position: latlng,
				icon: new G.MarkerImage(imgUrl+'&d='+size, new G.Size(size, size)),
				idx: i
			});
			markers.push(marker);

			path = [];
			for(var j = 0; j < dataB.marks[i].edge.length; ++j) {
				path.push(eLatLng(i));
				path.push(eLatLng(dataB.marks[i].edge[j]));
			}
			var line = new G.Polyline({ path: path, strokeColor: "#CCCCCC", strokeOpacity: 0.8, strokeWeight: 1 });
			var sline = new G.Polyline({ path: path, strokeColor: "#1e90ff", strokeOpacity: 0.8, strokeWeight: 3 });
			lines.push(line);
			var fn = markerClickFunction(sline, dataB.marks[i], latlng);
			G.event.addListener(marker, 'click', fn);
			
			//Side markers
			if (init) {
				var item = document.createElement("div");
				var title = document.createElement("div");
				len = 30;
				asg = (dataB.marks[i].asg===null)?"" :dataB.marks[i].asg;
				asg = (asg.length > len)?asg.substring(0,len)+"...":asg;
				title.innerHTML = '<img style="float:left" src="' + imgUrl + '&d=20"/>' + dataB.marks[i].name + 
					((dataB.marks[i].vBool.NIH)?'<span>NIH</span>':'') +
					((dataB.marks[i].vBool.NSF)?'<span>NSF</span>':'') +
					'<br/><font style="color:#88a">' + asg + '</font>';
				item.className = 'side';
				panel.appendChild(item);
				item.appendChild(title);
				G.event.addDomListener(item, 'click', fn);			
			}	
		}
		init = false;
 
		var zoom = null;
		if (dataB.marks.length<100) {
			zoom = 3;
		} else if (dataB.marks.length<250 && dataB.marks.length>100) {
			zoom = 4;
		} else if (dataB.marks.length<750) {
			zoom = 5;
		} else if (dataB.marks.length<1500) {
			zoom = 6;
		} else {
			zoom = 7;
		}
        var size = 50;
        zoom = zoom == -1 ? null : zoom;
        size = size == -1 ? null : size;
		info = new G.InfoWindow();
        markerClusterer = new MarkerClusterer(map, markers, lines, {
          maxZoom: zoom,
          gridSize: size
        });
    }	
	markerClickFunction = function(line, mark, latlng) {
		return function(e) {
			asg = (mark.asg===null)?"" :mark.asg;
			text = mark.name + 
				((mark.vBool.NIH)?'<span class="s1">NIH</span>':'') +
				((mark.vBool.NSF)?'<span class="s1">NSF</span>':'') +
				"<br/>" + asg + "<hr/><div style='max-height: 125px; overflow:auto;'><b>Collaborators</b>:";
			for(var k=0; k<mark.edge.length; ++k) {
				asg = (dataB.marks[mark.edge[k]].asg==="")?"" :"<font style='color:#88a'>(" + dataB.marks[mark.edge[k]].asg+")</font>";
				text = text + "<br/>" + (k+1) + ". " + dataB.marks[mark.edge[k]].name + " " + asg;
			}
			text = text + "</div>";
			info.setContent(text);
			
			info.setPosition(latlng);
			info.open(map);
			if (prevLine!==null) {
				prevLine.setMap(null);
			}
			line.setMap(map);
			prevLine = line;
			e.preventDefault();
		};
	};	
    function initialize() {
		map = new G.Map($('map'), {
		  zoom: 4,
		  center: new G.LatLng(-7.5, 12.5),
		  mapTypeControlOptions: { mapTypeIds: [MapLabel] }
		});
		map.mapTypes.set(MapLabel, new G.StyledMapType([ 
				{
					featureType: "all",
					elementType: "all",
					stylers: [ { visibility: 'off' }, { lightness: 75 } ]
				}
			], { name: MapLabel }));
		map.setMapTypeId(MapLabel);
		refreshMap();
	}
    function clearClusters(e) {
        e.preventDefault();
        e.stopPropagation();
        markerClusterer.clearMarkers();
    } 
