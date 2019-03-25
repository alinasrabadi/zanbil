<?php
/**
 * Plugin Custom Styles
 *
 */

defined( 'ABSPATH' ) || exit; // Exit if accessed directly


/*
 * Styles
 */
if ( !function_exists( 'tcw_get_custom_styling' ) ) {

    add_filter( 'Themesfa/CSS/after_theme_color', 'tcw_get_custom_styling' );
    function tcw_get_custom_styling( $out = '' ) {

        // Highlighted Color
        if ( $color = TCW::get_option( 'highlighted_color' ) ) {

            $bright = TCW_STYLES::light_or_dark( $color );

            $out .= "
				::-moz-selection{
					background-color: $color;
					color: $bright;
				}

				::selection{
					background-color: $color;
					color: $bright;
				}
			";
        }

        // Links Color
        if ( $color = TCW::get_option( 'links_color' ) ) {
            $out .= "
				a{
					color: $color;
				}
			";
        }

        // Links Color Hover
        if ( $color = TCW::get_option( 'links_color_hover' ) ) {
            $out .= "
				a:hover{
					color: $color;
				}
			";
        }

        // Links hover underline
        if ( TCW::get_option( 'underline_links_hover' ) ) {
            $out .= "
				#contents a:hover{
					text-decoration: underline !important;
				}
			";
        }


        // In Post links
        if ( TCW::get_option( 'post_links_color' ) ) {
            $out .= '
			#the-post .entry-content a:not(.shortc-button){
				color: ' . TCW::get_option( 'post_links_color' ) . ' !important;
			}';
        }

        if ( TCW::get_option( 'post_links_color_hover' ) ) {
            $out .= '
			#the-post .entry-content a:not(.shortc-button):hover{
				color: ' . TCW::get_option( 'post_links_color_hover' ) . ' !important;
			}';
        }


        // Go to Top Button
        if ( TCW::get_option( 'back_top_background_color' ) ) {
            $out .= '
			#go-to-top{
				background: ' . TCW::get_option( 'back_top_background_color' ) . ';
			}';
        }

        if ( TCW::get_option( 'back_top_text_color' ) ) {
            $out .= '
			#go-to-top{
				color: ' . TCW::get_option( 'back_top_text_color' ) . ';
			}';
        }


        // Site Width
        if ( TCW::get_option( 'site_width' ) && TCW::get_option( 'site_width' ) != '1200px' ) {
            $out .= '
				@media (min-width: 1200px){
				.container,
				.zanbil_breadcrumbs .container{
						width: auto;
					}
				}
			';

            if ( strpos( TCW::get_option( 'site_width' ), '%' ) !== false ) {
                $out .= '
					@media (min-width: 992px){
						.container,
						.zanbil_breadcrumbs .container{
							max-width: ' . TCW::get_option( 'site_width' ) . ';
						}
					}
				';
            } else {
                $outer_width = str_replace( 'px', '', TCW::get_option( 'site_width' ) ) + 30;
                $out         .= '
					@media (min-width: ' . $outer_width . 'px){
						.container,
						.zanbil_breadcrumbs .container {
							max-width: ' . TCW::get_option( 'site_width' ) . ';
						}
					}
				';
            }
        }

        /**
         * WooCommerce
         */
        if ( TCW_Comments_Like_Dislike::is_active() ) {
            // Comment Like Dislike Counter Color
            if ( $color = TCW::get_option( 'wc_comments_count_color' ) ) {
                $out .= "
                    .tcw-comment-like-dislike-trigger[data-counter]:before {
                        color: $color!important;
                    }           
                ";
            }

            // Comment Like Color
            if ( $color = TCW::get_option( 'wc_comments_like_color' ) ) {
                $out .= "
                    .tcw-comment-like-trigger {
                        color: $color!important;
                    }           
                ";
            }

            // Comment Dislike Color
            if ( $color = TCW::get_option( 'wc_comments_dislike_color' ) ) {
                $out .= "
                    .tcw-comment-dislike-trigger {
                        color: $color!important;
                    }           
                ";
            }
        }


        return $out;

    }
}


/*
 * Custom Theme Color
 */
if ( !function_exists( 'tcw_theme_color_css' ) ) {

    add_filter( 'Themesfa/CSS/custom_theme_color', 'tcw_theme_color_css', 1, 5 );
    function tcw_theme_color_css( $css_code, $color, $dark_color, $bright, $rgb_color ) {

        /**
         * Color
         */
        $css_code .= "
			a:hover {
				color: $color;
			}
		";


        /**
         * Misc
         */
        $css_code .= "
			::-moz-selection{
				background-color: $color;
				color: $bright;
			}

			::selection{
				background-color: $color;
				color: $bright;
			}
		";


        /**
         * WooCommerce
         */
        if ( THF_WOOCOMMERCE_IS_ACTIVE && false ) {
            $css_code .= "
				.woocommerce div.product span.price,
				.woocommerce div.product p.price,
				.woocommerce div.product div.summary .product_meta > span,
				.woocommerce div.product div.summary .product_meta > span a:hover,
				.woocommerce ul.products li.product .price ins,
				.woocommerce .woocommerce-pagination .page-numbers li a.current,
				.woocommerce .woocommerce-pagination .page-numbers li a:hover,
				.woocommerce .woocommerce-pagination .page-numbers li span.current,
				.woocommerce .woocommerce-pagination .page-numbers li span:hover,
				.woocommerce .widget_rating_filter ul li.chosen a,
				.woocommerce-MyAccount-navigation ul li.is-active a{
					color: $color;
				}

				.woocommerce span.new,
				.woocommerce a.button.alt,
				.woocommerce button.button.alt,
				.woocommerce input.button.alt,
				.woocommerce a.button.alt.disabled,
				.woocommerce a.button.alt:disabled,
				.woocommerce a.button.alt:disabled[disabled],
				.woocommerce a.button.alt.disabled:hover,
				.woocommerce a.button.alt:disabled:hover,
				.woocommerce a.button.alt:disabled[disabled]:hover,
				.woocommerce button.button.alt.disabled,
				.woocommerce button.button.alt:disabled,
				.woocommerce button.button.alt:disabled[disabled],
				.woocommerce button.button.alt.disabled:hover,
				.woocommerce button.button.alt:disabled:hover,
				.woocommerce button.button.alt:disabled[disabled]:hover,
				.woocommerce input.button.alt.disabled,
				.woocommerce input.button.alt:disabled,
				.woocommerce input.button.alt:disabled[disabled],
				.woocommerce input.button.alt.disabled:hover,
				.woocommerce input.button.alt:disabled:hover,
				.woocommerce input.button.alt:disabled[disabled]:hover,
				.woocommerce .widget_price_filter .ui-slider .ui-slider-range{
					background-color: $color;
					color: $bright;
				}

				.woocommerce div.product #product-images-slider-nav .tie-slick-slider .slide.slick-current img{
					border-color: $color;
				}

				.woocommerce a.button:hover,
				.woocommerce button.button:hover,
				.woocommerce input.button:hover,
				.woocommerce a.button.alt:hover,
				.woocommerce button.button.alt:hover,
				.woocommerce input.button.alt:hover{
					background-color: $dark_color;
				}
			";
        }

        return $css_code;
    }
}