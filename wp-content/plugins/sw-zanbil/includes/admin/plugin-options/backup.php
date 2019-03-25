<?php

	# Export/Import Plugin Options
	thf_build_plugin_option(
		array(
			'title' => esc_html__( 'Export/Import Plugin Options', TCW_TEXTDOMAIN ),
			'type'  => 'tab-title',
		));

	if( isset( $_REQUEST['import'] )){

		thf_build_plugin_option(
			array(
				'text' => esc_html__( 'The plugin options have been imported successfully.', TCW_TEXTDOMAIN ),
				'type' => 'message',
			));
	}

	thf_build_plugin_option(
		array(
			'title' => esc_html__( 'Export', TCW_TEXTDOMAIN ),
			'type'  => 'header',
		));

	?>

	<div class="option-item">

		<p><?php esc_html_e( 'When you click the button below the theme will create a .dat file for you to save to your computer.', TCW_TEXTDOMAIN ); ?></p>
		<p><?php esc_html_e( 'Once you’ve saved the download file, you can use the Import function in another WordPress installation to import the plugin options from this site.', TCW_TEXTDOMAIN ); ?></p>

		<p><a class="thf-primary-button button button-primary button-hero" href="<?php print wp_nonce_url( admin_url( 'admin.php?page=thf-plugin-options&export-settings' ), 'export-plugin-settings', 'export_nonce' ) ?>"><?php esc_html_e( 'Download Export File', TCW_TEXTDOMAIN ); ?></a></p>
	</div>

	<?php

	thf_build_plugin_option(
		array(
			'title' => esc_html__( 'Import', TCW_TEXTDOMAIN ),
			'type'  => 'header',
		));

	?>

	<div class="option-item">

		<p><?php esc_html_e( 'Upload your .dat plugin options file and we will import the options into this site.', TCW_TEXTDOMAIN ); ?></p>
		<p><?php esc_html_e( 'Choose a (.dat) file to upload, then click Upload file and import.', TCW_TEXTDOMAIN ); ?></p>

		<p>
			<label for="upload"><?php esc_html_e( 'Choose a file from your computer:', TCW_TEXTDOMAIN ); ?></label>
			<input type="file" name="thf_import_file" id="thf-import-file" />
		</p>

		<p>
			<input type="submit" name="thf-import-upload" id="thf-import-upload" class="button-primary" value="<?php esc_html_e( 'Upload file and import', TCW_TEXTDOMAIN ); ?>" />
		</p>
	</div>





