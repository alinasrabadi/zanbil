<?php

	thf_build_plugin_option(
		array(
			'title' => esc_html__( 'General Settings', TCW_TEXTDOMAIN ),
			'id'    => 'general-settings-tab',
			'type'  => 'tab-title',
		));


	// thf_build_plugin_option(
	// 	array(
	// 		'title' =>	esc_html__( 'Date Settings', TCW_TEXTDOMAIN ),
	// 		'id'    => 'time-format-settings',
	// 		'type'  => 'header',
	// 	));
	//
	// thf_build_plugin_option(
	// 	array(
	// 		'name'    => esc_html__( 'Date format for blog posts', TCW_TEXTDOMAIN ),
	// 		'id'      => 'time_format',
	// 		'type'    => 'radio',
	// 		'options' => array(
	// 			'traditional' => esc_html__( 'Traditional', TCW_TEXTDOMAIN ),
	// 			'modern'      => esc_html__( 'Time Ago Format', TCW_TEXTDOMAIN ),
	// 			'none'        => esc_html__( 'Disable all', TCW_TEXTDOMAIN ),
	// 		)));
	//
	// thf_build_plugin_option(
	// 	array(
	// 		'name'    => esc_html__( 'Show the date depending on', TCW_TEXTDOMAIN ),
	// 		'id'      => 'time_type',
	// 		'type'    => 'radio',
	// 		'options' => array(
	// 			'published' => esc_html__( 'Post Published Date', TCW_TEXTDOMAIN ),
	// 			'modified'  => esc_html__( 'Post Modified Date', TCW_TEXTDOMAIN ),
	// 		)));
	//
	//
	// thf_build_plugin_option(
	// 	array(
	// 		'title' =>	esc_html__( 'Breadcrumbs Settings', TCW_TEXTDOMAIN ),
	// 		'id'    => 'breadcrumbs-settings',
	// 		'type'  => 'header',
	// 	));
	//
	// thf_build_plugin_option(
	// 	array(
	// 		'name'   => esc_html__( 'Breadcrumbs', TCW_TEXTDOMAIN ),
	// 		'id'     => 'breadcrumbs',
	// 		'toggle' => '#breadcrumbs_delimiter-item',
	// 		'type'   => 'checkbox',
	// 	));
	//
	// thf_build_plugin_option(
	// 	array(
	// 		'name'    => esc_html__( 'Breadcrumbs Delimiter', TCW_TEXTDOMAIN ),
	// 		'id'      => 'breadcrumbs_delimiter',
	// 		'type'    => 'text',
	// 		'default' => '&#47;',
	// 	));
	//
	//
	// thf_build_plugin_option(
	// 	array(
	// 		'title' =>	esc_html__( 'Trim Text Settings', TCW_TEXTDOMAIN ),
	// 		'id'    => 'trim-text-settings',
	// 		'type'  => 'header',
	// 	));
	//
	// thf_build_plugin_option(
	// 	array(
	// 		'name'    => esc_html__( 'Trim text by', TCW_TEXTDOMAIN ),
	// 		'id'      => 'trim_type',
	// 		'type'		=> 'radio',
	// 		'options'	=> array(
	// 			'words' =>	esc_html__( 'Words', TCW_TEXTDOMAIN ) ,
	// 			'chars'	=>	esc_html__( 'Characters', TCW_TEXTDOMAIN ),
	// 		)));
	//
	//
	// thf_build_plugin_option(
	// 	array(
	// 		'title' =>	esc_html__( 'Post format icon on hover', TCW_TEXTDOMAIN ),
	// 		'id'    => 'post-font-icon',
	// 		'type'  => 'header',
	// 	));
	//
	// thf_build_plugin_option(
	// 	array(
	// 		'name'   => esc_html__( 'Show the post format icon on hover?', TCW_TEXTDOMAIN ),
	// 		'id'     => 'thumb_overlay',
	// 		'type'   => 'checkbox',
	// 	));

	thf_build_plugin_option(
		array(
			'title' =>	esc_html__( 'Custom Codes', TCW_TEXTDOMAIN ),
			'type'  => 'header',
		));

	thf_build_plugin_option(
		array(
			'name' => esc_html__( 'Header Code', TCW_TEXTDOMAIN ),
			'id'   => 'header_code',
			'hint' => esc_html__( 'Will add to the &lt;head&gt; tag. Useful if you need to add additional codes such as CSS or JS.', TCW_TEXTDOMAIN ),
			'type' => 'textarea',
		));

	thf_build_plugin_option(
		array(
			'name' => esc_html__( 'Footer Code', TCW_TEXTDOMAIN ),
			'id'   => 'footer_code',
			'hint' => esc_html__( 'Will add to the footer before the closing  &lt;/body&gt; tag. Useful if you need to add Javascript.', TCW_TEXTDOMAIN ),
			'type' => 'textarea',
		));
?>
