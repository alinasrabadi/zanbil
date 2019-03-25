<?php /** @var string $twitter_link */ ?>
<?php /** @var string $facebook_link */ ?>
<?php /** @var string $google_plus_link */ ?>
<?php /** @var string $telegram_link */ ?>
<?php /** @var boolean $twitter_is_active */ ?>
<?php /** @var boolean $facebook_is_active */ ?>
<?php /** @var boolean $google_plus_is_active */ ?>
<?php /** @var boolean $telegram_is_active */ ?>
<ul class="btn-group-share">
    <?php if ( $twitter_is_active ): ?>
        <li>
            <a href="<?= $twitter_link; ?>" rel="nofollow" class="btn-share btn-share--twitter" target="_blank"></a>
        </li>
    <?php endif ?>
    <?php if ( $facebook_is_active ): ?>
        <li>
            <a href="<?= $facebook_link; ?>" rel="nofollow" class="btn-share btn-share--fb" target="_blank"></a>
        </li>
    <?php endif ?>
    <?php if ( $google_plus_is_active ): ?>
        <li>
            <a href="<?= $google_plus_link; ?>" rel="nofollow" class="btn-share btn-share--gplus" target="_blank"></a>
        </li>
    <?php endif ?>
    <?php if ( $telegram_is_active ): ?>
        <li>
            <a href="<?= $telegram_link; ?>" rel="nofollow" class="btn-share btn-share--telegram" target="_blank"></a>
        </li>
    <?php endif ?>
</ul>