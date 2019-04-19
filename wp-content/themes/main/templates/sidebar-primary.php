<?php if ( is_active_sidebar_ZANBIL('primary') ):
	$primary_span_class = 'span'.zanbil_options()->getCpanelValue('sidebar_primary_expand');
?>
<aside id="primary" class="sidebar <?php echo esc_attr($primary_span_class); ?>">
	<?php dynamic_sidebar('primary'); ?>
</aside>
<?php endif; ?>