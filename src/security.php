<?php

function CheckString($s){
	$s = trim($s);				// odstrani biele miesta zo zaciatku a konca (medzery, tabulatory, nove riadky...)
	//$s = strip_tags($s);				// odstrani HTML a PHP tagy
	$s = htmlspecialchars($s);	// nahradi specialne HTML znaky
	$s = addslashes($s);		// prida lomitka pred apostrofy a uvodzovky
	return $s;
}

// kontrola vstupov - skontroluje vsetky prvky poli GET a POST
foreach ($_POST as $key => $par) {
	$_POST[$key] = CheckString($par);
}
foreach ($_GET as $key => $par) {
	$_POST[$key] = CheckString($par);
}

?>