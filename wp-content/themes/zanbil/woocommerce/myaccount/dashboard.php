<?php
/**
 * My Account Dashboard
 *
 * Shows the first intro screen on the account dashboard.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/dashboard.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see         https://docs.woocommerce.com/document/template-structure/
 * @author      WooThemes
 * @package     WooCommerce/Templates
 * @version     2.6.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
$firstname = get_user_meta ( $current_user->ID, 'first_name', true );
$lastname = get_user_meta ( $current_user->ID, 'last_name', true );
if ( $firstname && $lastname ) {
	$user_name = $firstname . ' ' . $lastname;
} else {
	$user_name = $current_user->display_name;
}
$user_email = $current_user->user_email;
$user_register = strtotime ($current_user->user_registered);
$user_mobile = get_user_meta ( $current_user->ID, 'billing_mobile', true ) ? get_user_meta ( $current_user->ID, 'mobile', true ) : '--' ;
$user_phone = get_user_meta ( $current_user->ID, 'billing_phone', true ) ? get_user_meta ( $current_user->ID, 'billing_phone', true ) : '--';
$user_city = get_user_meta ( $current_user->ID, 'billing_city', true ) ? get_user_meta ( $current_user->ID, 'billing_city', true ) : '--';
$user_address = get_user_meta ( $current_user->ID, 'billing_address_1', true ) ? get_user_meta ( $current_user->ID, 'billing_address_1', true ) : '--';
$user_postcode = get_user_meta ( $current_user->ID, 'billing_postcode', true ) ? get_user_meta ( $current_user->ID, 'billing_postcode', true ) : '--';


?>

    <div class="account-box">
        <div class="user-data">
            <table>
                <tbody>
                    <tr>
                        <td>
                            <span class="title">نام : </span>
                            <span class="value">
								<?php echo $user_name; ?>
                            </span>
                        </td>
                        <td>
                            <span class="title">آدرس ایمیل : </span>
                            <span class="value">
                                <?php echo $user_email; ?>
                            </span>
                        </td>
						<td>
                            <span class="title">موبایل : </span>
                            <span class="value ltr">
                                 <?php echo $user_mobile; ?>
                            </span>
                        </td>
						<td>
                            <span class="title">تاریخ عضویت : </span>
                            <span class="value">
                                <?php echo date_i18n ( 'j F Y', $user_register); ?>
                            </span>
                        </td>
                    </tr>
                    <tr>
						<td>
                            <span class="title">تلفن ثابت : </span>
                            <span class="value ltr">
                                <?php echo $user_phone; ?>
                            </span>
                        </td>
                        <td>
                            <span class="title">شهر : </span>
                            <span class="value">
                                <?php echo $user_city; ?>
                            </span>
                        </td>
						<td>
                            <span class="title">کد پستی : </span>
                            <span class="value">
                                <?php echo $user_postcode; ?>
                            </span>
                        </td>
                    </tr>
					<tr>
						<td colspan="4">
                            <span class="title">آدرس : </span>
                            <span class="value">
                                <?php echo $user_address; ?>
                            </span>
                        </td>
					</tr>
                </tbody>
            </table>
        </div>
        
        <div style="text-align: left;">
            <a href="<?php echo wc_get_endpoint_url( 'edit-account' );?>" style="padding: 6px 12px;background-color: #ff637d;color: #fff;">
				<span>ویرایش حساب کاربری</span>
            </a>
            <a href="<?php echo wc_get_endpoint_url( 'edit-address' );?>" style="margin-right: 20px;padding: 6px 12px;background-color: #1ca2bd;color: #fff;">                            
				<span>ویرایش آدرس</span>
            </a>
        </div>
    </div>

<?php $my_orders_columns = apply_filters( 'woocommerce_my_account_my_orders_columns', array(
	'order-number'  => __( 'Order', 'woocommerce' ),
	'order-date'    => __( 'Date', 'woocommerce' ),
	'order-status'  => __( 'Status', 'woocommerce' ),
	'order-total'   => __( 'Total', 'woocommerce' ),
	'order-actions' => '&nbsp;',
) );

$customer_orders = get_posts( apply_filters( 'woocommerce_my_account_my_orders_query', array(
	'numberposts' => 4,
	'meta_key'    => '_customer_user',
	'meta_value'  => get_current_user_id(),
	'post_type'   => wc_get_order_types( 'view-orders' ),
	'post_status' => array_keys( wc_get_order_statuses() ),
) ) );
if ( $customer_orders ) : ?>
<div class="account-box">
	<h2> آخرین سفارشات ثبت شده برای شما :</h2>

	<table class="shop_table shop_table_responsive my_account_orders">

		<thead>
			<tr>
				<?php foreach ( $my_orders_columns as $column_id => $column_name ) : ?>
					<th class="<?php echo esc_attr( $column_id ); ?>"><span class="nobr"><?php echo esc_html( $column_name ); ?></span></th>
				<?php endforeach; ?>
			</tr>
		</thead>

		<tbody>
			<?php foreach ( $customer_orders as $customer_order ) :
				$order      = wc_get_order( $customer_order );
				$item_count = $order->get_item_count();
				?>
				<tr class="order">
					<?php foreach ( $my_orders_columns as $column_id => $column_name ) : ?>
						<td class="<?php echo esc_attr( $column_id ); ?>">
							<?php if ( has_action( 'woocommerce_my_account_my_orders_column_' . $column_id ) ) : ?>
								<?php do_action( 'woocommerce_my_account_my_orders_column_' . $column_id, $order ); ?>

							<?php elseif ( 'order-number' === $column_id ) : ?>
								<a href="<?php echo esc_url( $order->get_view_order_url() ); ?>">&nbsp;
									<?php echo _x( '#', 'hash before order number', 'woocommerce' ) . $order->get_order_number(); ?>
								</a>

							<?php elseif ( 'order-date' === $column_id ) : ?>
								&nbsp;<time datetime="<?php echo esc_attr( $order->get_date_created()->date( 'c' ) ); ?>"><?php echo esc_html( wc_format_datetime( $order->get_date_created() ) ); ?></time>

							<?php elseif ( 'order-status' === $column_id ) : ?>
								<?php echo esc_html( wc_get_order_status_name( $order->get_status() ) ); ?>

							<?php elseif ( 'order-total' === $column_id ) : ?>
								<?php
								/* translators: 1: formatted order total 2: total order items */
								printf( _n( '%1$s for %2$s item', '%1$s for %2$s items', $item_count, 'woocommerce' ), $order->get_formatted_order_total(), $item_count );
								?>

							<?php elseif ( 'order-actions' === $column_id ) : ?>
								<?php
									$actions = array(
										'pay'    => array(
											'url'  => $order->get_checkout_payment_url(),
											'name' => __( 'Pay', 'woocommerce' ),
										),
										'view'   => array(
											'url'  => $order->get_view_order_url(),
											'name' => __( 'View', 'woocommerce' ),
										),
										'cancel' => array(
											'url'  => $order->get_cancel_order_url( wc_get_page_permalink( 'myaccount' ) ),
											'name' => __( 'Cancel', 'woocommerce' ),
										),
									);

									if ( ! $order->needs_payment() ) {
										unset( $actions['pay'] );
									}

									if ( ! in_array( $order->get_status(), apply_filters( 'woocommerce_valid_order_statuses_for_cancel', array( 'pending', 'failed' ), $order ) ) ) {
										unset( $actions['cancel'] );
									}

									if ( $actions = apply_filters( 'woocommerce_my_account_my_orders_actions', $actions, $order ) ) {
										foreach ( $actions as $key => $action ) {
											echo '<a href="' . esc_url( $action['url'] ) . '" class="button ' . sanitize_html_class( $key ) . '">' . esc_html( $action['name'] ) . '</a>';
										}
									}
								?>
							<?php endif; ?>
						</td>
					<?php endforeach; ?>
				</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
</div>
<?php endif; ?>

<?php
	/**
	 * My Account dashboard.
	 *
	 * @since 2.6.0
	 */
	do_action( 'woocommerce_account_dashboard' );

	/**
	 * Deprecated woocommerce_before_my_account action.
	 *
	 * @deprecated 2.6.0
	 */
	do_action( 'woocommerce_before_my_account' );

	/**
	 * Deprecated woocommerce_after_my_account action.
	 *
	 * @deprecated 2.6.0
	 */
	do_action( 'woocommerce_after_my_account' );

/* Omit closing PHP tag at the end of PHP files to avoid "headers already sent" issues. */
