$(function(){
	if($('#gmap-arts').length){
		var map;
		var geocoder;
		var MY_MAPTYPE_ID = 'custom_style';

		function initialize() {
			
			var featureOpts = [
				{
					"featureType": "all",
					"elementType": "labels.text.fill",
					"stylers": [
						{
							"saturation": 36
						},
						{
							"color": "#333333"
						},
						{
							"lightness": 40
						}
					]
				},
				{
					"featureType": "all",
					"elementType": "labels.text.stroke",
					"stylers": [
						{
							"visibility": "on"
						},
						{
							"color": "#ffffff"
						},
						{
							"lightness": 16
						}
					]
				},
				{
					"featureType": "all",
					"elementType": "labels.icon",
					"stylers": [
						{
							"visibility": "off"
						}
					]
				},
				{
					"featureType": "administrative",
					"elementType": "geometry.fill",
					"stylers": [
						{
							"color": "#fefefe"
						},
						{
							"lightness": 20
						}
					]
				},
				{
					"featureType": "administrative",
					"elementType": "geometry.stroke",
					"stylers": [
						{
							"color": "#fefefe"
						},
						{
							"lightness": 17
						},
						{
							"weight": 1.2
						}
					]
				},
				{
					"featureType": "landscape",
					"elementType": "geometry",
					"stylers": [
						{
							"color": "#fed6dc"
						},
						{
							"lightness": 20
						}
					]
				},
				{
					"featureType": "poi",
					"elementType": "geometry",
					"stylers": [
						{
							"color": "#ffb9c4"
						},
						{
							"lightness": 21
						}
					]
				},
				{
					"featureType": "poi.park",
					"elementType": "geometry",
					"stylers": [
						{
							"color": "#f38597"
						},
						{
							"lightness": 21
						}
					]
				},
				{
					"featureType": "road.highway",
					"elementType": "geometry.fill",
					"stylers": [
						{
							"color": "#ffffff"
						},
						{
							"lightness": 17
						}
					]
				},
				{
					"featureType": "road.highway",
					"elementType": "geometry.stroke",
					"stylers": [
						{
							"color": "#ffffff"
						},
						{
							"lightness": 29
						},
						{
							"weight": 0.2
						}
					]
				},
				{
					"featureType": "road.arterial",
					"elementType": "geometry",
					"stylers": [
						{
							"color": "#ffffff"
						},
						{
							"lightness": 18
						}
					]
				},
				{
					"featureType": "road.local",
					"elementType": "geometry",
					"stylers": [
						{
							"color": "#ffffff"
						},
						{
							"lightness": 16
						}
					]
				},
				{
					"featureType": "transit",
					"elementType": "geometry",
					"stylers": [
						{
							"color": "#f2f2f2"
						},
						{
							"lightness": 19
						}
					]
				},
				{
					"featureType": "water",
					"elementType": "geometry",
					"stylers": [
						{
							"color": "#ff607a"
						},
						{
							"lightness": 17
						}
					]
				}
			];
					
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

				var color = '#ed3d8f';
				var currentYear = false;

				var currentDate = new Date();

				if(artwork.date == currentDate.getFullYear()){
					color = '#04d3ff';
					currentYear = true;
				}

				var infoContent = '';
				infoContent += '<div class="artwork-data'+(currentYear ? ' this-year' : '')+'">';
				infoContent +=		'<div class="col-wrapper">';
				infoContent +=			'<div class="left-col">';
				infoContent +=  			'<figure>';
				infoContent += 					'<a href="'+artwork.link+'">'+artwork.thumbnail+'</a>';
				infoContent += 				'</figure>';
				infoContent +=  			artwork.share;
				infoContent += 			'</div>';
				infoContent +=			'<div class="right-col">';
				infoContent +=  			'<div class="artwork-infos">';
				infoContent +=  				'<h4><a href="'+artwork.link+'">'+artwork.title+'</a></h4>';
				infoContent +=  				'<p class="artist"><span>'+translation_map.by+'</span> '+artwork.artist.name+'</p>';
				infoContent +=  				'<p class="date">'+artwork.date+'</p>';
				infoContent +=  			'</div>';
				infoContent += 				'<div class="artwork-desc">';
				infoContent += 					artwork.description
				infoContent += 					'<p><a href="'+artwork.link+'" class="readmore">'+translation_map.readmore+'</a></p>';
				infoContent += 				'</div>';
				infoContent += 			'</div>';
				infoContent += 		'</div>';
				infoContent += '</div>';

				var infowindow = new google.maps.InfoWindow({
					content: infoContent
				});

				infoWindows.push(infowindow);
				
				var markerMap = new google.maps.Marker({
					position: markerPos,
					map: map,
					title: artwork.title,
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