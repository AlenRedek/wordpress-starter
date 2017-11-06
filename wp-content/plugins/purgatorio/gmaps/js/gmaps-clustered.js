/* gmap script */
/* global google, mapData, mapStyle, scriptData, strings, MarkerClusterer */

var map;
var markers = [];
var origin = false;
var userPos = false;
var initialPosition = {};
var imagesPath = scriptData.plugin_url + '/images';
var mapCanvas = document.getElementById(scriptData.gmaps_container);

var directionsDisplay = new google.maps.DirectionsRenderer();
var directionsService = new google.maps.DirectionsService();

var mh_loadGmap = function(){
	var mapOptions = {
		styles: mapStyle.theme,
		scrollwheel: false,
		zoomControl: true,
          zoomControlOptions: {
              position: google.maps.ControlPosition.TOP_LEFT
          },
	};

	map = new google.maps.Map(mapCanvas, mapOptions);

	directionsDisplay.setMap(map);

	if(navigator.geolocation){
        navigator.geolocation.getCurrentPosition(function(pos){
            origin = new google.maps.LatLng(pos.coords.latitude, pos.coords.longitude);
            userPos = {
            	lat: pos.coords.latitude,
            	lng: pos.coords.longitude,
            };
		    mh_getMarkersDistances(function(){
		    	mh_createMarkers();
				mh_toggleMarkers();
		    });
        }, function(){
        	console.log('Geolocation works only on secure content (https://)');
        	mh_createMarkers();
			mh_toggleMarkers();
        });
    }else{
        console.log("Geolocation is not supported by this browser.");
        mh_createMarkers();
		mh_toggleMarkers();
    }

    google.maps.event.addListenerOnce(map, 'idle', function(){
    	if(markers.length == 1){
    		// google.maps.event.trigger(markers[0], 'click');
    	}
	});
};

function mh_getMarkersDistances(callback){
	var destinations = [];
	for(var k in mapData){
		var d = new google.maps.LatLng(mapData[k].lat, mapData[k].lng);
		destinations.push(d);
	}

	var distanceMatrix  = new google.maps.DistanceMatrixService();
	var distanceRequest = {
		origins: [origin],
		destinations: destinations,
		travelMode: google.maps.TravelMode.DRIVING,
		unitSystem: google.maps.UnitSystem.METRIC,
		avoidHighways: false,
		avoidTolls: false,
	};
	distanceMatrix.getDistanceMatrix(distanceRequest, function(response, status){
	    if(status != google.maps.DistanceMatrixStatus.OK){
	        console.log('Error', status);
	    }else{
			for(var k in mapData){
				if(response.rows[0].elements[k]){
					mapData[k].gData = response.rows[0].elements[k];
				}
			}
	    }
	    callback();
	});
}

function mh_createMarkers(){
	var infoWindow = new google.maps.InfoWindow();
	
	mapData.map(function(markerObject, i) {
		if(markerObject.lat > 0 && markerObject.lng > 0){
			var m = {
				lat: parseFloat(markerObject.lat),
				lng: parseFloat(markerObject.lng),
			};
			var marker = new google.maps.Marker({
				position: new google.maps.LatLng(markerObject.lat, markerObject.lng),
				coordinates: m,
				id: markerObject.id,
				title: markerObject.title,
				icon: mapStyle.marker
			});

			marker.addListener('click', (function(marker, i){
				return function(){
					var html = '';
					html = infoWindowHtml(markerObject, i);
					infoWindow.setContent(html);
					infoWindow.open(map, marker);

					/* Highlights post based on selected marker on map */
					jQuery('.type-'+markerObject.post_type).removeClass('border-primary');
					jQuery('#post-'+markerObject.id).toggleClass('border-primary');
				};
			})(marker, i));

			infoWindow.addListener('closeclick', (function(){
				return function(){
					/* Clears highlighted post on info window close */
					jQuery('.type-'+markerObject.post_type).removeClass('border-primary');
				};
			})(marker, i));

			markers.push(marker);
		}
	});

	var mcOptions = {
    	minimumClusterSize: 2,
    	maxZoom: 20,
    	imagePath: imagesPath + '/m'
    };
	var markerCluster = new MarkerClusterer(map, markers, mcOptions);

	google.maps.event.addListener(infoWindow, 'domready', function() {
        var iwOuter = jQuery('.gm-style-iw'),
            iwBackground = iwOuter.prev(),
            iwCloseBtn = iwOuter.next();

        // Removes background shadow DIV
        iwBackground.children(':nth-child(2)').css({'display': 'none'});
        // Changes the desired tail shadow color.
        iwBackground.children(':nth-child(3)').find('div').children().css({'box-shadow': 'none', 'z-index': '1'});
        // Apply the desired effect to the close button
        iwCloseBtn.children('img').attr('src', imagesPath + '/close.png');
        iwCloseBtn.children('img').css({'width': '15', 'height': '15', 'top': '0', 'left': '0'});
		iwCloseBtn.css({'opacity': '1', 'width': '15', 'height': '15', 'border': 'none', 'border-radius': '0', 'box-shadow': 'none'});
        if(jQuery(window).width() + 17 > 1024){
            // Removes white background DIV
            iwBackground.children(':nth-child(4)').css({'display': 'none'});
            // Moves the infowindow 135px to the right.
            //iwOuter.parent().parent().css({'left': '135px'});
            // Hides the shadow of the arrow.
            iwBackground.children(':nth-child(1)').attr('style', function(i,s){ return s + 'display: none;'});
            // Moves the arrow 130px to the left margin.
            //iwBackground.children(':nth-child(3)').attr('style', function(i,s){ return s + 'left: 130px !important;'});
            // Changes the desired tail shadow color.
            iwBackground.children(':nth-child(3)').find('div').children().css({'border': '1px solid #000', 'border-top': 'none'});
            iwBackground.children(':nth-child(3)').children(':nth-child(1)').children().css({'border-right': 'none'});
            iwBackground.children(':nth-child(3)').children(':nth-child(2)').children().css({'border-left': 'none'});
			// Apply the desired effect to the close button
        	iwCloseBtn.css({'top': '30px', 'right': '50px'});
        }
    });
}

