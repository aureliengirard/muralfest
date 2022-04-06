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
					"featureType": "administrative",
					"elementType": "all",
					"stylers": [
						{
							"saturation": "-100"
						}
					]
				},
				{
					"featureType": "administrative.province",
					"elementType": "all",
					"stylers": [
						{
							"visibility": "off"
						}
					]
				},
				{
					"featureType": "landscape",
					"elementType": "all",
					"stylers": [
						{
							"saturation": -100
						},
						{
							"lightness": 65
						},
						{
							"visibility": "on"
						}
					]
				},
				{
					"featureType": "poi",
					"elementType": "all",
					"stylers": [
						{
							"saturation": -100
						},
						{
							"lightness": "50"
						},
						{
							"visibility": "simplified"
						}
					]
				},
				{
					"featureType": "road",
					"elementType": "all",
					"stylers": [
						{
							"saturation": "-100"
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
					"elementType": "all",
					"stylers": [
						{
							"lightness": "30"
						}
					]
				},
				{
					"featureType": "road.local",
					"elementType": "all",
					"stylers": [
						{
							"lightness": "40"
						}
					]
				},
				{
					"featureType": "transit",
					"elementType": "all",
					"stylers": [
						{
							"saturation": -100
						},
						{
							"visibility": "simplified"
						}
					]
				},
				{
					"featureType": "water",
					"elementType": "geometry",
					"stylers": [
						{
							"hue": "#ffff00"
						},
						{
							"lightness": -25
						},
						{
							"saturation": -97
						}
					]
				},
				{
					"featureType": "water",
					"elementType": "labels",
					"stylers": [
						{
							"lightness": -25
						},
						{
							"saturation": -100
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