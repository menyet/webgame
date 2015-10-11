<?php

require_once('class_not_logged_page.php');
require_once('functions/tooltips.php');

		define('MIN_STRENGTH', 2);
		define('MIN_ENDURANCE', 2);
		define('MIN_INTELLIGENCE', 5);
		define('MIN_SPEED', 5);
		define('MIN_CHARISMA', 1);
		define('MIN_LUCK', 1);
		define('ATTRIBUTE_MAX', 10);



class RegisterSelectAttributes extends  NOT_LOGGED_PAGE {
	
	public function __construct() {
		Styles::addStyle('title_page');
		$player = Player::actualPlayer();
    
		$this->mod_sila = 0;
		$this->mod_vydrz = 0;
		$this->mod_inteligencia = 0;
		$this->mod_rychlost = 0;
		$this->mod_charizma = 0;
		$this->mod_stastie = 0;
		$this->mod_zostavajuce = 0;

    if ($player->race == 'half-ogre') $this->mod_sila++;
		if ($player->race == 'dwarf') $this->mod_vydrz++;
		if ($player->race == 'half-ogre') $this->mod_vydrz++;
		if ($player->race == 'kremon') $this->mod_inteligencia++;
		if ($player->race == 'human') $this->mod_rychlost++;
		if ($player->race == 'half-ogre') $this->mod_charizma--;
		if ($player->race == 'elf') $this->mod_charizma++;
		if ($player->race == 'gnome') $this->mod_stastie++;
    

    
    if (Trait::checkTrait('trait_sprt', $player->id)) $this->mod_sila--;
		if (Trait::checkTrait('trait_bitkar', $player->id)) $this->mod_sila++;
		if (Trait::checkTrait('trait_sprt', $player->id)) $this->mod_inteligencia++;
		if (Trait::checkTrait('trait_bitkar', $player->id)) $this->mod_inteligencia--;
		if (Trait::checkTrait('trait_mutant', $player->id)) $this->mod_charizma--;
		if (Trait::checkTrait('trait_nadany', $player->id)) $this->mod_zostavajuce += 5;
    
		$this->zostavajuce = 20 + $this->mod_zostavajuce;
    
    if (isset($_POST['setattributes'])) {
      $strength = $_POST['sila'] - MIN_STRENGTH - $this->mod_sila;
      $endurance = $_POST['vydrz'] - MIN_ENDURANCE - $this->mod_vydrz;
      $intelligence = $_POST['inteligencia'] - MIN_INTELLIGENCE - $this->mod_inteligencia;
      $speed = $_POST['rychlost'] - MIN_SPEED - $this->mod_rychlost;
      $charisma = $_POST['charizma'] - MIN_CHARISMA - $this->mod_charizma;
      $luck = $_POST['stastie'] - MIN_LUCK - $this->mod_stastie;
      
      if ($strength + $endurance + $intelligence + $speed + $charisma + $luck) {
        $attr = $player->attributes;
        $attr->strength =  $_POST['sila'];
        $attr->endurance =  $_POST['vydrz'];
        $attr->intelligence =  $_POST['inteligencia'];
        $attr->speed =  $_POST['rychlost'];
        $attr->charisma =  $_POST['charizma'];
        $attr->luck =  $_POST['stastie'];
        $attr->saveAttributes();
        
        
        header('location:'.URL);
        exit();
        
        
        
      }
      
      

      
    }

		
	}

