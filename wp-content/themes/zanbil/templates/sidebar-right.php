<?php if ( is_active_sidebar_ZANBIL('right') ):
	$right_span_class = 'col-lg-'.zanbil_options()->getCpanelValue('sidebar_right_expand');
	$right_span_class .= ' col-md-'.zanbil_options()->getCpanelValue('sidebar_right_expand_md');
	$right_span_class .= ' col-sm-'.zanbil_options()->getCpanelValue('sidebar_right_expand_sm');
?>
<aside id="right" class="sidebar <?php echo esc_attr($right_span_class); ?>">
	<?php dynamic_sidebar('right'); ?>
</aside>
<?php endif; ?>