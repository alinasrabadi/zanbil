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

// Remove Admin Bar
show_admin_bar(false);


// Disable All Page Except Homepage
add_action('wp', function(){
  if(is_user_logged_in() && current_user_can('administrator')) return;
  if(is_home() || is_front_page() || is_product() || is_single() ) return;

  wp_die('این بخش از سایت در دست طراحی می باشد...', 'در دست طراحی', array( 
    'response' => 401,
    'back_link' => true
  ));
  die;
});