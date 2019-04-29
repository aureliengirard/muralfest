$(function(){
	if($('#gmap').length && mapData.gmap != null){
		var map;
		var geocoder;
		var MY_MAPTYPE_ID = 'custom_style';

		function initialize() {
			geocoder = new google.maps.Geocoder();
			var lat = mapData.gmap.lat;
			var lng = mapData.gmap.lng;
			
			var markerPos = new google.maps.LatLng(lat, lng);
			
			var featureOpts = [
				{
					"featureType": "all",
					"elementType": "labels.text.fill",
					"stylers": [
						{
							"lightness": "-66"
						}
					]
				},
				{
					"featureType": "all",
					"elementType": "labels.text.stroke",
					"stylers": [
						{
							"weight": 2
						}
					]
				},
				{
					"featureType": "all",
					"elementType": "labels.icon",
					"stylers": [
						{
							"visibility": "off"
						},
						{
							"color": "#dc3d1d"
						}
					]
				},
				{
					"featureType": "landscape",
					"elementType": "geometry",
					"stylers": [
						{
							"lightness": 30
						},
						{
							"saturation": 30
						}
					]
				},
				{
					"featureType": "landscape.man_made",
					"elementType": "geometry",
					"stylers": [
						{
							"color": "#f87c0a"
						},
						{
							"lightness": "48"
						}
					]
				},
				{
					"featureType": "poi",
					"elementType": "geometry",
					"stylers": [
						{
							"saturation": 20
						}
					]
				},
				{
					"featureType": "poi",
					"elementType": "geometry.fill",
					"stylers": [
						{
							"color": "#f68b4b"
						}
					]
				},
				{
					"featureType": "poi.park",
					"elementType": "geometry",
					"stylers": [
						{
							"lightness": 20
						},
						{
							"saturation": -20
						}
					]
				},
				{
					"featureType": "road",
					"elementType": "geometry",
					"stylers": [
						{
							"lightness": 10
						},
						{
							"saturation": -30
						}
					]
				},
				{
					"featureType": "road",
					"elementType": "geometry.stroke",
					"stylers": [
						{
							"saturation": 25
						},
						{
							"lightness": 25
						}
					]
				},
				{
					"featureType": "road.highway",
					"elementType": "geometry",
					"stylers": [
						{
							"saturation": "-2"
						},
						{
							"lightness": "18"
						},
						{
							"weight": "0.33"
						}
					]
				},
				{
					"featureType": "road.arterial",
					"elementType": "geometry.fill",
					"stylers": [
						{
							"color": "#ffffff"
						},
						{
							"weight": "0.80"
						}
					]
				},
				{
					"featureType": "road.local",
					"elementType": "geometry",
					"stylers": [
						{
							"color": "#fffefe"
						},
						{
							"weight": "0.76"
						}
					]
				},
				{
					"featureType": "water",
					"elementType": "all",
					"stylers": [
						{
							"lightness": -20
						}
					]
				},
				{
					"featureType": "water",
					"elementType": "geometry",
					"stylers": [
						{
							"visibility": "on"
						}
					]
				},
				{
					"featureType": "water",
					"elementType": "geometry.fill",
					"stylers": [
						{
							"visibility": "on"
						},
						{
							"color": "#e24719"
						},
						{
							"lightness": "16"
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

			var color = '#1097a6';
			var currentDate = new Date();
			
			if(mapData.year == currentDate.getFullYear()){
				color = '#04d3ff';
				currentYear = true;
			}
			
			var markerMap = new google.maps.Marker({
				position: markerPos,
				map: map,
				title: mapData.siteName,
				icon: {
					path: 'M172.268 501.67C26.97 291.031 0 269.413 0 192 0 85.961 85.961 0 192 0s192 85.961 192 192c0 77.413-26.97 99.031-172.268 309.67-9.535 13.774-29.93 13.773-39.464 0zM192 272c44.183 0 80-35.817 80-80s-35.817-80-80-80-80 35.817-80 80 35.817 80 80 80z',
					anchor: new google.maps.Point(199,530),
					fillColor: color,
					fillOpacity: 1,
					scale: 0.05,
					strokeColor: color,
					strokeOpacity: 1,
					strokeWeight: 2
				}
			});// A marker
			
			markerMap.addListener('click', function() {
				window.open('https://www.google.ca/maps/dir//'+encodeURIComponent(mapData.gmap.address), '_blank');
				
			});
		}// init

		google.maps.event.addDomListener(window, 'load', initialize);
		
	}else{
		$('#gmap').hide();
	}
});