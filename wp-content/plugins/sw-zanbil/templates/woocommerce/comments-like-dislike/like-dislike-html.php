<?php
/** @var object $comment */
/** @var string $template_path */
/** @var string $user_ip */

$comment_id    = $comment->comment_ID;
$comment_ips   = get_comment_meta( $comment_id, 'tcw_comment_ips', true ) ? : array();
$comment_hint  = TCW::get_option( 'wc_comments_hint_text' );
$resistriction = TCW::get_option( 'wc_comments_like_dislike_resistriction' );
$display       = TCW::get_option( 'wc_comments_like_dislike_display' );
$order         = TCW::get_option( 'wc_comments_like_dislike_display_order' );
$icons_enabled = TCW::get_option( 'wc_comments_icons' );
$user_ip_check = in_array( $user_ip, $comment_ips ) ? 1 : 0;
$prevent_class = ( $user_ip_check == 1 || isset( $_COOKIE['tcw_comment_' . $comment_id] ) ) ? 'tcw-comment-prevent' : '';
?>
<div class="tcw-comment-like-dislike-wrap">
    <div class="tcw-comment-hint"><?= $comment_hint; ?></div>
    <?php
    /**
     * Like Dislike Order
     */
    if ( $order == 'like-dislike' ) {
        if ( $display != 'dislike_only' ) {
            include( $template_path . 'like.php' );
        }
        if ( $display != 'like_only' ) {
            include( $template_path . 'dislike.php' );
        }
    } else {
        /**
         * Dislike Like Order
         */
        if ( $display != 'dislike_only' ) {
            include( $template_path . 'dislike.php' );
        }
        if ( $display != 'like_only' ) {
            include( $template_path . 'like.php' );
        }
    }
    ?>
</div>