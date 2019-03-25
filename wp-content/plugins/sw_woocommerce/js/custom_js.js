/*
	** Custom JS for vc
	** Version: 1.0.0
*/
(function ($) {
	function RespSlider1( $id, $append, $target ){
		var $col_lg = $id.data('lg');
		var $interval = $id.data('interval');
		var $scroll = $id.data('scroll');
		var $autoplay = $id.data('autoplay');
		var $rtl = $id.data('rtl');
		var $fade 		= ( typeof( $id.data('fade') != "undefined" ) ) ? $id.data('fade') : false;
		var $dots 		= ( typeof( $id.data('dots') != "undefined" ) ) ? $id.data('dots') : false;
		$target.slick({
		  appendArrows: $append,
		  prevArrow: '<span data-role="none" class="res-button slick-prev" aria-label="previous"></span>',
		  nextArrow: '<span data-role="none" class="res-button slick-next" aria-label="next"></span>',
		  dots: $dots,
		  infinite: false,
		  speed: 500,
		  slidesToShow: $col_lg,
		  slidesToScroll: $scroll,
		  autoplay: false,
		  autoplaySpeed: $interval,
		  rtl: $rtl,			  
		});
		$id.fadeIn(1000, function() {
			$(this).removeClass("loading");
		});
	}
	window.InlineShortcodeView_woo_tab_slider = window.InlineShortcodeView.extend( {
		render: function () {
			window.InlineShortcodeView_woo_tab_slider.__super__.render.call( this );
			var $id 	= this.$el.find( '.responsive-slider' );	
			var $target = this.$el.find( '.responsive' );
			var $append = $id;
			vc.frame_window.vc_iframe.addActivity( function () {
				RespSlider1( $id, $append, $target );
			} );
			return this;
		}
	} );
	window.InlineShortcodeView_woo_tab_cat_slider = window.InlineShortcodeView.extend( {
		render: function () {
			window.InlineShortcodeView_woo_tab_cat_slider.__super__.render.call( this );
			var $el 	= this.$el.find( '.sw-woo-tab-cat' );
			var $id 	= this.$el.find( '.responsive-slider' );	
			var $target = this.$el.find( '.responsive' );
			var $append = $id;
			vc.frame_window.vc_iframe.addActivity( function () {
				RespSlider1( $id, $append, $target );
				$el.removeClass( 'loading' );
			} );
			return this;
		}
	} );
	window.InlineShortcodeView_woo_slide = window.InlineShortcodeView.extend( {
		render: function () {
			window.InlineShortcodeView_woo_slide.__super__.render.call( this );
			var $id 	= this.$el.find( '.responsive-slider' );
			var $target = this.$el.find( '.responsive' );
			var $append = $id;
			vc.frame_window.vc_iframe.addActivity( function () {
				RespSlider1( $id, $append, $target );
			} );
			return this;
		}
	} );
	window.InlineShortcodeView_woo_slide_countdown = window.InlineShortcodeView.extend( {
		render: function () {
			window.InlineShortcodeView_woo_slide_countdown.__super__.render.call( this );
			var $id 	= this.$el.find( '.responsive-slider' );
			var $target = this.$el.find( '.responsive' );
			var $append = $id;
			vc.frame_window.vc_iframe.addActivity( function () {
				RespSlider1( $id, $append, $target );
			} );
			return this;
		}
	} );
	window.InlineShortcodeView_woocat_slide = window.InlineShortcodeView.extend( {
		render: function () {
			window.InlineShortcodeView_woocat_slide.__super__.render.call( this );
			var $id 	= this.$el.find( '.responsive-slider' );
			var $target = this.$el.find( '.responsive' );
			var $append = $id;
			vc.frame_window.vc_iframe.addActivity( function () {
				RespSlider1( $id, $append, $target );
			} );
			return this;
		}
	} );
	window.InlineShortcodeView_total_sale = window.InlineShortcodeView.extend( {
		render: function () {
			window.InlineShortcodeView_total_sale.__super__.render.call( this );
			var $id 	= this.$el.find( '.responsive-slider' );
			var $target = this.$el.find( '.responsive' );
			var $append = $id;
			vc.frame_window.vc_iframe.addActivity( function () {
				RespSlider1( $id, $append, $target );
			} );
			return this;
		}
	} );
})(jQuery);