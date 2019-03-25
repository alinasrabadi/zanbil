<?php /** @var boolean $product_id */ ?>
<div class="modal fade justify-center align-center tcw-modal" id="notifierModal" tabindex="-1" role="dialog" aria-labelledby="notifierModal">
    <div class="modal-dialog" role="document">
        <form class="modal-content" id="notifierModalForm" method="post">
            <div class="modal-header">
                <?php include "notifier-modal-header.php"; ?>
            </div><!-- /modal-header -->
            <div class="modal-body-wrap">
                <?php include 'notifier-modal-content.php'; ?>
            </div><!-- /modal-body -->

            <input type="hidden" name="tcw_notifier[product_id]" value="<?= $product_id ?>">
        </form><!-- /modal-content -->
    </div><!-- /modal-dialog -->
</div>