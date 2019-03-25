<div class="modal fade" id="addNewProductModal" tabindex="-1" role="dialog" aria-labelledby="addNewProductModal" data-categories="<?= esc_attr( implode( ',', $categories ) ? : '-1' ) ?>">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content d-flex flex-column">
            <div class="modal-header">
                <?php include "page-modal-header.php"; ?>
            </div><!-- /modal-header -->
            <div class="modal-body products-list">
                <?php include 'page-modal-loop.php'; ?>
            </div><!-- /modal-body -->
        </div><!-- /modal-content -->
    </div><!-- /modal-dialog -->
</div><?php wp_reset_query(); ?>