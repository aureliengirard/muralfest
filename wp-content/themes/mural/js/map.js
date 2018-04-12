$(function(){
	if($('#gmap').length && mapData.gmap != null){
		var map;
		var geocoder;
		var MY_MAPTYPE_ID = 'custom_style';

		function initialize() {
			var lat = mapData.gmap.lat;
			var lng = mapData.gmap.lng;
			
			var markerPos = new google.maps.LatLng(lat, lng);
			
			var featureOpts = [];
			
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
				content: '<a href="https://www.google.ca/maps/dir//'+encodeURIComponent(mapData.gmap.address)+'" target="_blank">'+mapData.gmap.address+'</a>'
			});
			
			var markerMap = new google.maps.Marker({
				position: markerPos,
				map: map,
				title: mapData.siteName,
				//icon: mapData.themeURI+'/images/marker.png'
			});// A marker
			
			markerMap.addListener('click', function() {
				infowindow.open(map, markerMap);
			});
		}// init

		google.maps.event.addDomListener(window, 'load', initialize);
	}
});