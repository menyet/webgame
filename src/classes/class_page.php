<?php

require_once('playermanager.php');
require_once('Dialogue.php');

require_once('Styles.php');


class PAGE
{
	function __construct() {
	}
	
	// vypise hlavicku html dokumentu - tuto funkciu bez zmeny dedia vsetky koncove triedy 
	function DisplayHead(){
		$titles = Array(								// pole $titles obsahuje mozne hlasky do <title> tagu
			"Post-Apolalyptic Fantasy",
			"The Darkest Day",
			"war never changes...",
			"The Online RPG",
			"Booze and Hookers",
			"Unlimited Fantasy",
			"We're born into the grave anyway.",
			"Your demons are your best friends.",
			"Welcome to the world of Wastecraft.",
			"Do roka a do dna...",
			"Všetci za jedného, jebem na všetkých!",
			"nothing to lose"
		);
		$title = $titles[rand(0,sizeof($titles)-1)];	// premenna $title obsahuje nahodnu hlasku z pola $titles
		echo '
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<script type="text/javascript" src="'.URL.'javascripts/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="'.URL.'javascripts/ajax.js"></script>
    <script type="text/javascript" src="'.URL.'javascripts/window_map.js"></script>
    <script type="text/javascript" src="'.URL.'javascripts/create_player_tooltips.js"></script>
		<script type="text/javascript" src="'.URL.'javascripts/characterscreen.js"></script>
    <link href="'.URL.'css/popup.css" rel="stylesheet" type="text/css"/>
    <link href="'.URL.'css/external_script.css" rel="stylesheet" type="text/css"/>
    <link href="'.URL.'css/questwindow.css" rel="stylesheet" type="text/css"/>';
    Styles::printStyle();
echo '
		<title>Wasteland - '.$title.'</title>
	</head>';
	}
	
	// vypise telo html dokumentu - tuto funkciu ma kazda koncova trieda predefinovanu podla svojej potreby
	function DisplayBody(){
	}
	
	// hlavna zobrazovacia funkcia - argument $css_file posunie funkcii Displayead() a argument $display_functions funkcii DisplayBody() (su to nazvy css suboru pre danu stranku a skriptu, ktory potrebujeme napr pre zobrazenie mapky, atd...)
	function Display(){
		echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">';
		$this->DisplayHead();
		echo '
	<body>
		<div class="mainframe">';
		$this->DisplayBody();
		echo '
		</div>
    ';
    
    $this->popup();
    
    echo '
	</body>
</html>';
	}
  
  
  function popup() {
    
    echo '<div id="popupbackground"></div>
    
  
    <div id="popupcontainer">
      
      <div id="popupscreen">
        <div id="popuptop">
          <div id="popupclose"><a href="#" onclick="hidePopUp()">X</a></div>
          <div id="popuptitle">Title</div>
        </div>
        <div id="popupcontent"></div>

      </div>
    </div>';
    
  }



}

?>
