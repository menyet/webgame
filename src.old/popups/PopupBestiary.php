<?php

require '../security.php';
require '../classes/class_external_script.php';
require '../configuration.php';

class BESTIARY extends EXTERNAL_SCRIPT
{

	function DisplayBody() {
	
	}

	function BESTIARY() {
		$this->Display('external_script.css','Vytvorenie nového hráča');
	}

}

	// vytvorenie objektu skriptu
	$page = new BESTIARY();

?>