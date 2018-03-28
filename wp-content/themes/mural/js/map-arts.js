$(function(){
	if($('#gmap-arts').length){
		var map;
		var geocoder;
		var MY_MAPTYPE_ID = 'custom_style';

		function initialize() {
			
			var featureOpts = [];
					
			var mapOptions = {
				zoom: 13,
				scrollwheel : false,
				zoomControl: true,
				disableDefaultUI: true,
				labelContent: '',
				mapTypeControlOptions: {
					mapTypeIds: [google.maps.MapTypeId.ROADMAP, MY_MAPTYPE_ID]
				},
				mapTypeId: MY_MAPTYPE_ID
			};// mappOptions
			
			map = new google.maps.Map(document.getElementById('gmap-arts'), mapOptions);

			var styledMapOptions = {
				name: 'Custom Style'
			};//StyledMapOptions
			
			var customMapType = new google.maps.StyledMapType(featureOpts, styledMapOptions);
			
			map.mapTypes.set(MY_MAPTYPE_ID, customMapType);

			var bounds = new google.maps.LatLngBounds();
			var infoWindows = [];
			map.markers = [];

			artworks.forEach(artwork => {
				lat = artwork.coords.lat;
				lng = artwork.coords.lng;
		
				var markerPos = new google.maps.LatLng(lat, lng);

				var infowindow = new google.maps.InfoWindow({
					content: '<div class="artwork-data"><figure>'+artwork.thumbnail+'</figure><div class="artwork-infos"><h3>'+artwork.title+'</h3><p class="artist"><a href="'+artwork.artist.link+'">'+artwork.artist.name+'</a></p><p class="date">'+artwork.formatted_date+'</p></div><div class="artwork-desc">'+artwork.description+'<p><a href="'+artwork.link+'">En lire plus</a></p></div></div>'
				});

				infoWindows.push(infowindow);

				var color = '#9b0fb0';

				var currentDate = new Date();
				var artworkDate = new Date(artwork.date);

				if(artworkDate.getFullYear() == currentDate.getFullYear()){
					color = '#d8081c';
				}
				
				var markerMap = new google.maps.Marker({
					position: markerPos,
					map: map,
					title: artwork.title,
					icon: {
						path: 'M172.268 501.67C26.97 291.031 0 269.413 0 192 0 85.961 85.961 0 192 0s192 85.961 192 192c0 77.413-26.97 99.031-172.268 309.67-9.535 13.774-29.93 13.773-39.464 0zM192 272c44.183 0 80-35.817 80-80s-35.817-80-80-80-80 35.817-80 80 35.817 80 80 80z',
						anchor: new google.maps.Point(199,530),
						fillColor: color,
						fillOpacity: 0.8,
						scale: 0.1,
						strokeColor: color,
						strokeOpacity: 1,
						strokeWeight: 2
					}
				});// A marker

				map.markers.push(markerMap);
				bounds.extend(markerMap.position);
				
				markerMap.addListener('click', function() {
					for (var i=0;i<infoWindows.length;i++) {
						infoWindows[i].close();
					}

					infowindow.open(map, markerMap);
				});
			});

			map.fitBounds(bounds);
			
		}// init

		google.maps.event.addDomListener(window, 'load', initialize);
	}
});