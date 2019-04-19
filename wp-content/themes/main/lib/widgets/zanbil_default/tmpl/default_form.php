<?php

$order      = isset( $instance['order'] )       ? strip_tags($instance['order']) : 'ASC';
$number     = isset( $instance['number'] ) ? intval($instance['number']) : 5;
$length     = isset( $instance['length'] )      ? intval($instance['length']) : 25;

?>

<p>
	<label for="<?php echo esc_attr( $this->get_field_id('order') ); ?>"><?php esc_html_e('Order', 'zanbil')?></label>
	<br />
	<select class="widefat"
		id="<?php echo esc_attr( $this->get_field_id('order') ); ?>"
		name="<?php echo esc_attr( $this->get_field_name('order') ); ?>">
		<option value="DESC" <?php if ($order=='DESC'){?> selected="selected"
		<?php } ?>>
			<?php esc_html_e('Descending', 'zanbil')?>
		</option>
		<option value="ASC" <?php if ($order=='ASC'){?> selected="selected"
		<?php } ?>>
			<?php esc_html_e('Ascending', 'zanbil')?>
		</option>
	</select>
</p>

<p>
	<label for="<?php echo esc_attr( $this->get_field_id('number') ); ?>"><?php esc_html_e('Number of comments', 'zanbil')?></label>
	<br />
	<input class="widefat"
		id="<?php echo esc_attr( $this->get_field_id('number') ); ?>"
		name="<?php echo esc_attr( $this->get_field_name('number') ); ?>" type="text"
		value="<?php echo esc_attr($number); ?>" />
</p>

<p>
	<label for="<?php echo esc_attr( $this->get_field_id('length') ); ?>"><?php esc_html_e('Excerpt length (in words): ', 'zanbil')?></label>
	<br />
	<input class="widefat"
		id="<?php echo esc_attr( $this->get_field_id('length') ); ?>"
		name="<?php echo esc_attr( $this->get_field_name('length') ); ?>" type="text"
		value="<?php echo esc_attr($length); ?>" />
</p>
