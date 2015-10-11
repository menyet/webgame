<?php


require_once('class_not_logged_page.php');
require_once('functions/tooltips.php');

class RegisterSelectSkills extends  NOT_LOGGED_PAGE {
  public function __construct() {
		Styles::addStyle('title_page');
    $player = Player::actualPlayer();
    $attr = $player->attributes;
    
    $this->zostavajuce_zrucnosti = 5*$attr->intelligence;
		
		$this->base_melee = min(49,3*$attr->strength+$attr->endurance+$attr->speed);
		$this->base_ranged = min(49,$attr->strength+$attr->endurance+2*$attr->speed+$attr->luck);
		$this->base_alien = min(49,2*$attr->intelligence+$attr->endurance+2*$attr->strength);
		$this->base_magic_attack = min(49,4*$attr->intelligence+$attr->speed);
		$this->base_magic_defense = min(49,4*$attr->intelligence+$attr->charisma);
		$this->base_merchant = min(49,3*$attr->charisma+2*$attr->intelligence);
		$this->base_thief = min(49,3*$attr->speed+2*$attr->luck);
		$this->base_gambling = min(49,3*$attr->luck+2*$attr->intelligence);
		$this->base_healing = min(49,2*$attr->intelligence+$attr->luck+2*$attr->endurance);
		$this->base_alchymist = min(49,3*$attr->intelligence+$attr->speed+$attr->luck);
    
    
    $this->mod_melee = 0;
    
		$this->mod_ranged = 0;
		$this->mod_alien = 0;
		$this->mod_magic_attack = 0;
		$this->mod_magic_defense = 0;
		$this->mod_merchant = 0;
		$this->mod_thief = 0;
		$this->mod_gambling = 0;
		$this->mod_healing = 0;
		$this->mod_alchymist = 0;
    
    if ($player->race == 'half-ogre') $this->mod_melee += 15; 
		if ($player->race == 'human') $this->mod_ranged += 10; 
		if ($player->race == 'human') $this->mod_magic_attack += 5; 
		if ($player->race == 'kremon') $this->mod_magic_attack += 20; 
		if ($player->race == 'dwarf') $this->mod_merchant += 20; 
		if ($player->race == 'gnome') $this->mod_thief += 20; 
		if ($player->race == 'elf') $this->mod_alchymist += 5; 
		if ($player->race == 'human') $this->mod_alien += 5; 
		if ($player->race == 'half-ogre') $this->mod_healing += 5; 
    if ($player->race == 'elf') $this->mod_magic_defense += 15; 

    
		if (Trait::checkTrait('trait_skuseny', $player->id)) {
      $this->mod_melee += 15;
      $this->mod_ranged += 15;
      $this->mod_alien += 15;
      $this->mod_magic_attack += 15;
      $this->mod_magic_defense += 15;
      $this->mod_merchant += 15;
      $this->mod_thief += 15;
      $this->mod_gambling += 15;
      $this->mod_healing += 15;
      $this->mod_alchymist += 15;
    }

    if (Trait::checkTrait('trait_nadany', $player->id)) {
      $this->mod_melee -= 15;
      $this->mod_ranged -= 15;
      $this->mod_alien -= 15;
      $this->mod_magic_attack -= 15;
      $this->mod_magic_defense -= 15;
      $this->mod_merchant -= 15;
      $this->mod_thief -= 15;
      $this->mod_gambling -= 15;
      $this->mod_healing -= 15;
      $this->mod_alchymist -= 15;
    }
    
    if (Trait::checkTrait('trait_surovec', $player->id)) {
      $this->mod_melee += 15;
      $this->mod_ranged += 15;
      $this->mod_merchant -= 15;
      $this->mod_healing -= 15;
    }

    if (Trait::checkTrait('trait_humanista', $player->id)) {
      $this->mod_melee -= 15;
      $this->mod_ranged -= 15;
      $this->mod_merchant += 15;
      $this->mod_healing += 15;
    }

    if (Trait::checkTrait('trait_psychopat', $player->id)) {
      $this->mod_melee += 15;
      $this->mod_magic_attack += 15;
      $this->mod_magic_defense -= 10;
      $this->mod_merchant -= 10;
      $this->mod_healing -= 10;
    }
    
		if (Trait::checkTrait('trait_technomaniak', $player->id)) {
      $this->mod_alien += 20;
      $this->mod_magic_attack -= 10;
      $this->mod_magic_defense -= 10;
    }
    
		if (Trait::checkTrait('trait_gambling', $player->id)) {
      $this->mod_merchant -= 25;
      $this->mod_gambling += 25;
    }
    


    
    
    if (isset($_POST['selectskills'])) {
			
			$spent = 0;
			$spent += $this->spentPoints($_POST['melee'], $this->base_melee + $this->mod_melee );
			$spent += $this->spentPoints($_POST['ranged'], $this->base_ranged + $this->mod_ranged);
			$spent += $this->spentPoints($_POST['alien'], $this->base_alien + $this->mod_alien);
			$spent += $this->spentPoints($_POST['magic_attack'], $this->base_magic_attack + $this->mod_magic_attack);
			$spent += $this->spentPoints($_POST['magic_defense'], $this->base_magic_defense + $this->mod_magic_defense);
			$spent += $this->spentPoints($_POST['merchant'], $this->base_merchant + $this->mod_merchant );
			$spent += $this->spentPoints($_POST['thief'], $this->base_thief + $this->mod_thief );
			$spent += $this->spentPoints($_POST['gambling'], $this->base_gambling + $this->mod_gambling);
			$spent += $this->spentPoints($_POST['healing'], $this->base_healing + $this->mod_healing);
			$spent += $this->spentPoints($_POST['alchymist'], $this->base_alchymist + $this->mod_alchymist);
			
			if ($spent == $this->zostavajuce_zrucnosti) {
				$player->baseMelee = $this->base_melee + $this->mod_melee;
				$player->baseRanged = $this->base_ranged + $this->mod_ranged;
				$player->baseAlien = $this->base_alien + $this->mod_alien;
				$player->baseAttackMagic = $this->base_magic_attack + $this->mod_magic_attack;
				$player->baseDefenseMagic = $this->base_magic_defense + $this->mod_magic_defense;
				$player->baseMerchant = $this->base_merchant + $this->mod_merchant;
				$player->baseThief = $this->base_thief + $this->mod_thief;
				$player->baseGambling = $this->base_gambling + $this->mod_gambling;
				$player->baseHealing = $this->base_healing + $this->mod_healing;
				$player->baseAlchemy = $this->base_alchymist + $this->mod_alchymist;
				$player->save();
				$player->setRegisterState(4);
				//header('location:'.URL);
				//exit();
			}
      
    }
    
    
    
    
  }
	
	
	function spentPoints($posted, $base) {
		$spent = 0;
		$points = $base; 
		
		while ($points < $posted) {
			$points++;
			if ($points >= 150) { 
				$spent += 4; 
			} elseif ($points >= 100) { 
				$spent += 3; 
			} elseif ($points >= 50) { 
				$spent += 2; 
			} else {
				$spent += 1; 
			}
			
		}
		
		return $spent;
		
		
	}
  
