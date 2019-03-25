<div class="c-compare js-compare-products-container">
    <ul class="c-compare__list c-compare__list--header">
        <li class="is-header">
            <?php include "page-header-loop.php"; ?>

            <div class="c-compare__list-value js-add-compare-product" data-toggle="modal" data-target="#addNewProductModal">
                <div class="c-compare__add">
                    <button href="#" class="c-compare__placement">برای افزودن کالا به لیست مقایسه کلیک کنید</button>
                    <button class="btn-primary btn-primary--gray">افزودن کالا به مقایسه</button>
                </div>
            </div>
        </li>
    </ul>
    <?php if ( !empty( $products ) && is_array( $products ) && count( $products ) > 0 ): ?>
        <ul class="c-compare-quick__list">
            <?php include "page-attr-loop.php"; ?>
        </ul>
    <?php endif; ?>
</div>