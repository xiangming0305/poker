<?php

class Core{
	private static $version = "1.19";
	
	public static function version(){
		return self::$version;
	}

	/**
	 * Remove all html characters and mysql characters
	 * @param $str
	 */
	public static function escape($str) {
		return preg_replace( '/[<>&\'\\"]/i', '', $str);
	}

	public static function escapeArray($array) {
		$ret = array();
		foreach($array as $key=>$value) $ret[$key] = self::escape($value);
		return $ret;
	}
}

?>