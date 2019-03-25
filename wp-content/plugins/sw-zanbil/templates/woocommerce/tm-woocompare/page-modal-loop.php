<?php if ( !empty($loop) && $loop->have_posts() ) : ?>
    <div class="row">
        <?php while ( $loop->have_posts() ) : ?>
            <?php $loop->the_post(); ?>
            <?php global $product; ?>
            <?php include 'page-modal-loop-item.php'; ?>
        <?php endwhile; ?>
        <?php wp_reset_query(); ?>
    </div>
<?php else : ?>
    <p>موردی یافت نشد</p>
<?php endif; ?>