<?php
global $woocommerce;
$number    		= isset( $instance['numberposts'] ) ? intval($instance['numberposts']) : 5;
$length    		= isset( $instance['length'] ) ? intval($instance['length']) : 25;
?>
<p>
	<label for="<?php echo esc_attr( $this->get_field_id('numberposts') ); ?>"><?php esc_html_e('Number of Posts', 'zanbil')?></label>
	<br />
	<input class="widefat"
		id="<?php echo esc_attr( $this->get_field_id('numberposts') ); ?>"name="<?php echo esc_attr( $this->get_field_name('numberposts') ); ?>" type="text"
		value="<?php echo esc_attr($number); ?>" />
</p>
