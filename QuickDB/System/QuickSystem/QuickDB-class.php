<?php

/**
 * @author Jakub Szymański <jakub-szymanski@outlook.com>
 * @package QuickDB
 */
class QuickDB extends QuickGlobal {
	
	private static $Connect;
	private static $Users;
	private static $Config;
	private static $Path = '/quickdb';

	public static function init( string $path = '' ) {

		self::$Path = $path === '' ? self::$Path : strtolower($path);
		self::$Connect = new QuickConnect();
		self::$Users = new QuickUsers();
		
		self::read_config();

		if ( !self::$Config ) {

			self::install();
			
		}
		
		self::show_gui();
		
		echo 'Initialization... ' . PHP_EOL;

		try {

			// self::$Users->add('admin', 'Jd9$gdfgfkjn');

		} catch (QuickException $e) {
			echo $e->getMessage() . PHP_EOL;
		}
		
		die();

	}

	private static function show_gui() {

		$uri  = strtolower($_SERVER['REQUEST_URI']);

		if ( strpos($uri, self::$Path) !== 0 ) return false;
		
		$new_uri = substr_replace($uri, '', 0, strlen(self::$Path));
		
		$uri_array = explode('/', $new_uri);
		
		if ( isset($uri_array[1]) && strtolower($uri_array[1]) == 'gui' ) {

			require_once(__DIR__ . '/../../Public/index.php');
			exit;
			
		}
		
	}

	private static function install() {

		self::$Config = [
			'users' => [],
		];

		self::save_config();
		
	}

	private static function read_config() {
		self::$Config = json_decode(file_get_contents(QUICK_ROOTH_PATH . '/Data/QuickDB.config.json'), true);
	}

	private static function save_config() {
		file_put_contents(QUICK_ROOTH_PATH . '/Data/QuickDB.config.json', json_encode(self::$Config));
	}

}

?>