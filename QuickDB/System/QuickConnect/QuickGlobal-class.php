<?php

define('Q_ROOT_PATH', $_SERVER['DOCUMENT_ROOT'] . 'QuickDB');
define('Q_SPECIAL_CHARACTERS', '@$!%*?&');

abstract class QuickGlobal {

	protected static function error( string $msg ) {
		throw new QuickException($msg);
	}
	
}



?>