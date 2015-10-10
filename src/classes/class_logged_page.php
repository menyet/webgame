<?php

require_once ('class_page.php');

class LOGGED_PAGE extends PAGE
{
	
	
	
	

	
	// vrati pole - vysledok mysql dotazu "$query" 
	
	// odhlasi uzivatela a vytvori NOT_LOGGED_PAGE
	function LogOut(){
		$_SESSION = array();
		if (isset($_COOKIE[session_name()])) {
			setcookie(session_name(), '', time()-42000, '/');
		}
		session_destroy();
		require 'class_not_logged_page.php';
		$son = new NOT_LOGGED_PAGE();
	}
}

?>