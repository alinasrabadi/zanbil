<?php
/**
 * Login Form
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $woocommerce; ?>

<form action="<?php echo get_permalink( wc_get_page_id( 'myaccount' ) ); ?>" method="post" class="login">
			<input name="form_key" type="hidden" value="lDLFLGU1hYlZ9gVL">
			<div class="block-content">
				<div class="col-reg registered-account">
					<div class="email-input">
						<input type="text" class="form-control input-text username" name="username" id="username" placeholder="نام کاربری خود را وارد کنید." />
					</div>
					<div class="pass-input">
						<input class="form-control input-text password" type="password" placeholder="کلمه عبور خود را وارد کنید." name="password" id="password" />
					</div>
					<div class="ft-link-p">
						<a href="<?php echo esc_url( wc_lostpassword_url() ); ?>" title="<?php esc_attr_e( 'Forgot your password', 'zanbil' ) ?>"><?php esc_html_e( 'Forgot your password?', 'zanbil' ); ?></a>
                        
					</div>
					<div class="actions">
						<div class="submit-login">
							<?php wp_nonce_field( 'woocommerce-login' ); ?>
			                <input type="submit" class="button btn-submit-login" name="login" value="<?php esc_html_e( 'Login', 'zanbil' ); ?>" />
                            <a href="<?php echo get_permalink( get_option('woocommerce_myaccount_page_id') ); ?>" title="<?php esc_attr_e( 'Register', 'zanbil' ) ?>" class="btn-reg-popup"><?php esc_html_e( 'Create an account', 'zanbil' ); ?></a>
						</div>	
					</div>
					
				</div>
				<div class="clearfix"></div>
			</div>
		</form>
<div class="clear"></div>
	
<?php do_action('woocommerce_after_cphone-icon-login ustomer_login_form'); ?>