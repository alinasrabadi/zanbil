<?php
/**
 * Single Product title
 *
 * @see        https://docs.woocommerce.com/document/template-structure/
 * @author     WooThemes
 * @package    WooCommerce/Templates
 * @version    1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
global $product;
the_title( '<h1 class="product_title entry-title">', '<span>' );

	if ( wc_product_sku_enabled() && ( $product->get_sku() || $product->is_type( 'variable' ) ) ) : 
		echo ( $sku = $product->get_sku() ) ? $sku : ''; ?>
	<?php endif; ?>
	</span></h1>