<div class="list-group search-result-body">
    <?php if ( !empty( $loop ) && $loop->have_posts() ) : ?>
        <?php while ( $loop->have_posts() ) : ?>
            <?php $loop->the_post(); ?>
            <a href="<?= get_permalink( $loop->post->ID ) ?>" class="list-group-item search-item"><?php the_title() ?></a>
        <?php endwhile; ?>
        <?php wp_reset_query(); ?>
    <?php elseif ( isset( $filters ) ): ?>
        <a href="<?= site_url( "?search_category={$filters['categories']}&s={$filters['search']}&search_posttype=product" ) ?>" class="list-group-item search-item search-keyword-link">نمایش همه نتایج برای
            <span class="search-keyword"><?= $filters['search'] ?></span></a>
    <?php endif; ?>
</div>
