<?php if ( !empty( $product ) && is_array( $product ) ): ?>
    <div class="c-compare__list-value js-compare-product" data-id="<?= $product['id'] ?>">
        <div class="c-compare__img">
            <div class="c-compare__content-holder">
                <a href="<?= $product['permalink'] ?>" class="img swiper-container js-compare-product-images swiper-container-horizontal swiper-container-rtl">
                    <div class="swiper-wrapper">
                        <div class="swiper-slide swiper-slide-duplicate swiper-slide-prev">
                            <?= $product['image'] ?>
                        </div>
                    </div>
                    <!--<div class="c-compare__images-button c-compare__images-button--next"></div>-->
                    <!--<div class="c-compare__images-button c-compare__images-button--prev"></div>-->
                </a>
                <span class="title"><?= $product['title'] ?></span>
                <div class="c-price">
                    <div class="c-price__value"><?= $product['price'] ?></div>
                </div>
            </div>
            <a class="btn-primary" href="<?= $product['permalink'] ?>">مشاهده و خرید محصول</a>
        </div>
        <span class="c-compare__btn-remove js-remove-compare-product" data-id="<?= $product['id'] ?>"></span>
    </div>
<?php endif; ?>