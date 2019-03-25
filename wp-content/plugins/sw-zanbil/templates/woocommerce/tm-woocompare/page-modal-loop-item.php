<div class="col-lg-3 col-md-4 col-sm-6">
    <div class="product" data-id="<?= $loop->post->ID ?>" title="برای افزودن به لیست مقایسه کلیک کنید">
        <div class="product-thumbnail">
            <?php if ( has_post_thumbnail( $loop->post->ID ) ): ?>
                <?= get_the_post_thumbnail( $loop->post->ID, 'shop_catalog' ); ?>
            <?php else: ?>
                <?= '<img src="' . wc_placeholder_img_src() . '" alt="Placeholder" width="300px" height="300px" />'; ?>
            <?php endif ?>
        </div>
        <div class="product-body">
            <div class="product-title" title="<?php the_title(); ?>"><h3><?php the_title(); ?></h3></div>
        </div>
    </div>
</div>
