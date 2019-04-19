<?php
/**
 * The template for displaying product content in the single-product.php template
 *
 * Override this template by copying it to yourtheme/woocommerce/content-single-product.php
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     3.4.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
?>

<?php
	/**
	 * woocommerce_before_single_product hook
	 *
	 * @hooked woocommerce_show_messages - 10
	 */
	 do_action( 'woocommerce_before_single_product' );
	global $product , $post;
?>
<div itemscope itemtype="http://schema.org/Product" id="product-<?php the_ID(); ?>" <?php post_class(); ?>>
	<div class="product_detail row">
		<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 clear_xs">							
			<div class="slider_img_productd">
				<!-- woocommerce_show_product_images -->
				<?php
					/**
					 * woocommerce_show_product_images hook
					 *
					 * @hooked woocommerce_show_product_sale_flash - 10
					 * @hooked woocommerce_show_product_images - 20
					 */
					do_action( 'woocommerce_before_single_product_summary' );
				?>
			</div>							
		</div>
		<div class="col-lg-8 col-md-8 col-sm-12 col-xs-12 clear_xs">
			<div class="content_product_detail">
				<!-- woocommerce_template_single_title - 5 -->
				<!-- woocommerce_template_single_rating - 10 -->
				<!-- woocommerce_template_single_price - 20 -->
				<!-- woocommerce_template_single_excerpt - 30 -->
				<!-- woocommerce_template_single_add_to_cart 40 -->
				<?php
					/**
					 * woocommerce_single_product_summary hook
					 *
					 * @hooked woocommerce_template_single_title - 5
					 * @hooked woocommerce_template_single_price - 10
					 * @hooked woocommerce_template_single_excerpt - 20
					 * @hooked woocommerce_template_single_add_to_cart - 30
					 * @hooked woocommerce_template_single_meta - 40
					 * @hooked woocommerce_template_single_sharing - 50
					 */
					do_action( 'woocommerce_single_product_summary' );
				?>
				</div><!-- close col 8 tag in rating -->
					<div class="col-md-4">						
							<?php
								$terms = get_the_terms( $post->ID ,"product_brand");
								if( !empty( get_post_meta(get_the_ID(), 'orario', true ) ) || !empty($terms) ) {
									echo '<h3>ویژگی‌های محصول</h3><ul class="cus-style">';								
									if (is_array($terms) || is_object($terms)) {
										echo '<li>برند : ';
										foreach ( $terms as $term ) { echo '<a href="'. get_term_link( $term->term_id, 'product_brand' ).'"> '.$term->name.' </a>'; }
										echo '</li>';
									}								
							if( !empty( get_post_meta(get_the_ID(), 'orario', true ) ) ) { echo '<li>'.get_post_meta(get_the_ID(), 'orario', true ).'</li>';}
							if( !empty( get_post_meta(get_the_ID(), 'luogo_evento', true ) ) ) { echo '<li>'.get_post_meta(get_the_ID(), 'luogo_evento', true ).'</li>'; }
							if( !empty( get_post_meta(get_the_ID(), 'indirizzo', true ) ) ) { echo '<li>'.get_post_meta(get_the_ID(), 'indirizzo', true ).'</li>';}
							if( !empty( get_post_meta(get_the_ID(), 'zona', true ) ) ) { echo '<li>'.get_post_meta(get_the_ID(), 'zona', true ).'</li>'; }
							if( !empty( get_post_meta(get_the_ID(), 'contatti', true ) ) ) { echo '<li>'.get_post_meta(get_the_ID(), 'contatti', true ).'</li>'; }
							echo '</ul>';
							}							
							?>
							
					</div>
				</div><!-- close row tag in rating -->
			</div>
			<?php			
			if( class_exists( 'Dokan_Vendor' ) ) {
								$seller = get_post_field( 'post_author', $product->get_id());
								$author  = get_user_by( 'id', $seller );
								$store_info = dokan_get_store_info( $author->ID );
								$dps_processing          = get_user_meta( $post->post_author, '_dps_pt', true );
								$processing_time= $dps_processing;
								echo '<div class="zanbil-vendor"><div class="dc"><p><img src="data:image/svg+xml;utf8;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iaXNvLTg4NTktMSI/Pgo8IS0tIEdlbmVyYXRvcjogQWRvYmUgSWxsdXN0cmF0b3IgMTkuMS4wLCBTVkcgRXhwb3J0IFBsdWctSW4gLiBTVkcgVmVyc2lvbjogNi4wMCBCdWlsZCAwKSAgLS0+CjxzdmcgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB4bWxuczp4bGluaz0iaHR0cDovL3d3dy53My5vcmcvMTk5OS94bGluayIgdmVyc2lvbj0iMS4xIiBpZD0iQ2FwYV8xIiB4PSIwcHgiIHk9IjBweCIgdmlld0JveD0iMCAwIDQ4OS40IDQ4OS40IiBzdHlsZT0iZW5hYmxlLWJhY2tncm91bmQ6bmV3IDAgMCA0ODkuNCA0ODkuNDsiIHhtbDpzcGFjZT0icHJlc2VydmUiIHdpZHRoPSI1MTJweCIgaGVpZ2h0PSI1MTJweCI+CjxnPgoJPGc+CgkJPHBhdGggZD0iTTM0Ny43LDI2My43NWgtNjYuNWMtMTguMiwwLTMzLDE0LjgtMzMsMzN2NTFjMCwxOC4yLDE0LjgsMzMsMzMsMzNoNjYuNWMxOC4yLDAsMzMtMTQuOCwzMy0zM3YtNTEgICAgQzM4MC43LDI3OC41NSwzNjUuOSwyNjMuNzUsMzQ3LjcsMjYzLjc1eiBNMzU2LjcsMzQ3Ljc1YzAsNS00LjEsOS05LDloLTY2LjVjLTUsMC05LTQuMS05LTl2LTUxYzAtNSw0LjEtOSw5LTloNjYuNSAgICBjNSwwLDksNC4xLDksOVYzNDcuNzV6IiBmaWxsPSIjMDAwMDAwIi8+CgkJPHBhdGggZD0iTTQ4OS40LDE3MS4wNWMwLTIuMS0wLjUtNC4xLTEuNi01LjlsLTcyLjgtMTI4Yy0yLjEtMy43LTYuMS02LjEtMTAuNC02LjFIODQuN2MtNC4zLDAtOC4zLDIuMy0xMC40LDYuMWwtNzIuNywxMjggICAgYy0xLDEuOC0xLjYsMy44LTEuNiw1LjljMCwyOC43LDE3LjMsNTMuMyw0Miw2NC4ydjIxMS4xYzAsNi42LDUuNCwxMiwxMiwxMmg2Ni4zYzAuMSwwLDAuMiwwLDAuMywwaDkzYzAuMSwwLDAuMiwwLDAuMywwaDIyMS40ICAgIGM2LjYsMCwxMi01LjQsMTItMTJ2LTIwOS42YzAtMC41LDAtMC45LTAuMS0xLjNDNDcyLDIyNC41NSw0ODkuNCwxOTkuODUsNDg5LjQsMTcxLjA1eiBNOTEuNyw1NS4xNWgzMDUuOWw1Ni45LDEwMC4xSDM0LjkgICAgTDkxLjcsNTUuMTV6IE0zNDguMywxNzkuMTVjLTMuOCwyMS42LTIyLjcsMzgtNDUuNCwzOGMtMjIuNywwLTQxLjYtMTYuNC00NS40LTM4SDM0OC4zeiBNMjMyLDE3OS4xNWMtMy44LDIxLjYtMjIuNywzOC00NS40LDM4ICAgIHMtNDEuNi0xNi40LTQ1LjUtMzhIMjMyeiBNMjQuOCwxNzkuMTVoOTAuOWMtMy44LDIxLjYtMjIuOCwzOC00NS41LDM4QzQ3LjUsMjE3LjI1LDI4LjYsMjAwLjc1LDI0LjgsMTc5LjE1eiBNMjAxLjYsNDM0LjM1aC02OSAgICB2LTEyOS41YzAtOS40LDcuNi0xNy4xLDE3LjEtMTcuMWgzNC45YzkuNCwwLDE3LjEsNy42LDE3LjEsMTcuMXYxMjkuNUgyMDEuNnogTTQyMy4zLDQzNC4zNUgyMjUuNnYtMTI5LjUgICAgYzAtMjIuNi0xOC40LTQxLjEtNDEuMS00MS4xaC0zNC45Yy0yMi42LDAtNDEuMSwxOC40LTQxLjEsNDEuMXYxMjkuNkg2NnYtMTkzLjNjMS40LDAuMSwyLjgsMC4xLDQuMiwwLjEgICAgYzI0LjIsMCw0NS42LTEyLjMsNTguMi0zMWMxMi42LDE4LjcsMzQsMzEsNTguMiwzMXM0NS41LTEyLjMsNTguMi0zMWMxMi42LDE4LjcsMzQsMzEsNTguMSwzMWMyNC4yLDAsNDUuNS0xMi4zLDU4LjEtMzEgICAgYzEyLjYsMTguNywzNCwzMSw1OC4yLDMxYzEuNCwwLDIuNy0wLjEsNC4xLTAuMUw0MjMuMyw0MzQuMzVMNDIzLjMsNDM0LjM1eiBNNDE5LjIsMjE3LjI1Yy0yMi43LDAtNDEuNi0xNi40LTQ1LjQtMzhoOTAuOSAgICBDNDYwLjgsMjAwLjc1LDQ0MS45LDIxNy4yNSw0MTkuMiwyMTcuMjV6IiBmaWxsPSIjMDAwMDAwIi8+Cgk8L2c+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPC9zdmc+Cg==" />';
								printf( 'فروشنده: <a href="%s">%s</a>', dokan_get_store_url( $author->ID ), $author->display_name );
								 if ( !empty( $store_info['store_name'] ) ) { 
										echo '<br> نام فروشگاه : '.$store_info['store_name'].'</span>'; 
									}
								echo '</p></div>';
								
								if ( $processing_time ) { ?>
								<div class="dc"><p><img src="data:image/svg+xml;utf8;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iaXNvLTg4NTktMSI/Pgo8IS0tIEdlbmVyYXRvcjogQWRvYmUgSWxsdXN0cmF0b3IgMTkuMC4wLCBTVkcgRXhwb3J0IFBsdWctSW4gLiBTVkcgVmVyc2lvbjogNi4wMCBCdWlsZCAwKSAgLS0+CjxzdmcgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB4bWxuczp4bGluaz0iaHR0cDovL3d3dy53My5vcmcvMTk5OS94bGluayIgdmVyc2lvbj0iMS4xIiBpZD0iTGF5ZXJfMSIgeD0iMHB4IiB5PSIwcHgiIHZpZXdCb3g9IjAgMCA1MTIgNTEyIiBzdHlsZT0iZW5hYmxlLWJhY2tncm91bmQ6bmV3IDAgMCA1MTIgNTEyOyIgeG1sOnNwYWNlPSJwcmVzZXJ2ZSIgd2lkdGg9IjUxMnB4IiBoZWlnaHQ9IjUxMnB4Ij4KPGc+Cgk8Zz4KCQk8cGF0aCBkPSJNNDcwLjU3MywzNjkuODM3Yy0xLjk1MS01LjI4NS03LjgxNS03Ljk4OC0xMy4xLTYuMDM4bC0xMDYuMDcyLDM5LjEzOWMtNS4yODUsMS45NS03Ljk4OCw3LjgxNS02LjAzOCwxMy4xICAgIGMxLjUyMSw0LjEyLDUuNDIsNi42NzEsOS41Nyw2LjY3MWMxLjE3MiwwLDIuMzY1LTAuMjA0LDMuNTMtMC42MzNsMTA2LjA3Mi0zOS4xMzkgICAgQzQ2OS44MiwzODAuOTg3LDQ3Mi41MjMsMzc1LjEyMiw0NzAuNTczLDM2OS44Mzd6IiBmaWxsPSIjMDAwMDAwIi8+Cgk8L2c+CjwvZz4KPGc+Cgk8Zz4KCQk8cGF0aCBkPSJNMzM1LjMzNyw0MTkuNzM4Yy0xLjk0OS01LjI4Ni03LjgxNS03Ljk4OS0xMy4xLTYuMDM5bC00LjQ5MiwxLjY1N2MtNS4yODQsMS45NS03Ljk4OCw3LjgxNS02LjAzOCwxMy4wOTkgICAgYzEuNTIsNC4xMiw1LjQyLDYuNjcyLDkuNTcsNi42NzJjMS4xNzIsMCwyLjM2NS0wLjIwNCwzLjUzLTAuNjMzbDQuNDkyLTEuNjU3QzMzNC41ODMsNDMwLjg4NywzMzcuMjg3LDQyNS4wMjMsMzM1LjMzNyw0MTkuNzM4eiIgZmlsbD0iIzAwMDAwMCIvPgoJPC9nPgo8L2c+CjxnPgoJPGc+CgkJPHBhdGggZD0iTTQ3MC41NzMsMzI4LjI3NmMtMS45NTEtNS4yODUtNy44MTUtNy45ODYtMTMuMS02LjAzOGwtODMuODI1LDMwLjkzYy01LjI4NSwxLjk1LTcuOTg4LDcuODE1LTYuMDM4LDEzLjEgICAgYzEuNTIxLDQuMTIsNS40Miw2LjY3MSw5LjU3LDYuNjcxYzEuMTcyLDAsMi4zNjUtMC4yMDQsMy41My0wLjYzM2w4My44MjUtMzAuOTNDNDY5LjgyLDMzOS40MjYsNDcyLjUyMywzMzMuNTYxLDQ3MC41NzMsMzI4LjI3NnogICAgIiBmaWxsPSIjMDAwMDAwIi8+Cgk8L2c+CjwvZz4KPGc+Cgk8Zz4KCQk8cGF0aCBkPSJNNDcwLjU3MywyODcuMTQ3Yy0xLjk1MS01LjI4NS03LjgxNS03Ljk4Ni0xMy4xLTYuMDM4bC04My44MjUsMzAuOTNjLTUuMjg1LDEuOTUtNy45ODgsNy44MTUtNi4wMzgsMTMuMSAgICBjMS41MjEsNC4xMiw1LjQyLDYuNjcxLDkuNTcsNi42NzFjMS4xNzIsMCwyLjM2NS0wLjIwNCwzLjUzLTAuNjMzbDgzLjgyNS0zMC45M0M0NjkuODIsMjk4LjI5Nyw0NzIuNTIzLDI5Mi40MzIsNDcwLjU3MywyODcuMTQ3eiAgICAiIGZpbGw9IiMwMDAwMDAiLz4KCTwvZz4KPC9nPgo8Zz4KCTxnPgoJCTxwYXRoIGQ9Ik01MDUuMzMyLDEwMC41MjhMMjU5LjUzMSw5LjgzMmMtMi4yNzktMC44NC00Ljc4My0wLjg0LTcuMDYxLDBMNi42NjksMTAwLjUyOGwtMC4wMDIsMC4wMDggICAgYy0wLjgwNSwwLjI5OC0xLjU3OSwwLjY5Mi0yLjI5OSwxLjE5M0MxLjYzMSwxMDMuNjM3LDAsMTA2Ljc2MiwwLDExMC4wOTd2MjkxLjgwNmMwLDQuMjcxLDIuNjYxLDguMDksNi42NjgsOS41NjlsMjQ1LjgwMSw5MC42OTYgICAgYzEuMTQ2LDAuNDIzLDIuMzQxLDAuNjMsMy41MywwLjYzYzEuMjAxLDAsMi4zOTYtMC4yMTgsMy41MjgtMC42MzZsMC4wMDQsMC4wMDZsMjQ1LjgwMS05MC42OTYgICAgYzQuMDA3LTEuNDc5LDYuNjY4LTUuMjk3LDYuNjY4LTkuNTY5VjExMC4wOTdDNTEyLDEwNS44MjUsNTA5LjMzOSwxMDIuMDA3LDUwNS4zMzIsMTAwLjUyOHogTTI0NS44MDEsNDc3Ljk2NUwyMC4zOTgsMzk0Ljc5NSAgICBWMTI0LjczMmw0NS44OTYsMTYuOTM2djEyMy41NzhjMCw0LjI3MSwyLjY2MSw4LjA5LDYuNjY4LDkuNTY5bDExMC4xNTEsNDAuNjQ1YzEuMTQ2LDAuNDIzLDIuMzQxLDAuNjMsMy41MywwLjYzICAgIGMyLjA1OCwwLDQuMDk4LTAuNjIzLDUuODMzLTEuODMyYzIuNzM2LTEuOTA3LDQuMzY3LTUuMDMyLDQuMzY3LTguMzY3VjE4OS44MzhsNDguOTU2LDE4LjA2NFY0NzcuOTY1eiBNMTc2LjQ0NiwxODIuMzExdjEwOC45NDMgICAgbC04OS43NTMtMzMuMTE4VjE0OS4xOTNMMTc2LjQ0NiwxODIuMzExeiBNMTA1Ljk1NywxMzQuNDc3bDgwLjY4OC0yOS43NzNsODAuNjg4LDI5Ljc3M2wtODAuNjg4LDI5Ljc3M0wxMDUuOTU3LDEzNC40Nzd6ICAgICBNMjU2LDE4OS45MjJsLTQwLjAwMS0xNC43Nmw4NC4zMjktMzEuMTE3YzQuMDA3LTEuNDc5LDYuNjY4LTUuMjk3LDYuNjY4LTkuNTY5cy0yLjY2MS04LjA5LTYuNjY4LTkuNTY5TDE5MC4xNzYsODQuMjYzICAgIGMtMi4yNzktMC44NC00Ljc4Mi0wLjg0LTcuMDYyLDBMNzYuMzgzLDEyMy42NDdsLTM2LjcyLTEzLjU1TDI1NiwzMC4yNzNsMjE2LjMzNyw3OS44MjVMMjU2LDE4OS45MjJ6IE00OTEuNjAyLDM5NC43OTUgICAgbC0yMjUuNDAyLDgzLjE2OVYyMDcuOTAybDIyNS40MDItODMuMTdWMzk0Ljc5NXoiIGZpbGw9IiMwMDAwMDAiLz4KCTwvZz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8L3N2Zz4K" /><?php _e( 'Ready to ship in', 'dokan' ); ?><br><?php echo dokan_get_processing_time_value( $processing_time ); ?></p></div>
								<?php }
								echo'<div class="dc"><img src="data:image/svg+xml;utf8;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iaXNvLTg4NTktMSI/Pgo8IS0tIEdlbmVyYXRvcjogQWRvYmUgSWxsdXN0cmF0b3IgMTguMS4xLCBTVkcgRXhwb3J0IFBsdWctSW4gLiBTVkcgVmVyc2lvbjogNi4wMCBCdWlsZCAwKSAgLS0+CjxzdmcgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB4bWxuczp4bGluaz0iaHR0cDovL3d3dy53My5vcmcvMTk5OS94bGluayIgdmVyc2lvbj0iMS4xIiBpZD0iQ2FwYV8xIiB4PSIwcHgiIHk9IjBweCIgdmlld0JveD0iMCAwIDYxMiA2MTIiIHN0eWxlPSJlbmFibGUtYmFja2dyb3VuZDpuZXcgMCAwIDYxMiA2MTI7IiB4bWw6c3BhY2U9InByZXNlcnZlIiB3aWR0aD0iNTEycHgiIGhlaWdodD0iNTEycHgiPgo8Zz4KCTxwYXRoIGQ9Ik02MTIsMzA2LjAzNkM2MTIsMTM3LjQwNSw0NzQuNTk1LDAsMzA1Ljk2NCwwUzAsMTM3LjQwNSwwLDMwNi4wMzZjMCw5Mi44ODEsNDIuMTQsMTc2LjQzNywxMDcuNjk4LDIzMi41OTkgICBjMC43OTUsMC43OTUsMS41OSwxLjU5LDMuMTA4LDIuMzEzQzE2My44Niw1ODUuNDczLDIzMS44MDQsNjEyLDMwNi43NTksNjEyYzczLjM2NSwwLDE0MS4zMDktMjYuNTI3LDE5NC4zNjMtNjkuNDYyICAgYzMuMTA4LTAuNzk1LDUuNDkzLTMuMTA4LDcuMDExLTUuNDkzQzU3MS40NTEsNDgwLjA4OCw2MTIsMzk4LjEyMiw2MTIsMzA2LjAzNnogTTI4LjExNywzMDYuMDM2ICAgYzAtMTUzLjAxOCwxMjQuOTAxLTI3Ny45MTksMjc3LjkxOS0yNzcuOTE5czI3Ny45MTksMTI0LjkwMSwyNzcuOTE5LDI3Ny45MTljMCw3NC45NTUtMjkuNjM1LDE0Mi44MjYtNzguMDYzLDE5Mi44NDUgICBjLTcuODA2LTM2LjcxOS0zMS4yMjUtOTkuMTY5LTEwMy4wNzItMTM5LjcxOGMxNi40MDgtMjAuMzExLDI1LjczMi00Ni44MzgsMjUuNzMyLTc0Ljk1NWMwLTY3LjE0OS01NC42NDQtMTIxLjc5My0xMjEuNzkzLTEyMS43OTMgICBzLTEyMS43OTMsNTQuNjQ0LTEyMS43OTMsMTIxLjc5M2MwLDI4LjExNywxMC4xMTksNTMuODQ5LDI1LjczMiw3NC45NTVjLTcyLjQ5Nyw0MC41NDktOTUuOTE2LDEwMy0xMDIuOTI4LDEzOS43MTggICBDNTguNTQ3LDQ0OS42NTgsMjguMTE3LDM4MC45OTEsMjguMTE3LDMwNi4wMzZ6IE0yMTIuMzYsMjg0LjkzYzAtNTEuNTM2LDQyLjE0LTkzLjY3Niw5My42NzYtOTMuNjc2czkzLjY3Niw0Mi4xNCw5My42NzYsOTMuNjc2ICAgcy00Mi4xNCw5My42NzYtOTMuNjc2LDkzLjY3NlMyMTIuMzYsMzM2LjQ2NiwyMTIuMzYsMjg0LjkzeiBNMTMyLjcwNyw1MjMuMDIzYzEuNTktMjIuNjI0LDE0LjAyMi05OS4xNjksOTguMzc0LTE0Mi4xMDQgICBjMjEuMTA2LDE2LjQwOCw0Ni44MzgsMjUuNzMyLDc0Ljk1NSwyNS43MzJjMjguMTE3LDAsNTQuNjQ0LTEwLjExOSw3NS43NS0yNi41MjdjODMuNTU2LDQyLjkzNSw5Ni43ODQsMTE3Ljg5LDk5LjE2OSwxNDIuMTA0ICAgYy00Ny42MzMsMzguMjM3LTEwOC40OTMsNjEuNjU1LTE3NC4wNTIsNjEuNjU1QzI0MC40NzgsNTgzLjk1NSwxODAuMzQsNTYxLjMzMSwxMzIuNzA3LDUyMy4wMjN6IiBmaWxsPSIjMDAwMDAwIi8+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPC9zdmc+Cg==" /><a style="color: #109bb7;" href="'.get_permalink( get_page_by_title( 'فروشنده شوید' ) ).'" class="register" title="فروشنده شوید"><span>فروشنده شوید و محصولات خود را در سایت ما به فروش برسانید.</span></a></div></div>';								
							}							
							  
			
			$short_description = apply_filters( 'woocommerce_short_description', $post->post_excerpt );
			if ( $short_description ) {
					
			 echo '<div class="woocommerce-product-details__short-description">'.$short_description.'</div>'; // WPCS: XSS ok.
			 
			}
			?>
		</div>
	</div>
</div>
<div class="tabs clearfix">
	<?php
		/**
		 * woocommerce_after_single_product_summary hook
		 *
		 * @hooked woocommerce_output_product_data_tabs - 10
		 * @hooked woocommerce_output_related_products - 20
		 */
		do_action( 'woocommerce_after_single_product_summary' );
	?>
</div>

<?php if (is_active_sidebar_ZANBIL('bottom-detail-product')) { ?>
	<div class="bottom-single-product theme-clearfix">
		<?php dynamic_sidebar('bottom-detail-product'); ?>
	</div>
<?php } ?>
	
<?php do_action( 'woocommerce_after_single_product' ); ?>