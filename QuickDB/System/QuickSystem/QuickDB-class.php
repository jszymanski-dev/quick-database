<?php

/**
 * @author Jakub Szymański <jakub-szymanski@outlook.com>
 * @package QuickDB
 */
class QuickDB extends QuickGlobal {
	
	private static $Connect;
	private static $Users;
	private static $Config;

	public static function init() {

		self::$Connect = new QuickConnect();
		self::$Users = new QuickUsers();
		
		self::read_config();

		if ( !self::$Config ) {

			self::install();
			
		}
		
		self::show_gui();
		
		echo 'Initialization... ' . PHP_EOL;

		try {

			self::$Users->add('admin', 'Jd9$gdfgfkjn');

		} catch (QuickException $e) {
			echo $e->getMessage() . PHP_EOL;
		}
		
		die();

	}

	private static function show_gui() {

		$path = '/quickdb';
		$uri  = strtolower($_SERVER['REQUEST_URI']);
		
		$uri_array = explode('/', str_replace($path, '', $uri));
		
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
		self::$Config = json_decode(file_get_contents(Q_ROOT_PATH . '/Data/QuickDB.config.json'), true);
	}

	private static function save_config() {
		file_put_contents(Q_ROOT_PATH . '/Data/QuickDB.config.json', json_encode(self::$Config));
	}

}

?>