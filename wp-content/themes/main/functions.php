<?php
if (!defined('ZANBIL_THEME')) {
	define('ZANBIL_THEME', 'zanbil_theme');
}

require_once 'avn.php';
AVIN_RtlTheme_Lic02::get_instance();
$check_class = AVIN_RtlTheme_Lic02::get_instance();
$rtloauthcheck = $check_class->rtloauthcheck();

if ($rtloauthcheck !== true) {
	AVIN_RtlTheme_Lic01::get_instance();
}
else {
	require_once get_template_directory() . '/lib/defines.php';
	require_once get_template_directory() . '/lib/classes.php';
	require_once get_template_directory() . '/lib/utils.php';
	require_once get_template_directory() . '/custom-function.php';
	require_once get_template_directory() . '/lib/init.php';
	require_once get_template_directory() . '/lib/cleanup.php';
	require_once get_template_directory() . '/lib/nav.php';
	require_once get_template_directory() . '/lib/widgets.php';
	require_once get_template_directory() . '/lib/scripts.php';
	require_once get_template_directory() . '/lib/customizer.php';
	require_once get_template_directory() . '/lib/plugin-requirement.php';

	if (class_exists('Woocommerce')) {
		require_once get_template_directory() . '/lib/woocommerce-hook.php';
	}
}