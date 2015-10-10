<?php

	// funkcia, ktora vezme sancu (cislo od 0 do 100) a vrati true, alebo false
	function Chance($value){
	  if (rand(0,99) < $value) {
	    return true;
	  } else {
	    return false;
	  }
	}
	
?>