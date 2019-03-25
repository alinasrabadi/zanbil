<?php

	thf_build_plugin_option(
		array(
			'title' => esc_html__( 'Advanced Settings', TCW_TEXTDOMAIN ),
			'id'    => 'advanced-settings-tab',
			'type'  => 'tab-title',
		));

	thf_build_plugin_option(
		array(
			'type'  => 'header',
			'id'    => 'advanced-settings',
			'title' => esc_html__( 'Advanced Settings', TCW_TEXTDOMAIN ),
		));

	thf_build_plugin_option(
		array(
			'name' => esc_html__( 'Cache', TCW_TEXTDOMAIN ),
			'id'   => 'cache',
			'type' => 'checkbox',
			'hint' => esc_html__( 'If enabled, some static parts like widgets, main menu and breaking news will be cached to reduce MySQL queries. Saving the theme settings, adding/editing/removing posts, adding comments, updating menus, activating/deactivating plugins, adding/editing/removing terms or updating WordPress, will flush the cache.', TCW_TEXTDOMAIN ),
		));

	thf_build_plugin_option(
		array(
			'name' => esc_html__( 'Minified CSS and JS files', TCW_TEXTDOMAIN ),
			'id'   => 'minified_files',
			'type' => 'checkbox',
		));

	thf_build_plugin_option(
		array(
			'name' => esc_html__( 'Add a link to the plugin options page to the Toolbar', TCW_TEXTDOMAIN ),
			'id'   => 'theme_toolbar',
			'type' => 'checkbox',
		));

	thf_build_plugin_option(
        array(
            'name' => esc_html__( 'Disable GIF Featured Images', TCW_TEXTDOMAIN ),
            'id'   => 'disable_featured_gif',
            'type' => 'checkbox',
        ));

	// thf_build_plugin_option(
	// 	array(
	// 		'name' => esc_html__( 'Disable the custom styles in the editor', TCW_TEXTDOMAIN ),
	// 		'id'   => 'disable_editor_styles',
	// 		'type' => 'checkbox',
	// 	));

	thf_build_plugin_option(
		array(
			'type'  => 'header',
			'id'    => 'wordpress-login-page-logo',
			'title' => esc_html__( 'WordPress Login page Logo', TCW_TEXTDOMAIN ),
		));

	thf_build_plugin_option(
		array(
			'name' => esc_html__( 'WordPress Login page Logo', TCW_TEXTDOMAIN ),
			'id'   => 'dashboard_logo',
			'type' => 'upload',
		));

	thf_build_plugin_option(
		array(
			'name' => esc_html__( 'WordPress Login page Logo URL', TCW_TEXTDOMAIN ),
			'id'   => 'dashboard_logo_url',
			'type' => 'text',
		));


	thf_build_plugin_option(
		array(
			'type'  => 'header',
			'id'    => 'reset-all-settings',
			'title' => esc_html__( 'Reset All Settings', TCW_TEXTDOMAIN ),
		));
		?>

		<div class="option-item">
			<a id="thf-reset-settings" class="thf-primary-button button button-primary button-hero thf-button-red" href="<?php print wp_nonce_url( admin_url( 'admin.php?page=thf-plugin-options&reset-settings' ), 'reset-plugin-settings', 'reset_nonce' ) ?>" data-message="<?php esc_html_e( 'This action can not be Undo. Clicking "OK" will reset your plugin options to the default installation. Click "Cancel" to stop this operation.', TCW_TEXTDOMAIN); ?>"><?php esc_html_e( 'Reset All Settings', TCW_TEXTDOMAIN ); ?></a>
		</div>

