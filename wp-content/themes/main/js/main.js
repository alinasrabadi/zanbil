(function($) {
	"use strict";
	/* Add Click On Ipad */
	$(window).resize(function(){
		var $width = $(this).width();
		if( $width < 1199 ){
			$( '.primary-menu .nav .dropdown-toggle'  ).each(function(){
				$(this).attr('data-toggle', 'dropdown');
			});
		}
	});
	jQuery(window).load(function() {
		/* Masonry Blog */
		$('body').find('.blog-content-grid').isotope({ 
			layoutMode : 'masonry'
		});
	});

    jQuery('.phone-icon-search').on('click', function(){
		/*alert("The paragraph was clicked.");*/
        jQuery('.sm-serachbox-pro').toggle("slide");
    });
	$(document).ready(function(){
		/* Quickview */
		$('.fancybox').fancybox({
			'width'     : 850,
			'height'   : '500',
			'autoSize' : false,
			afterShow: function() {
				$( '.quickview-container .product-images' ).each(function(){
					var $id = this.id;
					var $rtl = $('body').hasClass( 'rtl' );
					var $img_slider = $( '#' + $id + ' .product-responsive');
					var $thumb_slider = $( '#' + $id + ' .product-responsive-thumbnail' )
					$img_slider.slick({
						slidesToShow: 1,
						slidesToScroll: 1,
						fade: true,
						arrows: false,
						rtl: $rtl,
						asNavFor: $thumb_slider
					});
					$thumb_slider.slick({
						slidesToShow: 3,
						slidesToScroll: 1,
						asNavFor: $img_slider,
						arrows: true,
						focusOnSelect: true,
						rtl: $rtl,
						responsive: [				
							{
							  breakpoint: 360,
							  settings: {
								slidesToShow: 2    
							  }
							}
						  ]
					});

					var el = $(this);
					setTimeout(function(){
						el.removeClass("loading");
					}, 1000);

				});
			}
		});
		/* Slider Image */
		$( '.product-images' ).each(function(){
			var $id 			= this.id;
			var $rtl 			= $(this).data('rtl');
			var $vertical		= $(this).data('vertical');
			var $img_slider 	= $( '#' + $id + ' .product-responsive');
			var $thumb_slider 	= $( '#' + $id + ' .product-responsive-thumbnail' );
			$img_slider.slick({
				slidesToShow: 1,
				slidesToScroll: 1,
				fade: true,
				arrows: false,
				rtl: $rtl,
				asNavFor: $thumb_slider
			});
			$thumb_slider.slick({
				slidesToShow: 3,
				slidesToScroll: 1,
				asNavFor: $img_slider,
				arrows: true,
				rtl: $rtl,
				vertical: $vertical,
				verticalSwiping: $vertical,
				focusOnSelect: true,
				responsive: [	
				    {
					  breakpoint: 1199,
					  settings: {
						slidesToShow: 3
					  }
					},			
					{
					  breakpoint: 360,
					  settings: {
						slidesToShow: 2    
					  }
					}
				  ]
			});
			var el = $(this);
			setTimeout(function(){
				el.removeClass("loading");
			}, 1000);
		});
	});

var mobileHover = function () {
    $('*').on('touchstart', function () {
        $(this).trigger('hover');
    }).on('touchend', function () {
        $(this).trigger('hover');
    });
};

mobileHover();

    jQuery('.product-categories')
        .find('li:gt(5)') /*you want :gt(4) since index starts at 0 and H3 is not in LI */
        .hide()
        .end()
        .each(function(){
            if($(this).children('li').length > 5){ //iterates over each UL and if they have 5+ LIs then adds Show More...
                $(this).append(
                    $('<li><a>See more   +</a></li>')
                        .addClass('showMore')
                        .on('click',function(){
                            if($(this).siblings(':hidden').length > 0){
                                $(this).html('<a>See less   -</a>').siblings(':hidden').show(400);
                            }else{
                                $(this).html('<a>See more   +</a>').show().siblings('li:gt(5)').hide(400);
                            }
                        })
                );
            }
        });
    /*Form search iP*/




    jQuery('a.phone-icon-menu').on('click', function(){
       var temp = jQuery('.navbar-inner.navbar-inverse').toggle( "slide" );
	   $(this).toggleClass('active');
    });
	$('.zanbil-tooltip').tooltip();
	/* fix accordion heading state */
	$('.accordion-heading').each(function(){
		var $this = $(this), $body = $this.siblings('.accordion-body');
		if (!$body.hasClass('in')){
			$this.find('.accordion-toggle').addClass('collapsed');
		}
	});
	

	/* twice click */
	$(document).on('click.twice', '.open [data-toggle="dropdown"]', function(e){
		var $this = $(this), href = $this.attr('href');
		e.preventDefault();
		window.location.href = href;
		return false;
	});

    $('#cpanel').collapse();

    $('#cpanel-reset').on('click', function(e) {

    	if (document.cookie && document.cookie != '') {
    		var split = document.cookie.split(';');
    		for (var i = 0; i < split.length; i++) {
    			var name_value = split[i].split("=");
    			name_value[0] = name_value[0].replace(/^ /, '');

    			if (name_value[0].indexOf(cpanel_name)===0) {
    				$.cookie(name_value[0], 1, { path: '/', expires: -1 });
    			}
    		}
    	}

    	location.reload();
    });

	$('#cpanel-form').on('submit', function(e){
		var $this = $(this), data = $this.data(), values = $this.serializeArray();

		var checkbox = $this.find('input:checkbox');
		$.each(checkbox, function() {

			if( !$(this).is(':checked') ) {
				name = $(this).attr('name');
				name = name.replace(/([^\[]*)\[(.*)\]/g, '$1_$2');

				$.cookie( name , 0, { path: '/', expires: 7 });
			}

		})

		$.each(values, function(){
			var $nvp = this;
			var name = $nvp.name;
			var value = $nvp.value;

			if ( !(name.indexOf(cpanel_name + '[')===0) ) return ;

			name = name.replace(/([^\[]*)\[(.*)\]/g, '$1_$2');

			$.cookie( name , value, { path: '/', expires: 7 });

		});

		location.reload();

		return false;

	});

	$('a[href="#cpanel-form"]').on( 'click', function(e) {
		var parent = $('#cpanel-form'), right = parent.css('right'), width = parent.width();

		if ( parseFloat(right) < -10 ) {
			parent.animate({
				right: '0px',
			}, "slow");
		} else {
			parent.animate({
				right: '-' + width ,
			}, "slow");
		}

		if ( $(this).hasClass('active') ) {
			$(this).removeClass('active');
		} else $(this).addClass('active');

		e.preventDefault();
	});
/*Product listing select box*/
	jQuery('.view-catelog .orderby .current-li a').html(jQuery('.view-catelog .orderby ul li.current a').html());
	jQuery('.view-catelog .sort-count .current-li a').html(jQuery('.view-catelog .sort-count ul li.current a').html());
/*currency Selectbox*/
	$('.currency_switcher li a').on('click', function(){
		var $current = $(this).attr('data-currencycode');
		jQuery('.currency_w > li > a').html($current);
	});
/*language*/
	var $current ='';
	$('#lang_sel ul > li > ul li a').on('click',function(){
	 //console.log($(this).html());
	 $current = $(this).html();
	 $('#lang_sel ul > li > a.lang_sel_sel').html($current);
	  $.cookie('lang_select_zanbil', $current, { expires: 1, path: '/'}); 
	});
	if( $.cookie('lang_select_zanbil') && $.cookie('lang_select_zanbil').length > 0 ) {
	 $('#lang_sel ul > li > a.lang_sel_sel').html($.cookie('lang_select_zanbil'));
	}

	$('#lang_sel ul > li.icl-gr').click(function(){
		$('#lang_sel ul > li.icl-en').removeClass( 'active' );
		$(this).addClass( 'active' );
		$.cookie( 'zanbil_lang_en' , 1, { path: '/', expires: 1 });
	});
	$('#lang_sel ul > li.icl-en').click(function(){
		$('#lang_sel ul > li.icl-gr').removeClass( 'active' );
		$(this).addClass( 'active' );
		$.cookie( 'zanbil_lang_en' , 0, { path: '/', expires: -1 });
	});
	
	var zanbil_Lang = $.cookie( 'zanbil_lang_en' );
	if( zanbil_Lang == null ){
		$('#lang_sel ul > li.icl-en').addClass( 'active' );
		$('#lang_sel ul > li.icl-gr').removeClass( 'active' );
	}else{
		$('#lang_sel ul > li.icl-en').removeClass( 'active' );
		$('#lang_sel ul > li.icl-gr').addClass( 'active' );
	}
/*language v2*/
	var $current ='';
	$('#lang_sel_v2 ul > li > ul li a').on('click',function(){
	 //console.log($(this).html());
	 $current = $(this).html();
	 $('#lang_sel_v2 ul > li > a.lang_sel_sel').html($current);
	  $.cookie('lang_select_zanbil', $current, { expires: 1, path: '/'}); 
	});
	if( $.cookie('lang_select_zanbil') && $.cookie('lang_select_zanbil').length > 0 ) {
	 $('#lang_sel_v2 ul > li > a.lang_sel_sel').html($.cookie('lang_select_zanbil'));
	}

	$('#lang_sel_v2 ul > li.icl-gr').click(function(){
		$('#lang_sel_v2 ul > li.icl-en').removeClass( 'active' );
		$(this).addClass( 'active' );
		$.cookie( 'zanbil_lang_en' , 1, { path: '/', expires: 1 });
	});
	$('#lang_sel_v2 ul > li.icl-en').click(function(){
		$('#lang_sel_v2 ul > li.icl-gr').removeClass( 'active' );
		$(this).addClass( 'active' );
		$.cookie( 'zanbil_lang_en' , 0, { path: '/', expires: -1 });
	});
	
	var zanbil_Lang = $.cookie( 'zanbil_lang_en' );
	if( zanbil_Lang == null ){
		$('#lang_sel_v2 ul > li.icl-en').addClass( 'active' );
		$('#lang_sel_v2 ul > li.icl-gr').removeClass( 'active' );
	}else{
		$('#lang_sel_v2 ul > li.icl-en').removeClass( 'active' );
		$('#lang_sel_v2 ul > li.icl-gr').addClass( 'active' );
	}
	/*------ clear header ------*/
	$( '.zanbil-logo' ).on('click', function(){
		$.cookie("zanbil_header_style", null, { path: '/' });
		$.cookie("zanbil_footer_style", null, { path: '/' });
	});


	jQuery(document).ready(function(){
		var currency_show = jQuery('ul.currency_switcher li a.active').html();
		jQuery('.currency_to_show').html(currency_show);	
	}); 
/*end lavalamp*/
	jQuery(function($){
	// back to top
	$("#zanbil-totop").hide();
	$(function () {
		var wh = $(window).height();
		var whtml = $(document).height();
		$(window).scroll(function () {
			if ($(this).scrollTop() > whtml/10) {
					$('#zanbil-totop').fadeIn();
				} else {
					$('#zanbil-totop').fadeOut();
				}
			});
		$('#zanbil-totop').click(function () {
			$('body,html').animate({
				scrollTop: 0
			}, 800);
			return false;
			});
	});
	/* end back to top */
	}); 
	jQuery(document).ready(function(){
  jQuery('.wpcf7-form-control-wrap').on('hover', function(){
   $(this).find('.wpcf7-not-valid-tip').css('display', 'none');
  });
 });
/** width tab **/
if(($(window).width() > 1199)) {
	 var dem = $('.vc_tta-tabs-list .vc_tta-tab').length;
	 var widthul = Math.ceil($(".vc_tta-tabs-container").outerWidth());
	 var widthli = (widthul / dem ) ;
		 $('.vc_tta-tabs-list').find('.vc_tta-tab').css({
			    width: (widthli)
			})
	$( "a.add_to_cart_button" ).attr( "title", "افزودن به سبد" );
	$( ".add_to_wishlist" ).attr( "title", "افزودن به مورد علاقه ها" );
	$( ".compare-button" ).attr( "title", "مقایسه" );
	$( ".group" ).attr( "title", "نمایش سریع" );

}
 /*fix js */
 $('.wpb_map_wraper').on('click', function () {
    $('.wpb_map_wraper iframe').css("pointer-events", "auto");
});

$( ".wpb_map_wraper" ).on('mouseleave', function() {
  $('.wpb_map_wraper iframe').css("pointer-events", "none"); 
});
/*Remove tag p colections*/
$( ".collections .tab-content .tab-pane" ).find('p:first-child').remove();

/*remove tag p*/
$( ".collections .tab-pane " ).find( "p" ).remove();
/*add title to button*/

	$("a.compare").attr('title', 'مقایسه');
	$(".yith-wcwl-add-button a").attr('title', 'افزودن به علاقه مندی ها');
	$("a.fancybox").attr('title', 'نمایش سریع');
	
$('#myTabs a').hover(function (e) {
	e.preventDefault()
	$(this).tab('show')
})
	/*remove loading*/
	$(".sw-woo-tab").fadeIn(300, function() {
		$(this).removeClass("loading");
	});
	$(".sw-woo-tab-cat-resp").fadeIn(300, function() {
		$(this).removeClass("loading");
	});
	$(".responsive-slider").fadeIn(300, function() {
		$(this).removeClass("loading");
	});

}(jQuery));
