$(function(){
	if($('#gmap-arts').length){
		var map;
		var geocoder;
		var MY_MAPTYPE_ID = 'custom_style';

		function initialize() {
			geocoder = new google.maps.Geocoder();

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

				var color = '#1f4968';
				var currentYear = false;

				var currentDate = new Date();

				if(artwork.date == currentDate.getFullYear()){
					color = '#dea1d9';
					currentYear = true;
				}

				var infoContent = '';
				infoContent += '<div class="artwork-data'+(currentYear ? ' this-year' : '')+'">';
				infoContent +=		'<div class="row">';
				infoContent +=			'<div class="col-md-4">';
				infoContent +=  			'<figure>';
				infoContent += 					'<a href="'+artwork.link+'">'+artwork.thumbnail+'</a>';
				infoContent += 				'</figure>';
				infoContent += 			'</div>';
				infoContent +=			'<div class="col-md-8">';
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

				/*
				google.maps.event.addListener(infowindow, 'domready', function () {
					twttr.widgets.load();
				})
				infoWindows.push(infowindow);
				*/

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