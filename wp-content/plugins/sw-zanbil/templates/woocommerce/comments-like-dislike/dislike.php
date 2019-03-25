<?php
/** @var integer $comment_id */
/** @var string $resistriction */
/** @var string $prevent_class */
/** @var string $user_ip */
/** @var boolean $user_ip_check */
/** @var boolean $icons_enabled */

$dislike_count   = get_comment_meta( $comment_id, 'tcw_comment_dislike_count', true ) ? : '0';
$dislike_title   = esc_attr( TCW::get_option( 'wc_comments_dislike_hover_text', __( 'Dislike', TCW_TEXTDOMAIN ) ) );
$dislike_content = $dislike_title;

if ( $icons_enabled ) {
    $dislike_icon    = esc_attr( TCW::get_option( 'wc_comments_dislike_icon', 'fa-thumbs-down' ) );
    $dislike_content = '<i class="fas ' . $dislike_icon . '"></i>';
}
?>
<button class="tcw-comment-dislike-trigger tcw-comment-like-dislike-trigger <?= $prevent_class ?>" title="<?= $dislike_title; ?>" data-counter="<?= $dislike_count; ?>" data-comment-id="<?= $comment_id; ?>" data-trigger-type="dislike" data-restriction="<?= $resistriction; ?>" data-user-ip="<?= $user_ip; ?>" data-ip-check="<?= $user_ip_check; ?>">
    <?= $dislike_content; ?>
</button>
