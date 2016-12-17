$( document ).ready( function () {
	/////////////////////
	// Initialize page //
	/////////////////////
	// Hide
	$( 'body' )
		.delay( 350 )
		.css({
			'overflow': 'visible'
		});
	$( '#status' )
		.fadeOut();
	$( '#preloader' )
		.delay( 350 )
		.fadeOut( 'slow' );
	$( ".menu" )
		.hide();

	// Fit text
	$( "#responsive_headline" ).fitText();
	$( "#wd" ).fitText( 0.72 );
	$( "#fd" ).fitText( 1.15 );

	// Custom css
	$( "#and" ).css( 'bottom', $( "#fd" ).height() );

	///////////////////////
	// Create fancyboxes //
	///////////////////////
	$( ".referenzbox" ).fancybox({
		maxWidth	: 1020,
		maxHeight	: 580,
		autoSize	: true,
		closeClick	: false,
		openEffect	: 'fade',
		closeEffect	: 'fade',
		nextEffect  : 'fade',
		prevEffect  : 'fade',
		openSpeed   : '1590',
		closeSpeed  : '1590',
		nextSpeed   : '1590',
		prevSpeed   : '1590',
		prevEasing  : 'easeOutCubic',
		nextEasing  : 'easeOutCubic',
		// scrolling   : 'hidden',
		// scrollOutside: false,
		helpers : {
			overlay : {
				closeClick: true,
				locked: false
			}
		}
	});

	/////////////////
	// ScrollMagic //
	/////////////////
	// Helper to animate skillbar growth
	function fillbar () {
		$( '.skillbar' ).each( function () {
			$( this ).find( '.skillbar-bar' ).animate(
				{
					width: $( this ).attr( 'data-percent' )
				},
				6000,
				'easeInOutQuart'
			);
		});
	}

	// Head fadeout
	var controller = new ScrollMagic.Controller();
	new ScrollMagic.Scene({
			triggerElement: '#headfadetrigger',
			duration: "100%"
		})
		// .setPin( "#headtitle" )
		.setTween(
			new TweenMax.to(
				'#headtitle, #arrowdown-wrapper',
				3.9,
				{
					opacity: 0,
					y: "25%",
					transformOrigin: "100% 50%"
				}
			)
		)
		// .addIndicators({ name: "navi fadein" })
		.addTo( controller );

	// Top bar y ZZZ Hier vll ein besserer Kommentar
	new ScrollMagic.Scene({
			triggerElement: '#headfadetrigger',
			duration: "110%"
		})
		.setTween(
			new TweenMax.to(
				'.biggi-top',
				0.9,
				{ y: "-100%" }
			)
		)
		// .addIndicators({ name: "top bar y" })
		.addTo( controller );

	new ScrollMagic.Scene({
			triggerElement: "#skillbartrigger"
		})
		.on( 'start', function () {
			fillbar();
		})
		// .addIndicators({ name: "skillbar" })
		.addTo( controller );

	////////////
	// Events //
	////////////
	// Sticky Header
	var wholevh = $( "#mwrapper" ).height();
	$( window ).scroll( function () {
		if ( $( window ).scrollTop() > wholevh - 1 ) {
			$('#navi').removeClass('sticky');
			$('#navi').addClass('sticky');

		} else {
			$('#navi').removeClass('sticky');
		}
	});

	$( ".hamburger" ).click( function () {
		$( ".menu" ).slideToggle( "slow", function () {});
	});

	$( '#nav-icon1,#nav-icon2,#nav-icon3,#nav-icon4' ).click( function () {
		$( this ).toggleClass('open');
	});

	$( 'a[href*="#"]:not([href="#"])' ).click( function () {
		if (
			location.pathname.replace( /^\//, '' ) === this.pathname.replace( /^\//, '' ) &&
			location.hostname === this.hostname
		) {
			var target = $( this.hash );
			target = (
				target.length ?
					target :
					$( '[name=' + this.hash.slice( 1 ) + ']' )
			);

			if ( target.length ) {
				$( 'html, body' ).animate(
					{
						scrollTop: target.offset().top
					},
					2300,
					'easeInOutQuart'
				);
				return false;
			}
		}
	});
});
