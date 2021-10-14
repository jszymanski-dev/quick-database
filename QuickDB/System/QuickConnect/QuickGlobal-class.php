<?php

abstract class QuickGlobal {

	protected static $SpecialCharacters = '@$!%*?&';

	protected static function error( string $msg ) {
		throw new QuickException($msg);
	}
	
}



?>