<?php

class LOCATION extends NORMAL_PAGE {

	function LOCATION(){

		// zadefinovanie adresara z obrazkami
		define("IMG_PATH","locations/desert");
	
		// priradenie spravneho css suboru do premennej $css_file a zobrazenie stranky
		$this->Display("play_page.css");
	}

	function DisplayBody(){
		
		world_map($GLOBALS['player']->x,$GLOBALS['player']->y);
		player_info($GLOBALS['player']);
		controls();
		
		// obrazok prostredia
		echo '<img class="location_image" src="'.IMG_PATH.'/desert0'.rand(1,4).'.jpg" alt="ilustrácia">';
		
		// popis prostredia
		echo '<div class="location_description">';
		DisplayMessages();
		echo '<h1 class="location_title">Púšť</h1>';
		echo 'Ocitol si sa v piesočnatej púšti aké vo svete pred Veľkou vojnou neboli. Na tomto mieste bola príroda zdevastovaná chemickými zbraňami obzvlášť dôkladne a dodnes sa z toho nespamätala. Široko-ďaleko nevidíš nič živé. Cítiš však, že radiácia a jedy vo vzduchu a v pôde sú stále prítomné a nebezpečné.';
		DisplayItemsOnGround();
		echo '</div>';
	}
	
}

?>