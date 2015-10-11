<?php

require '../security.php';
require '../classes/class_external_script.php';

class FORUM extends EXTERNAL_SCRIPT
{

	function DisplayBody() {
		echo 'forum wasteland';
	}

	function FORUM() {
		$this->Display('external_script.css','Fórum');
	}

}

	// vytvorenie objektu skriptu
	$page = new FORUM();

?>