function infoWindowHtml(d, k){
	var html                = '',
	    widthInfoBox        = 100;
	/*if(d.attachment_image && jQuery(window).width() + 17 > 1024){
	    widthInfoBox        = 50;
	}*/

	html += '<div class="gmap-info-box-wrapper flex-xs space-between">';
    	html += '<div class="width-'+widthInfoBox+'">';
        	html += '<div class="gmap-info-box">';
            	html += '<h4 class="uppercase">'+d.title+'</h4>';
            	if(d.post_meta){
            	    html += '<p>'+d.post_meta+'</p>';
            	}
				if(d.gData){
            		html += '<p>'+strings.distance+' '+d.gData.distance.text+'</p>';
            		//html += '<p><button class="btn btn-primary btn-xs" type="button" onclick="mh_showDirections('+k+')">'+strings.show_direction+'</button></p>';
            	}
            	if(userPos){
            		var directions = userPos.lat+','+userPos.lng+'/'+d.lat+','+d.lng;
            		html += '<div><a href="https://www.google.com/maps/dir/'+directions+'" target="_blank" class="btn btn-primary">'+strings.show_direction+'</a></div>';
            	}
        	html += '</div>';
    	html += '</div>';
	html += '</div>';

	return html;
}

function mh_toggleMarkers(){
	var lat = 0, lng = 0, num = 0;
	var bound = new google.maps.LatLngBounds();
	markers.map(function(markerObject, i) {
		markerObject.setMap(map);
		lat += parseFloat(markerObject.coordinates.lat);
		lng += parseFloat(markerObject.coordinates.lng);
		num++;
		bound.extend(new google.maps.LatLng(markerObject.coordinates.lat, markerObject.coordinates.lng));
	});
	lat = lat/num;
	lng = lng/num;

	initialPosition = {
		center: new google.maps.LatLng(lat, lng),
		zoom: getBoundsZoomLevel(bound, {width: jQuery('#'+scriptData.gmaps_container).width(), height: jQuery('#'+scriptData.gmaps_container).height()})
	}
	map.setCenter(initialPosition.center);
	map.setZoom(initialPosition.zoom);
}

function getBoundsZoomLevel(bounds, mapDim){
    var WORLD_DIM = { height: 300, width: 300 };	//spreminjaj ta stevila za vecji/manjsi zoom - manjse stevilo vecji zoom
    var ZOOM_MAX = 12;

    function latRad(lat) {
        var sin = Math.sin(lat * Math.PI / 180);
        var radX2 = Math.log((1 + sin) / (1 - sin)) / 2;
        return Math.max(Math.min(radX2, Math.PI), -Math.PI) / 2;
    }

    function zoom(mapPx, worldPx, fraction) {
        return Math.floor(Math.log(mapPx / worldPx / fraction) / Math.LN2);
    }

    var ne = bounds.getNorthEast();
    var sw = bounds.getSouthWest();

    var latFraction = (latRad(ne.lat()) - latRad(sw.lat())) / Math.PI;

    var lngDiff = ne.lng() - sw.lng();
    var lngFraction = ((lngDiff < 0) ? (lngDiff + 360) : lngDiff) / 360;

    var latZoom = zoom(mapDim.height, WORLD_DIM.height, latFraction);
    var lngZoom = zoom(mapDim.width, WORLD_DIM.width, lngFraction);

    return Math.min(latZoom, lngZoom, ZOOM_MAX);
}

function mh_showDirections(key){
	if(origin){
		var end = new google.maps.LatLng(mapData[key].lat, mapData[key].lng);
		var request = {
			origin: origin,
			destination: end,
			travelMode: google.maps.TravelMode.DRIVING,
			unitSystem: google.maps.UnitSystem.METRIC,
			avoidHighways: false,
			avoidTolls: false,
		};
		directionsService.route(request, function(result, status){
			if(status == 'OK'){
				directionsDisplay.setDirections(result);
			}else{
				console.log('Bad data');
			}
		});
	}else{
		console.log('No position');
	}
}

(function($){
	google.maps.event.addDomListener(window, 'load', mh_loadGmap);
}(jQuery));