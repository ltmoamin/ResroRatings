jQuery(document).ready(function () {

    // use sctict mode js
	"use strict";

	/*---------------------------
	 Product Filter
	 ----------------------------*/
    $('#grid').mixItUp();
    $('.filter-option li a').eq(0).addClass('active');

    $('.project-related').owlCarousel({
        autPlay: true,
        loop:true,
        margin:30,
        nav: true,
        dots: false,
        navText:[
            "<i class='fa fa-angle-left'></i>",
            "<i class='fa fa-angle-right'></i>"
        ],
        responsiveClass:true,
        responsive:{
            0:{
                items:1,
                nav:false
            },
            600:{
                items:1,
                nav:false
            },
            1000:{
                items:3,
                nav:true,
                loop:false
            }
        }
    });
    /*** =====================================
     * Team
     * =====================================***/
    $('.team-member').owlCarousel({
        loop:true,
        margin:30,
		nav: false,
        responsiveClass:true,
        responsive:{
            0:{
                items:1,
                nav:false
            },
            600:{
                items:2,
                nav:false
            },
            1000:{
                items:4,
                nav:false,
                loop:false
            }
        }
    });
    $('.home-service-three').owlCarousel({
        loop:true,
        margin:30,
		nav: false,
        responsiveClass:true,
        responsive:{
            0:{
                items:1,
                nav:false
            },
            600:{
                items:2,
                nav:false
            },
            1000:{
                items:4,
                nav:false,
                loop:false
            }
        }
    });
    $('.home-service-slide').owlCarousel({
        loop:true,
        margin:30,
        nav: false,
        responsiveClass:true,
        responsive:{
            0:{
                items:1,
                nav:false
            },
            600:{
                items:2,
                nav:false
            },
            1000:{
                items:3,
                nav:false,
                loop:false
            }
        }
    });
    $('.client-area').owlCarousel({
        loop:true,
        margin:30,
        nav: false,
        responsiveClass:true,
        responsive:{
            0:{
                items:1,
                nav:false
            },
            600:{
                items:2,
                nav:false
            },
            1000:{
                items:1,
                nav:false,
                loop:false
            }
        }
    });
    /*** =====================================
     * Team
     * =====================================***/
    $('.team-member-2').owlCarousel({
        loop:true,
        item: 2,
        singleItem: true,
        margin:30,
        nav: false,
        responsiveClass:true,
        responsive:{
            0:{
                items:1,
                nav:false
            },
            600:{
                items:2,
                nav:false
            },
            1000:{
                items:2,
                nav:false,
                loop:false
            }
        }
    });
    /*** =====================================
     * Latest News
     * =====================================***/
    $('.latest-news').owlCarousel({
        loop:true,
        margin:30,
        nav: true,
		dots: false,
        navText:[
			"<i class='fa fa-angle-left'></i>",
			"<i class='fa fa-angle-right'></i>"
		],
        responsiveClass:true,
        responsive:{
            0:{
                items:1,
                nav:false
            },
            600:{
                items:2,
                nav:false
            },
            1000:{
                items:3,
                nav:true,
                loop:false
            }
        }
    });
    $('.project-carousel').owlCarousel({
        loop:true,
        margin:0,
        nav: true,
		dots: false,
        navText:[
			"<i class='fa fa-angle-left'></i>",
			"<i class='fa fa-angle-right'></i>"
		],
        responsiveClass:true,
        responsive:{
            0:{
                items:1,
                nav:true
            },
            600:{
                items:1,
                nav:true,
                dots: false,
            },
            1000:{
                items:4,
                nav:true,
                loop:false
            }
        }
    });
    /*** =====================================
     * Brand Logo
     * =====================================***/
    $('.brand-logo').owlCarousel({
        loop:true,
        margin:0,
        nav: false,
        dots: false,
        navText:[
            "<i class='fa fa-angle-left'></i>",
            "<i class='fa fa-angle-right'></i>"
        ],
        responsiveClass:true,
        responsive:{
            0:{
                items:1,
                nav:false
            },
            600:{
                items:2,
                nav:false
            },
            1000:{
                items:5,
                nav:false,
                loop:false
            }
        }
    });
    $('.getquote-text').owlCarousel({
        autPlay: true,
        loop:true,
        margin:0,
        nav: false,
        dots: false,
        navText:[
            "<i class='fa fa-angle-left'></i>",
            "<i class='fa fa-angle-right'></i>"
        ],
        responsiveClass:true,
        responsive:{
            0:{
                items:1,
                nav:false
            },
            600:{
                items:1,
                nav:false
            },
            1000:{
                items:1,
                nav:false,
                loop:false
            }
        }
    });

    /** =====================================
    *   Search Box
    * =====================================**/
   	$('.search-box .search-icon').on('click', function(e) {
        e.preventDefault();
        $('.top-search-input-wrap').addClass('show');
   	});
   	$(".top-search-input-wrap .top-search-overlay, .top-search-input-wrap .close-icon").on('click', function(){
        $('.top-search-input-wrap').removeClass('show');
   	});

	/*** =====================================
    * Prallex
    * =====================================***/
	if( $('.testimonail-area').length ){
		$('.testimonail-area').parallax("50%", 0.3);
	}

    /** =====================================
     *  Popup Video
     * ===================================== **/
    if($('.play-icon').length){
        $('.play-icon i').magnificPopup({
            items: {
                src: 'https://www.youtube.com/watch?v=3UZgi186H38'
            },
            type: 'iframe', // this is default type
        });
    }

	/**
    * =====================================
    * wow Js
    * =====================================
    */
    var wow=new WOW( {
        boxClass: 'wow', // animated element css class (default is wow)
        animateClass: 'animated', // animation css class (default is animated)
        offset: 0, // distance to the element when triggering the animation (default is 0)
        mobile: false, // trigger animations on mobile devices (default is true)
        live: true, // act on asynchronously loaded content (default is true)
        callback: function(box) {}
        , scrollContainer: true // optional scroll container selector, otherwise use window
    }
    );
    wow.init();

	jQuery(window).on('load', function() {
		/** =====================================
	  * Preloder
	  * =====================================*/
	    $('.preloader').fadeOut();
	});

	/*** =====================================* Contact Form submission* =====================================*/
	$(function() {
		$('form#contact').on('submit', function(e) {
			e.preventDefault();
			$.post('post-contact-form.php', $(this).serialize()).done(function(data) {
				$('.comment-form').fadeOut('slow', function() {
					$('.comment-form').fadeIn('slow').html(data);
				});
			}).fail(function() {
				alert('Failed to submit. Please Try again.');
			});
		});
	});

    /**=====================================
     * Accodrion Menu
     * ===================================== **/
        function toggleIcon(e) {
            $(e.target)
                .prev('.panel-heading')
                .find(".more-less")
                .toggleClass('fa fa-minus fa fa-plus');
        }
        $('.panel-group').on('hidden.bs.collapse', toggleIcon);
        $('.panel-group').on('shown.bs.collapse', toggleIcon);

        function toggleActive(e) {
            $(e.target)
                .prev('.panel-heading')
                .find(".panel-title")
                .toggleClass('not-active active');
        }
        $('.panel-group').on('hidden.bs.collapse', toggleActive);
        $('.panel-group').on('shown.bs.collapse', toggleActive);
    $('.zoom-button').simpleLightbox();
});



