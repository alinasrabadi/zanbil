<?php if ( !empty( $products ) && is_array( $products ) ): ?>
    <?php foreach ( $products as $product ): ?>
        <?php include 'page-header-loop-item.php'; ?>
    <?php endforeach; ?>
<?php endif; ?>