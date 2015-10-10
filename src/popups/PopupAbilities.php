<?php

require '../security.php';
require '../classes/class_external_script.php';

class ABILITIES
{

	function DisplayBody() {
	
	}

	function ABILITIES() {
		$this->Display('external_script.css','Nebojové zručnosti');
	}

}

	// vytvorenie objektu skriptu
	$page = new ABILITIES();

?>