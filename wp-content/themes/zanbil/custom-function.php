<?php

add_filter( 'widget_text', 'do_shortcode' );

// Admin Custom Styles
add_action('admin_head', 'my_custom_fonts');
function my_custom_fonts() {
  echo '<style>
    #toplevel_page_thf-plugin-options {
        display: none !important;
    }
  </style>';
}
?>