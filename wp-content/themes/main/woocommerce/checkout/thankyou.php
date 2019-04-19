<?php
/**
 * Thankyou page
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/thankyou.php.
 *
 *
 * @see 	    https://docs.woocommerce.com/document/template-structure/
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     3.2.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<div class="woocommerce-order">

	<?php if ( $order ) : ?>

		<?php if ( $order->has_status( 'failed' ) ) : ?>

			<p class="woocommerce-notice woocommerce-notice--error woocommerce-thankyou-order-failed"><?php _e( 'Unfortunately your order cannot be processed as the originating bank/merchant has declined your transaction. Please attempt your purchase again.', 'woocommerce' ); ?></p>

			<p class="woocommerce-notice woocommerce-notice--error woocommerce-thankyou-order-failed-actions">
				<a href="<?php echo esc_url( $order->get_checkout_payment_url() ); ?>" class="button pay"><?php _e( 'Pay', 'woocommerce' ) ?></a>
				<?php if ( is_user_logged_in() ) : ?>
					<a href="<?php echo esc_url( wc_get_page_permalink( 'myaccount' ) ); ?>" class="button pay"><?php _e( 'My account', 'woocommerce' ); ?></a>
				<?php endif; ?>
			</p>

		<?php else : ?>
			<div class="col-lg-6 col-md-6 col-sm-12">
			<img src="data:image/svg+xml;utf8;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iaXNvLTg4NTktMSI/Pgo8IS0tIEdlbmVyYXRvcjogQWRvYmUgSWxsdXN0cmF0b3IgMTkuMC4wLCBTVkcgRXhwb3J0IFBsdWctSW4gLiBTVkcgVmVyc2lvbjogNi4wMCBCdWlsZCAwKSAgLS0+CjxzdmcgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB4bWxuczp4bGluaz0iaHR0cDovL3d3dy53My5vcmcvMTk5OS94bGluayIgdmVyc2lvbj0iMS4xIiBpZD0iQ2FwYV8xIiB4PSIwcHgiIHk9IjBweCIgdmlld0JveD0iMCAwIDQxMy4xOTYgNDEzLjE5NiIgc3R5bGU9ImVuYWJsZS1iYWNrZ3JvdW5kOm5ldyAwIDAgNDEzLjE5NiA0MTMuMTk2OyIgeG1sOnNwYWNlPSJwcmVzZXJ2ZSIgd2lkdGg9IjY0cHgiIGhlaWdodD0iNjRweCI+CjxnPgoJPGc+CgkJPGc+CgkJCTxnPgoJCQkJPHBhdGggZD0iTTM0OS42MDcsMzgxLjQwMkgzMS43ODJWNjMuNTc3SDI2Ni4yMWwxOS44MzItMzEuNzgySDMxLjc4MkMxNC4yMzksMzEuNzk0LDAsNDYuMDMzLDAsNjMuNTc3ICAgICAgdjMxNy44MjVjMCwxNy41NDQsMTQuMjM5LDMxLjc4MiwzMS43ODIsMzEuNzgyaDMxNy44MjVjMTcuNTQ0LDAsMzEuNzgyLTE0LjIzOSwzMS43ODItMzEuNzgydi0yMDkuODZsLTMxLjc4Miw1MC45NDdWMzgxLjQwMnoiIGZpbGw9IiM0Y2FmNTAiLz4KCQkJCTxwYXRoIGQ9Ik00MDMuMTI5LDMuNjM1Yy0zLjc4Mi0yLjQ0Ny04LjAwOS0zLjYyMy0xMi4xNzMtMy42MjNjLTcuMjQ2LDAtMTQuMzM0LDMuNTYtMTguNjI1LDEwLjA3NSAgICAgIGwtMTcyLjc3LDI0OS4yMzhsLTY2LjgzOS03Ni4yNzhjLTkuMDU4LTguMzU5LTIzLjEwNi03LjgxOC0zMS40NjUsMS4yMDhjLTguMzU5LDkuMDI2LTcuNzg3LDIzLjEwNiwxLjI0LDMxLjQzM2w4MC4zNDYsOTEuNjkyICAgICAgbDEuOTM5LDIuMjI1bDIuMDM0LDIuMzJsMC44MjYsMC41MDlsMS42ODQsMS42ODRsNC4zNTQsMi4wMDJsMC45ODUsMC40NzdsNi44MzMsMS4xNzZsMC4yODYtMC4wNjQgICAgICBjNi4wMzksMC4wMzIsMTIuMDQ2LTIuMTkzLDE2LjQ2My02Ljk5MmwxLjgxMi0yLjkyNGwwLjA5NS0wLjEyN0w0MDkuNTgxLDM0LjQwMUM0MTYuMjg3LDI0LjE2Nyw0MTMuMzk1LDEwLjM3Myw0MDMuMTI5LDMuNjM1eiIgZmlsbD0iIzRjYWY1MCIvPgoJCQk8L2c+CgkJPC9nPgoJPC9nPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+Cjwvc3ZnPgo=" style="margin: 20px auto;"/>
			<h2 class="woocommerce-notice woocommerce-notice--success woocommerce-thankyou-order-received"><?php echo apply_filters( 'woocommerce_thankyou_order_received_text', __( 'Thank you. Your order has been received.', 'woocommerce' ), $order ); ?></h2>
			<p>اطلاعات سفارش خود را یادداشت کنید. همچنین فاکتور این سفارش به آدرس ایمیلی که در صورتحساب خود وارد کردید ارسال شده است. اگر در سایت عضو شده باشید میتونید از طریق حساب کاربری خود وضعیت سفارشتون رو مشاهده کنید. همچنین به وسیله آدرس ایمیل و شماره حساب در بخش پیگیری سفارش میتونید وضعیت سفارشتون رو مشاهده کنید.</p>
						<ul class="woocommerce-order-overview woocommerce-thankyou-order-details order_details">

				<li class="woocommerce-order-overview__order order">
					<span><?php _e( 'Order number:', 'woocommerce' ); ?></span>
					<strong><?php echo $order->get_order_number(); ?></strong>
				</li>

				<li class="woocommerce-order-overview__date date">
					<span><?php _e( 'Date:', 'woocommerce' ); ?></span>
					<strong><?php echo wc_format_datetime( $order->get_date_created() ); ?></strong>
				</li>

				<?php if ( is_user_logged_in() && $order->get_user_id() === get_current_user_id() && $order->get_billing_email() ) : ?>
					<li class="woocommerce-order-overview__email email">
						<span><?php _e( 'Email:', 'woocommerce' ); ?></span>
						<strong><?php echo $order->get_billing_email(); ?></strong>
					</li>
				<?php endif; ?>

				<li class="woocommerce-order-overview__total total">
					<span><?php _e( 'Total:', 'woocommerce' ); ?></span>
					<strong><?php echo $order->get_formatted_order_total(); ?></strong>
				</li>

				<?php if ( $order->get_payment_method_title() ) : ?>
					<li class="woocommerce-order-overview__payment-method method">
						<span><?php _e( 'Payment method:', 'woocommerce' ); ?></span>
						<strong><?php echo wp_kses_post( $order->get_payment_method_title() ); ?></strong>
					</li>
				<?php endif; ?>

			</ul>
			</div>
			<div class="col-lg-6 col-md-6 col-sm-12">
			<?php do_action( 'woocommerce_thankyou', $order->get_id() ); ?>
			</div>
			<div class="clear"></div>
		<?php endif; ?>

		<?php do_action( 'woocommerce_thankyou_' . $order->get_payment_method(), $order->get_id() ); ?>
		

	<?php else : ?>

		<p class="woocommerce-notice woocommerce-notice--success woocommerce-thankyou-order-received"><?php echo apply_filters( 'woocommerce_thankyou_order_received_text', __( 'Thank you. Your order has been received.', 'woocommerce' ), null ); ?></p>

	<?php endif; ?>

</div>
