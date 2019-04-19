<?php
/**
 * Single Product Image
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     3.5.1
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
global $post, $woocommerce, $product;
$zanbil_direction 		= zanbil_options()->getCpanelValue( 'direction' );
$sidebar_product 	= zanbil_options()->getCpanelValue( 'sidebar_product' );

?>
<div id="product_img_<?php echo esc_attr( $post->ID ); ?>" class="woocommerce-product-gallery woocommerce-product-gallery--with-images images product-images loading" data-rtl="<?php echo ( is_rtl() || $zanbil_direction == 'rtl' )? 'true' : 'false';?>" data-vertical="<?php echo ( $sidebar_product == 'full' ) ? 'true' : 'false'; ?>">
	<figure class="woocommerce-product-gallery__wrapper">
	<div class="product-images-container clearfix <?php echo ( $sidebar_product == 'full' ) ? 'thumbnail-left' : 'thumbnail-bottom'; ?>">
		<?php 
			if( has_post_thumbnail() ){ 
			$attachments = ( sw_woocommerce_version_check( '3.0' ) ) ? $product->get_gallery_image_ids() : $product->get_gallery_attachment_ids();
			$image_id 	 = get_post_thumbnail_id();
			array_unshift( $attachments, $image_id );
		?>
		<?php 
			if( $sidebar_product == 'full' ){
				do_action('woocommerce_product_thumbnails');
			}
		?>
		<!-- Image Slider -->
		<div class="slider product-responsive">
			<?php 
				foreach ( $attachments as $key => $attachment ) { 
				$full_size_image  = wp_get_attachment_image_src( $attachment, 'full' );
				$thumbnail_post   = get_post( $attachment );
				$image_title      = $thumbnail_post->post_content;

				$attributes = array(
					'class' => 'wp-post-image',
					'title'                   => $image_title,
					'data-src'                => $full_size_image[0],
					'data-large_image'        => $full_size_image[0],
					'data-large_image_width'  => $full_size_image[1],
					'data-large_image_height' => $full_size_image[2],
				);
			?>
			<div class="item-img-slider">
				<div data-thumb="<?php echo wp_get_attachment_image_url( $attachment, 'shop_thumbnail' ) ?>" class="woocommerce-product-gallery__image images">					
					<a href="<?php echo wp_get_attachment_url( $attachment ) ?>"><?php echo wp_get_attachment_image( $attachment, 'shop_single', false, $attributes ); ?></a>
				</div>
			</div>
			<?php } ?>
		</div>
		<!-- Thumbnail Slider -->
		<?php 
			if( $sidebar_product != 'full' ){
				do_action('woocommerce_product_thumbnails'); 
			}
		?>
		<?php }else{ ?>
			<div class="slider product-responsive">
				<div class="item-img-slider">
					<div data-thumb="<?php echo wp_get_attachment_image_url( $attachment, 'shop_thumbnail' ) ?>" class="woocommerce-product-gallery__image images">					
						<a href="<?php echo wp_get_attachment_url( $attachment ) ?>"><?php echo wp_get_attachment_image( $attachment, 'shop_single', false, $attributes ); ?></a>
					</div>
				</div>
			</div>
		<?php } ?>
	</div>
	</figure>	
</div>