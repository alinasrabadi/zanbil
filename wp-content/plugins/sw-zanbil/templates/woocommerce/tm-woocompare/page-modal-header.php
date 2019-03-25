<div class="d-flex align-stretch">
    <div class="modal-title ">
        <form action="#" method="post">
            <div class="input-group input-group-lg input-group-with-select d-flex align-center">
                <input type="text" class="form-control" name="search-product" placeholder="کالای مورد نظر خود را جستجو کنید...">
                <span class="input-group-addon d-flex align-center modal-spinner"><?= thf_icon( 'search' ) ?></span>
                <select class="form-control" name="product-brand">
                    <option value="-1">فیلتر</option>
                    <?= $brand_options_html ?>
                </select>
            </div><!-- /input-group -->
        </form>
    </div><!-- /modal-title -->
    <div class="c-compare">
        <button type="button" class="c-compare__btn-remove" data-toggle="tooltip" data-dismiss="modal" aria-label="Close" title="بستن"></button>
    </div><!-- /c-compare -->
</div>