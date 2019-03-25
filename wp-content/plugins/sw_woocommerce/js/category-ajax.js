/*
	** Category Ajax Js
	** Version: 1.0.0
*/
(function ($) {
	$(document).ready(function(){
		/* Category slider ajax */
		var el = $( '.active [data-catload=ajax]' );
		el.each( function(){
			var els = $(this);
			sw_tab_click_ajax( els );
		});		

		$('[data-catload=ajax]').on('click', function() {
			sw_tab_click_ajax( $(this) );
		});
		var el = $( '.active [data-catload=cat_tab_ajax]' );
		el.each( function(){
			var els = $(this);
			sw_tab_click_ajax( els );
		});		
		$('[data-catload=cat_tab_ajax]').on('click', function() {
			sw_tab_click_ajax( $(this) );
		});
		$('.sw-wootab-slider .tab-content').addClass( 'loading' );
		$('[data-catload=so_ajax]').on('click', function() {
			sw_tab_click_ajax( $(this) );
		});
		
		
		function sw_tab_click_ajax( element ) {			
			$('.category-ajax-slider .tab-content').addClass( 'loading' );
			var target 		= $( element.attr( 'href' ) );
			var id 				= element.attr( 'href' );
			var ltype     = element.data( 'type' );
			var layout 		= element.data( 'layout' );
			var ajaxurl   = element.data( 'ajaxurl' );
			var orderby 	= element.data( 'orderby' );
			var item_row  = element.data( 'row' );
			var sorder    = element.data( 'sorder' );
			var catid 		= element.data( 'category' );
			var number 		= element.data( 'number' );
			var columns 	= element.data( 'lg' );
			var interval 	= element.data( 'interval' );
			var scroll 		= element.data( 'scroll' );
			var autoplay 	= element.data( 'autoplay' );	
			var action = '';
			if( ltype == 'cat_ajax' ) {
				action = 'sw_category_callback';
			} else if( ltype == 'so_ajax' ) {
				action = 'sw_tab_category';
			} else if( ltype == 'tab_ajax' ) {
				action = 'sw_ajax_tab_callback';
			}
			if( target.html() == '' ){
				target.parent().addClass( 'loading' );
				var data 		= {
					action: action,
					catid: catid,
					number: number,
					target: id,
					item_row: item_row,
					sorder: sorder,
					columns: columns,
					interval: interval,
					scroll: scroll,
					autoplay: autoplay,
				};
				jQuery.post(ajaxurl, data, function(response) {
					target.html( response );
					sw_slider_ajax( target );
					target.parent().removeClass( 'loading' );
				});
			}
		}
		
		function sw_slider_ajax( target ) {
			var element 	= $(target).find( '.responsive-slider' );
			var $col_lg 	= element.data('lg');
			var $interval 	= element.data('interval');
			var $scroll 	= element.data('scroll');
			var $autoplay 	= element.data('autoplay');
			var $rtl 		= $('body').hasClass( 'rtl' );
			$target = $(target).find( '.responsive' );
			$target.slick({
			  appendArrows: $(target),
			  prevArrow: '<span data-role="none" class="res-button slick-prev" aria-label="previous"></span>',
			  nextArrow: '<span data-role="none" class="res-button slick-next" aria-label="next"></span>',
			  dots: false,
			  infinite: false,
			  speed: 500,
			  slidesToShow: $col_lg,
			  slidesToScroll: $scroll,
			  autoplay: $autoplay,
			  autoplaySpeed: $interval,
			  rtl: $rtl,			  
			});
			setTimeout(function(){
				element.removeClass("loading");
			}, 500);
		}
		
		/*
		** Categories Ajax listing
		*/
		$('.sw-ajax-categories').each( function(){
			var tparent  = $(this);
			var target 	 = $(this).find( 'a.btn-loadmore' );
			var number 	 = target.data( 'number' );
			var ajax_url = target.data( 'ajaxurl' );
			var maxpage  = target.data( 'maxpage' );
			var page		 = 1;		
			if( page >= maxpage ){
				target.addClass( 'btn-loaded' );
			}
			target.on( 'click',function(){
				if( page >= maxpage ){
					return false;
				}
				target.addClass( 'btn-loading' );
				jQuery.ajax({
					type: "POST",
					url: ajax_url,
					data: ({
						action 	: "sw_category_ajax_listing",
						number  : number,
						page 		: page,
					}),
					 success: function(data) {	
						target.removeClass('btn-loading');
						var $newItems = $(data);
						if( $newItems.length > 0 ){
							page = page + 1;
							tparent.find( '.resp-listing-container' ).append( $newItems );
							if( page >= maxpage ){
								target.addClass( 'btn-loaded' );
							}
						}else{
							target.addClass( 'btn-loaded' );
						}
					}
				});
			});
		});
	});
})(jQuery);