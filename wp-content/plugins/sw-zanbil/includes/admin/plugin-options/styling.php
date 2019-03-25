<?php

# Styling Options
thf_build_plugin_option( array(
    'title' => esc_html__( 'Styling Settings', TCW_TEXTDOMAIN ),
    'id'    => 'layout-tab',
    'type'  => 'tab-title',
) );

thf_build_plugin_option( array(
    'title' => esc_html__( 'Styling Settings', TCW_TEXTDOMAIN ),
    'id'    => 'styling-head',
    'type'  => 'header',
) );

thf_build_plugin_option( array(
    'name' => esc_html__( 'Apply custom styling by inline code', TCW_TEXTDOMAIN ),
    'hint' => esc_html__( 'check this option if you have problems with styling because of styling file rewrite permissions.', TCW_TEXTDOMAIN ),
    'id'   => 'inline_css',
    'type' => 'checkbox',
) );


# Site Width
thf_build_plugin_option( array(
    'title' => esc_html__( 'Site Width', TCW_TEXTDOMAIN ),
    'type'  => 'header',
) );

thf_build_plugin_option( array(
    'name'    => esc_html__( 'Site Width', TCW_TEXTDOMAIN ),
    'id'      => 'site_width',
    'type'    => 'text',
    'default' => '1200px',
    'hint'    => esc_html__( 'Controls the overall site width. In px or %, ex: 100% or 1170px.', TCW_TEXTDOMAIN ),
) );


# Custom Body Classes
thf_build_plugin_option( array(
    'title' => esc_html__( 'Custom Body Classes', TCW_TEXTDOMAIN ),
    'id'    => 'custom-body-classes',
    'type'  => 'header',
) );

thf_build_plugin_option( array(
    'name' => esc_html__( 'Custom Body Classes', TCW_TEXTDOMAIN ),
    'id'   => 'body_class',
    'type' => 'text',
) );


# Theme Skin and color
thf_build_plugin_option( array(
    'title' => esc_html__( 'Primary Color', TCW_TEXTDOMAIN ),
    'id'    => 'primary-color',
    'type'  => 'header',
) );

thf_build_plugin_option( array(
    'name'    => esc_html__( 'Predefined skins', TCW_TEXTDOMAIN ),
    'id'      => '',
    'class'   => 'predefined-skins',
    'type'    => 'select',
    'options' => array(
        ''            => esc_html__( 'Choose a Skin', TCW_TEXTDOMAIN ),
        'default'     => esc_html__( 'Default', TCW_TEXTDOMAIN ),
        'blue'        => esc_html__( 'Blue', TCW_TEXTDOMAIN ),
        'yellow'      => esc_html__( 'Yellow', TCW_TEXTDOMAIN ),
        'alizarin'    => esc_html__( 'Alizarin', TCW_TEXTDOMAIN ),
        'sand'        => esc_html__( 'Sand', TCW_TEXTDOMAIN ),
        'royal'       => esc_html__( 'Royal', TCW_TEXTDOMAIN ),
        'mint'        => esc_html__( 'Mint', TCW_TEXTDOMAIN ),
        'stylish_red' => esc_html__( 'Stylish Red', TCW_TEXTDOMAIN ),
        'twilight'    => esc_html__( 'Twilight', TCW_TEXTDOMAIN ),
        'coffee'      => esc_html__( 'Coffee', TCW_TEXTDOMAIN ),
        'ocean'       => esc_html__( 'Ocean', TCW_TEXTDOMAIN ),
        'cyan'        => esc_html__( 'Cyan', TCW_TEXTDOMAIN ),
        'facebook'    => esc_html__( 'Facebook', TCW_TEXTDOMAIN ),
        'sahifa'      => esc_html__( 'Sahifa', TCW_TEXTDOMAIN ),
        'mist'        => esc_html__( 'Mist', TCW_TEXTDOMAIN ),
        'serene'      => esc_html__( 'Serene', TCW_TEXTDOMAIN ),
        'fall'        => esc_html__( 'Fall', TCW_TEXTDOMAIN ),
    ),
) );

