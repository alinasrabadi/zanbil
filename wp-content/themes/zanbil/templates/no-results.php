<?php if (!have_posts()) : ?>
	<div class="alert alert-warning alert-dismissible" role="alert">
		<a class="close" data-dismiss="alert">&times;</a>
		<p><?php esc_html_e('Sorry, no results were found.', 'zanbil'); ?></p>
	</div>
<?php endif; ?>