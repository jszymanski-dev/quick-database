<?php

class QuickUsers extends QuickGlobal {
	
	private $Users = [];

	private function load( $forced = false ) {

		if ( !empty($this->Users) && !$forced ) return;
		
		$config = json_decode(file_get_contents(QUICK_ROOTH_PATH . '/Data/QuickDB.config.json'), true);
		
		$this->Users = $config['users'];
		
	}

	private function save() {

		$config = json_decode(file_get_contents(QUICK_ROOTH_PATH . '/Data/QuickDB.config.json'), true);
		$config['users'] = $this->Users;
		file_put_contents(QUICK_ROOTH_PATH . '/Data/QuickDB.config.json', json_encode($config));
		$this->Users = [];
		
	}
	
	public function exists( string $username ) {

		$this->load();
		
		$all_users = array_column($this->Users, 'usr');

		return array_search($username, $all_users);
		
	}

	public function add( string $username, string $password ) {

		$this->load();
		
		if ( !preg_match('/^[a-zA-Z]{1}[0-9a-zA-Z]{3,}$/', $username) ) {
			$this->error("Invalid username. Username must be contain minimum 4 characters and may be contain only lowercase and uppercase letters and numbers. User name can't begins with number.");
			return false;
		}

		if ( !preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*['.self::$SpecialCharacters.'])[A-Za-z\d'.self::$SpecialCharacters.']{8,}$/', $password) ) {
			$this->error("Invalid password. Password must contain minimum 8 characters, at least one uppercase letter, one lowercase letter, one number and one special character (from the following: ".self::$SpecialCharacters.").");
			return false;
		}
		
		if ( $this->exists($username) !== false ) {
			$this->error("User with given name already exists.");
			return false;
		}
		
		$new_user = [
			'usr' => $username,
			'pwd' => md5($password),
			'prm' => [],
			'tkn' => [],
		];

		$this->Users[] = $new_user;
		$this->save();
		
	}

	public function check( $username, $password ) {

		$this->load();
		
		$user_index = $this->exists($username);

		if ( $user_index === false ) {
			$this->error("Invalid username or password.");
			return false;
		}

		if ( $this->Users[$user_index]['pwd'] !== md5($password) ) {
			$this->error("Invalid username or password.");
			return false;
		}

		return true;
		
	}

	public function change_password( $username, $password, $new_password ) {
		
		$this->load();
		
		$user_index = $this->exists($username);

		if ( !$this->check($username, $password) ) return false;

		if ( !preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*['.self::$SpecialCharacters.'])[A-Za-z\d'.self::$SpecialCharacters.']{8,}$/', $new_password) ) {
			$this->error("New password is invalid. Password must contain minimum 8 characters, at least one uppercase letter, one lowercase letter, one number and one special character (from the following: ".self::$SpecialCharacters.").");
			return false;
		}

		$this->Users[$user_index]['pwd'] = md5($new_password);
		$this->save();
		return true;
		
	}
	
}



?>