$skins = array(
    'blue' => array(
        'global_color' => '#1b98e0',
    ),

    'yellow' => array(
        'global_color' => '#f98d00',
    ),

    'alizarin' => array(
        'global_color' => '#fe4641',
    ),

    'sand' => array(
        'global_color' => '#daa48a',
    ),

    'royal' => array(
        'global_color' => '#921245',
    ),

    'mint' => array(
        'global_color' => '#00bf80',
    ),

    'stylish_red' => array(
        'global_color' => '#ff2b58',
    ),

    'twilight' => array(
        'global_color' => '#937cbf',
    ),

    'coffee' => array(
        'global_color' => '#c7a589',
    ),

    'ocean' => array(
        'global_color' => '#9ebaa0',
    ),

    'cyan' => array(
        'global_color' => '#32beeb',
    ),

    'facebook' => array(
        'global_color' => '#3b5998',
    ),

    'sahifa' => array(
        'global_color' => '#F88C00',
    ),

    'mist' => array(
        'global_color' => '#2e323c',
    ),

    'serene' => array(
        'global_color' => '#fcad84',
    ),

    'fall' => array(
        'global_color' => '#613942',
    ),

);

?>
    <script>var thf_skins = <?php echo wp_json_encode( $skins ) ?>;</script>
<?php
thf_build_plugin_option( array(
    'name' => esc_html__( 'Custom Primary Color', TCW_TEXTDOMAIN ),
    'id'   => 'global_color',
    'type' => 'color',
) );


# Body styles
thf_build_plugin_option( array(
    'title' => esc_html__( 'Body', TCW_TEXTDOMAIN ),
    'id'    => 'body-styling',
    'type'  => 'header',
) );

thf_build_plugin_option( array(
    'name' => esc_html__( 'Highlighted Text Color', TCW_TEXTDOMAIN ),
    'id'   => 'highlighted_color',
    'type' => 'color',
) );

thf_build_plugin_option( array(
    'name' => esc_html__( 'Links Text Color', TCW_TEXTDOMAIN ),
    'id'   => 'links_color',
    'type' => 'color',
) );

thf_build_plugin_option( array(
    'name' => esc_html__( 'Links text HOVER color', TCW_TEXTDOMAIN ),
    'id'   => 'links_color_hover',
    'type' => 'color',
) );

thf_build_plugin_option( array(
    'name' => esc_html__( 'Underline text links on hover', TCW_TEXTDOMAIN ),
    'id'   => 'underline_links_hover',
    'type' => 'checkbox',
) );


# Main Content
thf_build_plugin_option( array(
    'type'  => 'header',
    'id'    => 'main-content-styling',
    'title' => esc_html__( 'Main Content Styling', TCW_TEXTDOMAIN ),
) );

thf_build_plugin_option( array(
    'name' => esc_html__( 'In Post Links Color', TCW_TEXTDOMAIN ),
    'id'   => 'post_links_color',
    'type' => 'color',
) );

thf_build_plugin_option( array(
    'name' => esc_html__( 'In Post Links Color on mouse over', TCW_TEXTDOMAIN ),
    'id'   => 'post_links_color_hover',
    'type' => 'color',
) );


# Custom CSS
thf_build_plugin_option( array(
    'title' => esc_html__( 'Custom CSS', TCW_TEXTDOMAIN ),
    'id'    => 'custom-css',
    'type'  => 'header',
) );

thf_build_plugin_option( array(
    'text' => esc_html__( 'Paste your CSS code, do not include any tags or HTML in the field. Any custom CSS entered here will override the theme CSS. In some cases, the !important tag may be needed.', TCW_TEXTDOMAIN ),
    'type' => 'message',
) );

thf_build_plugin_option( array(
    'name'  => esc_html__( 'Custom CSS', TCW_TEXTDOMAIN ),
    'id'    => 'css',
    'class' => 'thf-css',
    'type'  => 'textarea',
) );

thf_build_plugin_option( array(
    'name'  => esc_html__( 'Tablets', TCW_TEXTDOMAIN ) . '<br /><small>' . esc_html__( '768px - 1024px', TCW_TEXTDOMAIN ) . '</small>',
    'id'    => 'css_tablets',
    'class' => 'thf-css',
    'type'  => 'textarea',
) );

thf_build_plugin_option( array(
    'name'  => esc_html__( 'Phones', TCW_TEXTDOMAIN ) . '<br /><small>' . esc_html__( '0 - 768px', TCW_TEXTDOMAIN ) . '</small>',
    'id'    => 'css_phones',
    'class' => 'thf-css',
    'type'  => 'textarea',
) );