  // funkcia, ktora vypise modifikator zrucnosti
	function ShowModifier($int){
		if ($int > 0) {
			return '(+'.$int.')';
		}
		if ($int < 0) {
			return '('.$int.')';
		}
	}
  
  public function DisplayContent() {
      $player = Player::actualPlayer();
      $attr = $player->attributes;
			
			echo '
          <script type="text/javascript">
            zostavajuce_zrucnosti = '.$this->zostavajuce_zrucnosti.';
              
            function Spotreba(hod){
              if (hod > 150) { return 4; }
              if ((hod <= 150) && (hod > 100)) { return 3; }
              if ((hod <= 100) && (hod > 50)) { return 2; }
              if (hod <= 50) { return 1; }
              }
              
            function Skill(meno,operacia){
              minimum = 0;
              switch (meno) {
                case "melee": minimum = '.$this->base_melee.'; break;
                case "ranged": minimum = '.$this->base_ranged.'; break;
                case "alien": minimum = '.$this->base_alien.'; break;
                case "magic_attack": minimum = '.$this->base_magic_attack.'; break;
                case "magic_defense": minimum = '.$this->base_magic_defense.'; break;
                case "merchant": minimum = '.$this->base_merchant.'; break;
                case "thief": minimum = '.$this->base_thief.'; break;
                case "gambling": minimum = '.$this->base_gambling.'; break;
                case "healing": minimum = '.$this->base_healing.'; break;
                case "alchymist": minimum = '.$this->base_alchymist.'; break;
              }
              if ((operacia == \'+\') && (zostavajuce_zrucnosti >= Spotreba(parseInt(document.forms[0][meno].value)+1))) {
                document.forms[0][meno].value = parseInt(document.forms[0][meno].value)+1+"%";
                zostavajuce_zrucnosti = zostavajuce_zrucnosti - Spotreba(parseInt(document.forms[0][meno].value)+1);
              	document.forms[0]["zostav"].value = zostavajuce_zrucnosti;
              }
              if ((operacia == \'-\') && ((parseInt(document.forms[0][meno].value)) > minimum)) {
              	document.forms[0][meno].value = parseInt(document.forms[0][meno].value)-1+"%";
              	zostavajuce_zrucnosti = zostavajuce_zrucnosti + Spotreba(parseInt(document.forms[0][meno].value)-1);
              	document.forms[0]["zostav"].value = zostavajuce_zrucnosti;
              }
            }

          </script>';
			
			// tooltipy
			CreateToolTip(0,'','Body, ktoré môžeš prerozdeliť medzi zručnosti akokoľvek chceš<br>Čím viac percent už máš v danej zručnosti, tým viac bodov minieš na jedno percento.');
			CreateToolTip(1,'Boj s chladnými zbraňami - zbraňami na boj z blízka',' - nože, meče, sekery, skrutkovače, válčeky na cesto, kladivá, lopaty...');
			CreateToolTip(2,'Streľba z klasických strelných zbraní',' - luky, kuše, praky');
			CreateToolTip(3,'Používanie hi-tech zbraní cudzincov',' - pištole, pušky, samopale, motorové píly, miniguny, guľomety, bazooky, granátomety...');
			CreateToolTip(4,'Útočné kúzla',' - magické výboje, firebally, nekromancia, hnilobné a oslepovacie kúzla, choroby...');
			CreateToolTip(5,'Obranné kúzla',' - liečenia, štíty, regeneračné kúzla, aury, vyvolávanie lesných tvorov...');
			CreateToolTip(6,'Obchodovanie','Obchodovanie určuje cenu predávaných a kupovaných predmetov. Čím viac percent, tým lacnejší nákup a drahší predaj u obchodníkov.');
			CreateToolTip(7,'thief','thief určuje šancu na úspech krádeží. Okrem toho určuje aj množstvo ukradnutých šupín (peňazí). ');
			CreateToolTip(8,'gambling','Šanca na výhru v hazardných hrách. Úspešnosť závisí okrem toho aj od atribútu Šťastie.');
			CreateToolTip(9,'Liečenie','Určuje šancu na úspešné liečenie a počet vyliečených Hit-Pointov.');
			CreateToolTip(10,'Výroba lektvarov - Alchýmia','Úspešnosť výroby lektvarov. Úspech závisí aj od zložitosti lektvaru.');
			
			echo '<h1>Zručnosti - krok 5/6</h1>';
			
			// text
			echo '<div class="create_player_div" style="left: 300px; top: 60px; width: 500px; height: 400px; text-align: left;">';
			echo 'Zručnosti určujú ako dobre tvoja postava dokáže vykonávať jednotlivé činnosti v hre. Ich počiatočná hodnota je daná atribútmi, a mierne upravená osobnostnými črtami a rasou. Počas hry ich však budeš zvyšovať za body, ktoré dostaneš každý level.<br><br>';
			echo 'Na zlepšenie zručnosti o 1% potrebuješ rôzny počet bodov podľa toho, na akom percente už danú zručnosť máš:<br><br>';
			echo '49% a menej - 1 bod za 1%<br>';
			echo '50-99% - 2 body za 1%<br>';
			echo '100-149% - 3 body za 1%<br>';
			echo '150% a viac - 4 body za 1%<br><br>';
			echo 'Prvé tri sú bojové zručnosti - tie udávajú šancu tvojej postavy na zásah v boji podľa toho, akú zbraň práve používa.<br><br>';
			echo 'Dve magické zručnosti určujú šancu na zoslanie kúzla. (útočného, či obranného)<br><br>';
			echo 'Tri sociálne zručnosti udávajú cenu predmetov pri obchodovaní, šancu na uthief predmetu, ži šancu na výhru pri hazarde.<br><br>';
			echo 'Posledné 2 zručnosti ovplyvňujú šancu na úspešnú výrobu lektvarov, či množstvo Hit-Pointov, ktoré sa ti podarí za ťah vyliečiť.';
			echo '</div>';
			
			// formular
			echo '<div class="create_player_div" style="left: 20px; top: 60px; width: 250px; height: 400px; text-align: left;">';
			echo '<form action="'.$_SERVER['PHP_SELF'].'" method="post">';
			

			echo '<div '.UseToolTip(0).' style="position: absolute; width: 220px; top: 12px; left: 10px; font-size: 14px !important; font-size: 16px; font-weight: bold;">ZOSTÁVAJÚCE BODY: </div>';
			echo '<input '.UseToolTip(0).' id="zostav" name="zostav" type="text" readonly value="'.$this->zostavajuce_zrucnosti.'" style="background-color: transparent; border-style: none; color: rgb(200,200,255); font-weight: bold; font-size: 22px; font-family: bookman; position: absolute; left: 185px; width: 30px;">';
			
			echo '	<div onclick="Skill(\'melee\',\'+\')" class="create_player_button" style="top: 40px; left: 67px;">+</div>';
			echo '	<div onclick="Skill(\'melee\',\'-\')" class="create_player_button" style="top: 40px; left: 10px;">-</div>';
			echo '	<div '.UseToolTip(1).' style="position: absolute; width: 180px; top: 40px; left: 91px; font-size: 16px;">chladné zbrane '.$this->ShowModifier($this->mod_melee).'</div>';
			echo '	<input id="melee" name="melee" class="create_player_input" readonly type="text" style="top: 40px; left: 30px; width: 33px; margin: 0px; padding: 0px;" value="'.($this->base_melee + $this->mod_melee).'%">';
			
			echo '	<div onclick="Skill(\'ranged\',\'+\')" class="create_player_button" style="top: 60px; left: 67px;">+</div>';
			echo '	<div onclick="Skill(\'ranged\',\'-\')" class="create_player_button" style="top: 60px; left: 10px;">-</div>';
			echo '	<div '.UseToolTip(2).' style="position: absolute; width: 180px; top: 60px; left: 91px; font-size: 16px;">strelné zbrane '.$this->ShowModifier($this->mod_ranged).'</div>';
			echo '	<input id="ranged" name="ranged" class="create_player_input" readonly type="text" style="top: 60px; left: 30px; width: 33px; margin: 0px; padding: 0px;" value="'.($this->base_ranged + $this->mod_ranged).'%">';
			
			echo '	<div onclick="Skill(\'alien\',\'+\')" class="create_player_button" style="top: 80px; left: 67px;">+</div>
              <div onclick="Skill(\'alien\',\'-\')" class="create_player_button" style="top: 80px; left: 10px;">-</div>
              <div '.UseToolTip(3).' style="position: absolute; width: 180px; top: 80px; left: 91px; font-size: 16px;">cudzinecké zbrane '.$this->ShowModifier($this->mod_alien).'</div>
              <input id="alien" name="alien" class="create_player_input" readonly type="text" style="top: 80px; left: 30px; width: 33px; margin: 0px; padding: 0px;" value="'.($this->base_alien + $this->mod_alien).'%">

              <div onclick="Skill(\'magic_attack\',\'+\')" class="create_player_button" style="top: 110px; left: 67px;">+</div>
              <div onclick="Skill(\'magic_attack\',\'-\')" class="create_player_button" style="top: 110px; left: 10px;">-</div>
              <div '.UseToolTip(4).' style="position: absolute; width: 180px; top: 110px; left: 91px; font-size: 16px;">útočná mágia '.$this->ShowModifier($this->mod_magic_attack).'</div>
              <input id="magic_attack" name="magic_attack" class="create_player_input" readonly type="text" style="top: 110px; left: 30px; width: 33px; margin: 0px; padding: 0px;" value="'.($this->base_magic_attack + $this->mod_magic_attack).'%">
              
              <div onclick="Skill(\'magic_defense\',\'+\')" class="create_player_button" style="top: 130px; left: 67px;">+</div>
              <div onclick="Skill(\'magic_defense\',\'-\')" class="create_player_button" style="top: 130px; left: 10px;">-</div>
              <div '.UseToolTip(5).' style="position: absolute; width: 180px; top: 130px; left: 91px; font-size: 16px;">obranná mágia '.$this->ShowModifier($this->mod_magic_defense).'</div>
              <input id="magic_defense" name="magic_defense" class="create_player_input" readonly type="text" style="top: 130px; left: 30px; width: 33px; margin: 0px; padding: 0px;" value="'.($this->base_magic_defense + $this->mod_magic_defense).'%">

              <div onclick="Skill(\'merchant\',\'+\')" class="create_player_button" style="top: 160px; left: 67px;">+</div>
              <div onclick="Skill(\'merchant\',\'-\')" class="create_player_button" style="top: 160px; left: 10px;">-</div>
              <div '.UseToolTip(6).' style="position: absolute; width: 180px; top: 160px; left: 91px; font-size: 16px;">obchodovanie '.$this->ShowModifier($this->mod_merchant).'</div>
              <input id="merchant" name="merchant" class="create_player_input" readonly type="text" style="top: 160px; left: 30px; width: 33px; margin: 0px; padding: 0px;" value="'.($this->base_merchant + $this->mod_merchant).'%">
              
              <div onclick="Skill(\'thief\',\'+\')" class="create_player_button" style="top: 180px; left: 67px;">+</div>
              <div onclick="Skill(\'thief\',\'-\')" class="create_player_button" style="top: 180px; left: 10px;">-</div>
              <div '.UseToolTip(7).' style="position: absolute; width: 180px; top: 180px; left: 91px; font-size: 16px;">thief '.$this->ShowModifier($this->mod_thief).'</div>
              <input id="thief" name="thief" class="create_player_input" readonly type="text" style="top: 180px; left: 30px; width: 33px; margin: 0px; padding: 0px;" value="'.($this->base_thief + $this->mod_thief).'%">
              

              <div onclick="Skill(\'gambling\',\'+\')" class="create_player_button" style="top: 200px; left: 67px;">+</div>
              <div onclick="Skill(\'gambling\',\'-\')" class="create_player_button" style="top: 200px; left: 10px;">-</div>
              <div '.UseToolTip(8).' style="position: absolute; width: 180px; top: 200px; left: 91px; font-size: 16px;">gambling '.$this->ShowModifier($this->mod_gambling).'</div>
              <input id="gambling" name="gambling" class="create_player_input" readonly type="text" style="top: 200px; left: 30px; width: 33px; margin: 0px; padding: 0px;" value="'.($this->base_gambling + $this->mod_gambling).'%">
              
              <div onclick="Skill(\'healing\',\'+\')" class="create_player_button" style="top: 230px; left: 67px;">+</div>
              <div onclick="Skill(\'healing\',\'-\')" class="create_player_button" style="top: 230px; left: 10px;">-</div>
              <div '.UseToolTip(9).' style="position: absolute; width: 180px; top: 230px; left: 91px; font-size: 16px;">liečenie '.$this->ShowModifier($this->mod_healing).'</div>
              <input id="healing" name="healing" class="create_player_input" readonly type="text" style="top: 230px; left: 30px; width: 33px; margin: 0px; padding: 0px;" value="'.($this->base_healing + $this->mod_healing).'%">

              <div onclick="Skill(\'alchymist\',\'+\')" class="create_player_button" style="top: 250px; left: 67px;">+</div>
              <div onclick="Skill(\'alchymist\',\'-\')" class="create_player_button" style="top: 250px; left: 10px;">-</div>
              <div '.UseToolTip(10).' style="position: absolute; width: 180px; top: 250px; left: 91px; font-size: 16px;">výroba lektvarov '.$this->ShowModifier($this->mod_alchymist).'</div>
              <input id="alchymist" name="alchymist" class="create_player_input" readonly type="text" style="top: 250px; left: 30px; width: 33px; margin: 0px; padding: 0px;" value="'.($this->base_alchymist + $this->mod_alchymist).'%">

              <input class="submit_button" type="submit" name="selectskills" value="Pokračovať krokom 6" style="position: absolute; left: 45px; top: 370px;">
            </form>
          </div>';
  }
  
}
		// cast 6





?>