<?php /** @var boolean $product_id */ ?>
<div class="modal fade justify-center align-center tcw-modal" id="sharingModal" tabindex="-1" role="dialog" aria-labelledby="sharingModal">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <?php include "sharing-modal-header.php"; ?>
            </div><!-- /modal-header -->
            <div class="modal-body-wrap">
                <?php include 'sharing-modal-content.php'; ?>
            </div><!-- /modal-body -->

            <input type="hidden" name="tcw_sharing[product_id]" value="<?= $product_id ?>">
        </div><!-- /modal-content -->
    </div><!-- /modal-dialog -->
</div>