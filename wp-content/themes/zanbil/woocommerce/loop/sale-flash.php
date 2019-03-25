<?php
/**
 * Product loop sale flash
 *
 *
 * @see 	    https://docs.woocommerce.com/document/template-structure/
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $post, $product;

?>
<?php if ( $product->is_on_sale() ) : ?>

	<?php echo apply_filters( 'woocommerce_sale_flash', '<div class="onsale-box"><span class="onsale">' . esc_html__( 'Sale!', 'woocommerce' ) . '</span></div>', $post, $product ); ?>

<?php endif;

/* Omit closing PHP tag at the end of PHP files to avoid "headers already sent" issues. */
