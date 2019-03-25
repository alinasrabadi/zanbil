<?php 
$zanbil_header_style = zanbil_options()->getCpanelValue('header_style');
if($zanbil_header_style !='style1') { ?>
<?php do_action( 'before' ); ?>
<?php if ( class_exists( 'WooCommerce' ) ) { ?>
<?php global $woocommerce; ?>
<div class="top-login">
	<?php if ( ! is_user_logged_in() ) {  ?>

			<?php echo ' <a href="javascript:void(0);" data-toggle="modal" data-target="#login_form"><span>'.esc_html__('Login', 'zanbil').'</span></a> '; ?>
	<?php } else{?>
		<div class="div-logined">
					<?php 
						$user_id = get_current_user_id();
						$user_info = get_userdata( $user_id );	
					?>
					<a href="<?php echo wp_logout_url( home_url('/') ); ?>" title="<?php esc_attr_e( 'Logout', 'zanbil' ) ?>"><?php esc_html_e('Logout', 'zanbil'); ?></a>
		</div>
	<?php } ?>
</div>
<?php }  } ?>
