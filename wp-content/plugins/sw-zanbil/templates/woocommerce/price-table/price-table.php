<?php /** @var integer $is_admin */ ?>
<?php /** @var integer $ID */ ?>
<?php /** @var string $title */ ?>
<?php /** @var string $thumb */ ?>
<?php /** @var string $link */ ?>
<?php /** @var string $old_price */ ?>
<?php /** @var string $new_price */ ?>
<?php /** @var string $diff */ ?>
<?php /** @var string $status */ ?>
<?php /** @var string $status_title */ ?>

<div class="tcw_table_price">
    <?php echo '<div class="order-title"><h2>'.$title.'</h2></div>'; ?>
    <?php echo '<a class="list-link" href="'.$listlink.'">نمایش کامل لیست</a>'; ?>
    <table class="wp-list-table widefat striped table-bordered">
        <thead>
            <tr>
                <th class="th-product-index">#</th>
                <th class="th-product-thumb">تصویر</th>
                <th class="th-product-title">نام محصول</th>
                <th class="th-product-old-price"><?= sprintf( 'قیمت قبل (%s)', get_woocommerce_currency_symbol() ) ?></th>
                <th class="th-product-new-price"><?= sprintf( 'قیمت جدید (%s)', get_woocommerce_currency_symbol() ) ?></th>
                <th class="th-product-price-change"><?= sprintf( 'تغییر قیمت (%s)', get_woocommerce_currency_symbol() ) ?></th>
                <th class="th-product-status">تغییرات</th>
            </tr>
        </thead>
        <tbody>
            <?php if ( !empty( $ids ) && is_array( $ids ) ): ?>
                <?php foreach ( $ids as $index => $id ): ?>
                    <?php $data = tcw()->WOOCOMMERCE->PRICE_TABLE->get_product( $id ); ?>
                    <?php if ( is_wp_error( $data ) || !is_array( $data ) ) continue; ?>
                    <?php extract( $data ); ?>
                    <tr data-id="<?= $ID ?>">
                        <td class="td-product-index"><?= $index + 1; ?></td>
                        <td class="td-product-thump"><?= $thumb; ?></td>
                        <td class="td-product-title"><a href="<?= $link ?>" target="_blank"><?= $title; ?></a><?php if ( is_admin() ): ?><br>
                            <div class="row-actions">
                            <span class="edit"><a href="<?= get_edit_post_link( $ID ) ?>">ویرایش</a> | </span>
                            <span class="delete"><a href="#">پاک کردن</a></span>
                            </div><?php endif; ?>
                        </td>
                        <td class="td-product-old-price"><?= $old_price; ?></td>
                        <td class="td-product-new-price"><?= $new_price; ?></td>
                        <td class="td-product-price-change"><?= $diff; ?></td>
                        <td class="td-product-status text-center"><?= $status_icon; ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr class="no-items">
                    <td colspan="7">محصولی پیدا نشد.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>