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
var window_type;
var $window = $(window);
if ($window.width() <= 768) {
    window_type = 'sp';
} else {
    window_type = 'pc';
}
$(window).resize(function() {
    if($window.width() <= 768){
        if( (window_type != 'sp') ){
            location.reload();
        }
    }else{
        if(window_type != 'pc'){
            location.reload();
        }
    }
});



$('.js-mobile').on('click', function(){
	$(this).toggleClass("js-mobile--close");
	$("html").toggleClass("js-locked");
	$(".header-nav").toggleClass("active");
});

$(function () {
	$(window).on('load resize', function () {
		if($(window).width()>768){
		
		}
		else{
			$('.header-nav__menu-arrow').on('click', function(){
				$(this).toggleClass("active");
				$(this).next("ul").slideToggle();
			});
			$(".js-customer-list").slick({
				slidesToShow: 1,
				slidesToScroll: 1,
				autoplay: false,
				centerMode: true,
				infinite: false,
			});
			$('.footer-menu__ttl').on('click', function(){
				$(this).toggleClass("active");
				$(this).next("ul").slideToggle();
			});
		}
		
	});
});

jQuery(function ($) {
	$('.customer-list .customer-list__item .customer-list__ct .customer-list__ct-ttl').matchHeight();
	$('.customer-list .customer-list__item .customer-list__ct').matchHeight();
});

