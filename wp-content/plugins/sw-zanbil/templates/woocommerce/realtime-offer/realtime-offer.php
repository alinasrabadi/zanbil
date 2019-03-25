<?php if ( !empty( $products ) && is_array( $products ) ): ?>
    <aside class="thf-plugin-realtime-offer">
        <div class="c-box c-box--realtime-offer">
            <div class="c-realtime-offer js-realtime-offer" id="realtime-offer">
                <div class="c-realtime-offer__headline js-realtime-offer-bar"><?= $title ?></div>
                <div class="swiper-container swiper-container-horizontal js-realtime-offer-box">
                    <div class="swiper-wrapper js-realtime-offer-wrapper">
                        <?php foreach ( $products as $product ): ?>
                            <a class="swiper-slide" href="<?= $product['link'] ?>" title="<?= $product['title'] ?>">
                                <span class="c-realtime-offer__img">
                                     <img data-src="<?= $product['thumbnail'] ?>" class="swiper-lazy">
                                     <span class="swiper-lazy-preloader swiper-lazy-preloader-white"></span>
                                </span>
                                <span class="c-realtime-offer__desc">
                                    <span class="c-realtime-offer__title"><?= $product['title'] ?></span>
                                    <span class="c-realtime-offer__prince-container">
                                        <del class="c-realtime-offer__discount"><?= $product['regular_price'] ?></del>
                                        <span class="c-realtime-offer__price"><?= $product['sale_price'] ?></span>
                                     </span>
                                </span>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </aside>
<?php endif; ?>


