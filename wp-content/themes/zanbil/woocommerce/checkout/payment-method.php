<?php
/**
 * Output a single payment method
 *
 *
 * @see 	    https://docs.woocommerce.com/document/template-structure/
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     3.5.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<li class="wc_payment_method payment_method_<?php echo $gateway->id; ?>">
	
	<div class="pay-first-sec">
			<input id="payment_method_<?php echo $gateway->id; ?>" type="radio" class="input-radio" name="payment_method" value="<?php echo esc_attr( $gateway->id ); ?>" <?php checked( $gateway->chosen, true ); ?> data-order_button_text="<?php echo esc_attr( $gateway->order_button_text ); ?>" />
			<label for="payment_method_<?php echo $gateway->id; ?>"></label>		
	</div>
	
	<div class="paym-second-sec">
		<label for="payment_method_<?php echo $gateway->id; ?>">
			<?php echo $gateway->get_icon(); ?><?php echo $gateway->get_title(); ?>
		</label>
		<?php if ( $gateway->has_fields() || $gateway->get_description() ) : ?>
			<div class="payment-hr payment_box payment_method_<?php echo $gateway->id; ?>"  <?php if ( ! $gateway->chosen ) : ?>style="display:none;"<?php endif; ?>>
				<?php $gateway->payment_fields(); ?>
			</div>
		<?php endif; ?>
	</div>
	
</li>