	public function displayContent() {
			// vytvorenie tooltipov, ktore budeme pouzivat vo formulari
			CreateToolTip(0,'','Body, ktoré môžeš prerozdeliť medzi atribúty akokoľvek chceš.<br>Základná hodnota je 20, no môže byť zvýšená osobnostnou črtou Nadaný.');
			CreateToolTip(1,'Sila','Fyzická sila postavy.<br> - Predmety v hre potrebujú určitú silu, aby si ich mohol použiť.<br> - Určuje, koľko kilogramov vecí môžeš mať v inventári. (10kg na 1 bod sily)<br> - Prispieva k základnému počtu Hit-Pointov (života) hodnotou 1 HP za 1 bod sily.<br> - Určuje damage doplňujúcich bojových schopností - kopov a úderov. (kop = 3xSila, úder = 2xSila)');
			CreateToolTip(2,'Výdrž','Odolnosť voči útokom.<br> - Udáva počet získaných HP za level. (1 HP za 1 výdrž)<br> - Prispieva k základnému počtu Hit-Pointov (života) postavy hodnotou 2 HP za 1 bod výdrže.<br> - Prispieva k počtu vyliečených Hit-Pointov za ťah pri pohybe po svete. (počet = počet bodov vo výdrži + 1HP za každých 20% v liečení)');
			CreateToolTip(3,'Inteligencia','Vynaliezavosť a dôvtip postavy.<br> - Povoľuje niektoré možnosti pri dialógoch.<br> - Určuje počet získaných bodov na zručnosti za level. (2 body za 1 inteligenčný bod)<br> - Udáva základný počet bodov na zručnosti na rozdelenie v ďalšom kroku tvorby postavy. (5 bodov za 1 inteligenčný bod)');
			CreateToolTip(4,'Rýchlosť','Rýchlosť reakcií postavy.<br> - Určuje počet Action-Pointov (akčných bodov), ktoré máš k dispozícii v boji. Action-Pointy určia, koľko toho stihneš za jeden ťah v boji vykonať. Každá akcia (útok,kúzlo,vypitie lektvaru...) spotrebuje špecifický počet Action-Pointov. Počet AP je rovný počtu bodov, ktoré máš v rýchlosti.');
			CreateToolTip(5,'Charizma','Výzor a vystupovanie postavy.<br> - Ovplyvňuje reakcie niektorých postáv na teba.<br> - Každé 3 body v charizme ti dovolia mať v hre jedného spoločníka. (NPC postavu, ktorá ťa nasleduje a pomáha ti v boji)');
			CreateToolTip(6,'Šťastie','Šťastie postavy.<br> - Zvyšuje šance na zásah pri boji o 1% za 1 bod šťastia.<br> - Napomáha pri kradnutí a gamblovaní.<br> - Zvyšuje šancu na kritický zásah o 1% za bod šťastia. (kritické zásahy spôsobujú podstatne väčší damage)');
			
			echo '<h1>Atribúty - krok 4/6</h1>
            <div class="create_player_div" style="left: 300px; top: 60px; width: 500px; height: 300px; text-align: justify;">
              Atribúty sú základnou charakteristikou tvojej postavy. V podstate od nich závisí každá jej schopnosť a vlastnosť. Okrem toho určujú počiatočnú hodnotu zručností a dávajú ti rôzne možnosti riešenia questov v hre.<br><br>
              Na rozdelenie máš k dispozícii '.$this->zostavajuce.' atribútových bodov. Od toho, ako ich rozdelíš priamo závisí tvoj herný štýl, tak si pozorne preštuduj čo ktorý atribút ovplyvňuje a pred rozdelením popremýšľaj.<br><br>
              Maximálnou hodnotou každého atribútu je 10 bodov.<br>Minimum je rôzne:<br><br>';
              
			echo 'SILA: '.MIN_STRENGTH.'-'.ATTRIBUTE_MAX.'<br>';
			echo 'VYDRZ: '.MIN_ENDURANCE.'-'.ATTRIBUTE_MAX.'<br>';
			echo 'INTELIGENCIA: '.MIN_INTELLIGENCE.'-'.ATTRIBUTE_MAX.'<br>';
			echo 'RYCHLOST: '.MIN_SPEED.'-'.ATTRIBUTE_MAX.'<br>';
			echo 'CHARIZMA: '.MIN_CHARISMA.'-'.ATTRIBUTE_MAX.'<br>';
			echo 'STASTIE: '.MIN_LUCK.'-'.ATTRIBUTE_MAX.'<br>';
			echo '</div>';
			
			// skript na pridavanie a odoberanie atributov
			echo '<script type="text/javascript">
						zostavajuce = '.$this->zostavajuce.';
						function Attribute(meno,operacia){
						switch (meno) {		
								case "sila": minimum = '.MIN_STRENGTH.'; break;
								case "vydrz": minimum = '.MIN_ENDURANCE.'; break;
								case "inteligencia": minimum = '.MIN_INTELLIGENCE.'; break;
								case "rychlost": minimum = '.MIN_SPEED.'; break;
								case "charizma": minimum = '.MIN_CHARISMA.'; break;
								case "stastie": minimum = '.MIN_LUCK.'; break;
							}';
			
			echo '	if ((operacia == \'+\') && ((parseInt(document.forms[0][meno].value)) < '.ATTRIBUTE_MAX.') && (zostavajuce > 0)) {';
			echo '		document.forms[0][meno].value = parseInt(document.forms[0][meno].value)+1;';
			echo '		zostavajuce--;';
			echo '		document.forms[0]["zostav"].value = zostavajuce;';
			echo '	}';
			echo '	if ((operacia == \'-\') && ((parseInt(document.forms[0][meno].value)) > minimum)) {';
			echo '		document.forms[0][meno].value = parseInt(document.forms[0][meno].value)-1;';
			echo '		zostavajuce++;';
			echo '		document.forms[0]["zostav"].value = zostavajuce;';
			echo '	}';
			echo '}';
			echo '</SCRIPT>';
			
			// formular
			echo '<div class="create_player_div" style="left: 20px; top: 60px; width: 250px; height: 300px; text-align: left;">';
			echo '<form action="'.URL.'" method="post">';
			
			
			// zostavajuce body
			echo '<div '.UseToolTip(0).' style="position: absolute; width: 200px; top: 12px; left: 10px; font-size: 14px !important; font-size: 16px; font-weight: bold;">ZOSTÁVAJÚCE BODY: </div>';
			echo '<input id="zostav" name="zostav" type="text" readonly value="'.$this->zostavajuce.'" style="background-color: transparent; border-style: none; color: rgb(200,200,255); font-weight: bold; font-size: 22px; font-family: bookman; position: absolute; left: 185px; width: 30px;">';
			
			echo '	<div onClick="Attribute(\'sila\',\'+\')" class="create_player_button" style="top: 40px; left: 56px;">+</div>';
			echo '	<div onClick="Attribute(\'sila\',\'-\')" class="create_player_button" style="top: 40px; left: 10px;">-</div>';
			echo '	<div '.UseToolTip(1).' style="position: absolute; width: 100px; top: 40px; left: 80px; font-size: 20px;">SILA</div>';
			echo '	<input id="sila" name="sila" class="create_player_input" readonly type="text" style="top: 40px; left: 30px; width: 22px; margin: 0px; padding: 0px;" value="'.(MIN_STRENGTH+$this->mod_sila).'">';
			
			echo '	<div onClick="Attribute(\'vydrz\',\'+\')" class="create_player_button" style="top: 65px; left: 56px;">+</div>';
			echo '	<div onClick="Attribute(\'vydrz\',\'-\')" class="create_player_button" style="top: 65px; left: 10px;">-</div>';
			echo '	<div '.UseToolTip(2).' style="position: absolute; width: 100px; top: 65px; left: 80px; font-size: 20px;">VÝDRŽ</div>';
			echo '	<input id="vydrz" name="vydrz" class="create_player_input" readonly type="text" style="top: 65px; left: 30px; width: 22px; margin: 0px; padding: 0px;" value="'.(MIN_ENDURANCE+$this->mod_vydrz).'">';
			
			echo '	<div onClick="Attribute(\'inteligencia\',\'+\')" class="create_player_button" style="top: 90px; left: 56px;">+</div>';
			echo '	<div onClick="Attribute(\'inteligencia\',\'-\')" class="create_player_button" style="top: 90px; left: 10px;">-</div>';
			echo '	<div '.UseToolTip(3).' style="position: absolute; width: 100px; top: 90px; left: 80px; font-size: 20px;">INTELIGENCIA</div>';
			echo '	<input id="inteligencia" name="inteligencia" class="create_player_input" readonly type="text" style="top: 90px; left: 30px; width: 22px; margin: 0px; padding: 0px;" value="'.(MIN_INTELLIGENCE+$this->mod_inteligencia).'">';
			
			echo '	<div onClick="Attribute(\'rychlost\',\'+\')" class="create_player_button" style="top: 115px; left: 56px;">+</div>';
			echo '	<div onClick="Attribute(\'rychlost\',\'-\')" class="create_player_button" style="top: 115px; left: 10px;">-</div>';
			echo '	<div '.UseToolTip(4).' style="position: absolute; width: 100px; top: 115px; left: 80px; font-size: 20px;">RÝCHLOSŤ</div>';
			echo '	<input id="rychlost" name="rychlost" class="create_player_input" readonly type="text" style="top: 115px; left: 30px; width: 22px; margin: 0px; padding: 0px;" value="'.(MIN_SPEED+$this->mod_rychlost).'">';
			
			echo '	<div onClick="Attribute(\'charizma\',\'+\')" class="create_player_button" style="top: 140px; left: 56px;">+</div>';
			echo '	<div onClick="Attribute(\'charizma\',\'-\')" class="create_player_button" style="top: 140px; left: 10px;">-</div>';
			echo '	<div '.UseToolTip(5).' style="position: absolute; width: 100px; top: 140px; left: 80px; font-size: 20px;">CHARIZMA</div>';
			echo '	<input id="charizma" name="charizma" class="create_player_input" readonly type="text" style="top: 140px; left: 30px; width: 22px; margin: 0px; padding: 0px;" value="'.(MIN_CHARISMA+$this->mod_charizma).'">';
			
			echo '	<div onClick="Attribute(\'stastie\',\'+\')" class="create_player_button" style="top: 165px; left: 56px;">+</div>';
			echo '	<div onClick="Attribute(\'stastie\',\'-\')" class="create_player_button" style="top: 165px; left: 10px;">-</div>';
			echo '	<div '.UseToolTip(6).' style="position: absolute; width: 100px; top: 165px; left: 80px; font-size: 20px;">ŠŤASTIE</div>';
			echo '	<input id="stastie" name="stastie" class="create_player_input" readonly type="text" style="top: 165px; left: 30px; width: 22px; margin: 0px; padding: 0px;" value="'.(MIN_LUCK+$this->mod_stastie).'">';
			
			echo '<input class="submit_button" type="submit" name="setattributes" value="Pokračovať krokom 5" style="position: absolute; left: 45px; top: 270px;">';
			echo '</form>';
			echo '</div>';
	}

}
?>