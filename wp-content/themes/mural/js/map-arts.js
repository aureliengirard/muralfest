$(function(){
	if($('#gmap-arts').length){
		var map;
		var geocoder;
		var MY_MAPTYPE_ID = 'custom_style';

		function initialize() {
			geocoder = new google.maps.Geocoder();
			
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

			$.each(artworks, function(id, artwork){
				lat = artwork.coords.lat;
				lng = artwork.coords.lng;
		
				var markerPos = new google.maps.LatLng(lat, lng);
				
				var color = '#1097a6';
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
				infoContent += 			'</div>';
				infoContent +=			'<div class="right-col">';
				infoContent +=  			'<div class="artwork-infos">';
				infoContent +=  				'<h4><a href="'+artwork.link+'">'+artwork.title+'</a></h4>';
				infoContent +=  				'<p class="artist"><span>'+translation_map.by+'</span> <a href="'+artwork.artist.link+'">'+artwork.artist.name+'</a></p>';
				infoContent +=  				'<p class="date">'+artwork.date+'</p>';
				infoContent +=  			'</div>';
				infoContent += 				'<div class="artwork-desc">';
				infoContent += 					artwork.description
				infoContent += 					'<p><a href="'+artwork.link+'" class="readmore">'+translation_map.readmore+'</a></p>';
				infoContent +=  			artwork.share;
				infoContent += 				'</div>';
				infoContent += 			'</div>';
				infoContent += 		'</div>';
				infoContent += '</div>';
				
				var infowindow = new google.maps.InfoWindow({
					content: infoContent
				});
				
				google.maps.event.addListener(infowindow, 'domready', function () {
					twttr.widgets.load();
				})
				infoWindows.push(infowindow);
				
				var markerMap = new google.maps.Marker({
					markerid: id,
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

			function openInfoWindow(targetID){
				$.each(map.markers, function(index, val) {
					if(val.markerid == targetID){
						new google.maps.event.trigger( val, 'click' );
						var offsetMap = $('#gmap-arts').offset();

						$("html, body").animate({ scrollTop: offsetMap.top - 10 }, "slow");
						
						return false;
					}
				});
			}

			$('.artwork-list .artwork span').click(function(){
				openInfoWindow($(this).data('markerid'));
			});

			map.fitBounds(bounds);
			
		}// init

		google.maps.event.addDomListener(window, 'load', initialize);
	}
});