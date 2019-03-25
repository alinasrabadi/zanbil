<?php
final class AVIN_RtlTheme_Lic01
{
    static private $instance;
	static public function get_instance()
	{
		if (self::$instance == NULL) {
			self::$instance = new AVIN_RtlTheme_Lic01();
		}

		return self::$instance;
	}

    public function rtloauthcheck() {
        return true;
    }
}

final class AVIN_RtlTheme_Lic02
{
    static private $instance;
	static public function get_instance()
	{
		if (self::$instance == NULL) {
			self::$instance = new AVIN_RtlTheme_Lic02();
		}

		return self::$instance;
	}

    public function rtloauthcheck() {
        return true;
    }
}