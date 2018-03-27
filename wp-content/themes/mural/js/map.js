$(function(){
	if($('#gmap').length){
		var map;
		var geocoder;
		var MY_MAPTYPE_ID = 'custom_style';

		function initialize() {
			
			geocoder = new google.maps.Geocoder();
			var lat;
			var lng;
			
			geocoder.geocode( { 'address': mapData.gmap.address}, function(results, status) {
				if (status == google.maps.GeocoderStatus.OK) {
					lat = results[0].geometry.location.lat();
					lng = results[0].geometry.location.lng();
			
					var markerPos = new google.maps.LatLng(lat, lng);
					
					var featureOpts = [
					     {
					        "featureType": "administrative",
					        "elementType": "labels.text.fill",
					        "stylers": [
					            {
					                "color": "#444444"
					            }
					        ]
					    },
					    {
					        "featureType": "landscape",
					        "elementType": "all",
					        "stylers": [
					            {
					                "color": "#f2f2f2"
					            }
					        ]
					    },
					    {
					        "featureType": "poi",
					        "elementType": "all",
					        "stylers": [
					            {
					                "visibility": "off"
					            }
					        ]
					    },
					    {
					        "featureType": "road",
					        "elementType": "all",
					        "stylers": [
					            {
					                "saturation": -100
					            },
					            {
					                "lightness": 45
					            }
					        ]
					    },
					    {
					        "featureType": "road.highway",
					        "elementType": "all",
					        "stylers": [
					            {
					                "visibility": "simplified"
					            }
					        ]
					    },
					    {
					        "featureType": "road.arterial",
					        "elementType": "labels.icon",
					        "stylers": [
					            {
					                "visibility": "off"
					            }
					        ]
					    },
					    {
					        "featureType": "transit",
					        "elementType": "all",
					        "stylers": [
					            {
					                "visibility": "off"
					            }
					        ]
					    },
					    {
					        "featureType": "water",
					        "elementType": "all",
					        "stylers": [
					            {
					                "color": "#6997b1"
					            },
					            {
					                "visibility": "on"
					            }
					        ]
					    }
					];
					
					var mapOptions = {
						zoom: 13,
						scrollwheel : false,
						zoomControl: true,
						disableDefaultUI: true,
						center: markerPos,
						labelContent: mapData.siteName,
						mapTypeControlOptions: {
						  mapTypeIds: [google.maps.MapTypeId.ROADMAP, MY_MAPTYPE_ID]
						},
						mapTypeId: MY_MAPTYPE_ID
					};// mappOptions
					
					map = new google.maps.Map(document.getElementById('gmap'), mapOptions);
					
					
					var styledMapOptions = {
						name: 'Custom Style'
					};//StyledMapOptions
					
					var customMapType = new google.maps.StyledMapType(featureOpts, styledMapOptions);
					
					map.mapTypes.set(MY_MAPTYPE_ID, customMapType);
					
					var infowindow = new google.maps.InfoWindow({
						content: '<a href="https://www.google.ca/maps/dir//'+encodeURIComponent(mapData.gmap.address)+'" target="_blank">'+traduction.itineraire+'</a>'
					});
					
					var markerMap = new google.maps.Marker({
						position: markerPos,
						map: map,
						title: mapData.siteName,
						icon: mapData.themeURI+'/images/marker.png'
					});// A marker
					
					markerMap.addListener('click', function() {
						infowindow.open(map, markerMap);
					});
			
				} else {
					jQuery('#gmap').hide();
				}
			});// geocoder
		}// init

		google.maps.event.addDomListener(window, 'load', initialize);
	}
});