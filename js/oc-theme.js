// (function($) {
// 	$( document ).ready(function() {
// 		console.log( "This is js/oc-theme.js" );
// 	});
// }(jQuery));

function google_maps_callback() {
	console.log( "This is google_maps_callback()" );
	var mapDiv = $('#map');
	var zoom = mapDiv.attr('data-zoom') * 1;
	var latitude = mapDiv.attr('data-latitude');
	var longitude = mapDiv.attr('data-longitude');
	var addrLatLong = new google.maps.LatLng(latitude, longitude);

	var mapOptions = {
		zoom: zoom,
		center: addrLatLong,
		mapTypeId: google.maps.MapTypeId.ROADMAP,
		streetViewControl: false,
		zoomControl: true,
		mapTypeControl: false,
		panControl: false,
		scrollwheel: false,
	};

	var mapCanvas = document.getElementById('map');
	map = new google.maps.Map(mapCanvas, mapOptions);

	var markerpin = new google.maps.Marker({
		map: map,
		position: addrLatLong,
		animation: google.maps.Animation.DROP
	});
}


//oc-theme
$('.js-mobile').on('click', function(){
	$(this).toggleClass("js-mobile--close");
	$("html").toggleClass("js-locked");
	$(".header-nav").toggleClass("active");
});

$(function () {
	$(window).on('load resize', function () {
		if($(window).width()>767){
		
		}
		else{
			$('.header-nav__menu-arrow').on('click', function(){
				$(this).toggleClass("active");
				$(this).next("ul").slideToggle();
			});
		}
		
	});
});

jQuery(function ($) {
	$('.customer-list .customer-list__item .customer-list__ct .customer-list__ct-ttl').matchHeight();
});
$(".js-partner-logo").slick({
	slidesToShow: 1,
	slidesToScroll: 1,
	autoplay: true,
	// autoplaySpeed: 0,
	speed: 7000,
	// variableWidth: true,
	infinite: true,
});
