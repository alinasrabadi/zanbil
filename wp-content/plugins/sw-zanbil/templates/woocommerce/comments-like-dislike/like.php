<?php
/** @var integer $comment_id */
/** @var string $resistriction */
/** @var string $prevent_class */
/** @var string $user_ip */
/** @var boolean $icons_enabled */
/** @var boolean $user_ip_check */

$like_count   = get_comment_meta( $comment_id, 'tcw_comment_like_count', true ) ? : '0';
$like_title   = esc_attr( TCW::get_option( 'wc_comments_like_hover_text', __( 'Like', TCW_TEXTDOMAIN ) ) );
$like_content = $like_title;
if ( $icons_enabled ) {
    $like_icon    = esc_attr( TCW::get_option( 'wc_comments_like_icon', 'fa-thumbs-up' ) );
    $like_content = '<i class="fas ' . $like_icon . '"></i>';
}

?>
<button class="tcw-comment-like-trigger tcw-comment-like-dislike-trigger <?= $prevent_class ?>" title="<?= $like_title; ?>" data-counter="<?= $like_count; ?>" data-comment-id="<?= $comment_id; ?>" data-trigger-type="like" data-restriction="<?= $resistriction; ?>" data-user-ip="<?= $user_ip; ?>" data-ip-check="<?= $user_ip_check; ?>">
    <?= $like_content ?>
</button>
