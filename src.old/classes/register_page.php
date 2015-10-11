<?php


//require '../security.php';
require 'class_not_logged_page.php';

require_once 'functions/tooltips.php';

class RegisterPage extends NOT_LOGGED_PAGE
{

  private $db = null;
  
  private $nickError = '';
  private $characterNameError = '';
  private $passwordError = '';
  private $password2Error = '';
  private $emailError = '';
  private $genderError = '';
  
  public function __construct() {
    $this->db = MySQLConnection::getInstance();
		
		Styles::addStyle('title_page');
    
    if (isset($_POST['register'])) {
      $ok = true;
      
      if (!isset($_POST['nick']) || ($_POST['nick'] == '')) {
        $ok = false;
        $this->nickError = 'Nezadal si login.';
      }
      
      if (isset($_POST['nick']) && (strlen($_POST['nick']) > 32)) {
        $ok = false;
        $this->nickError = 'Login je príliš dlhý. Maximum je 32 znakov.';
      }
				
      if (!isset($_POST['heslo1']) || ($_POST['heslo1'] == ''))  {
        $ok = false;
        $this->passwordError = 'Nezadal si heslo.';
      }
      if (isset($_POST['heslo1']) && (strlen($_POST['heslo1']) > 32)) {
        $ok = false;
        $this->passwordError = 'Heslo je príliš dlhé. Maximum je 32 znakov.';
      }
				
			if (isset($_POST['heslo1']) && isset($_POST['heslo2']) && ($_POST['heslo1'] != $_POST['heslo2'])) {
        $ok = false;
        $this->password2Error = 'Nesprávne si zopakoval heslo.';
      }
				
      if (!preg_match('/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/',$_POST['email'])) {
        $ok = false;
        $this->emailError = 'Nezadal si správnu emailovú adresu.';
      }
        
      if (!isset($_POST['meno_postavy']) || ($_POST['meno_postavy'] == '')) {
        $ok = false;
        $this->characterNameError = 'Nezadal si meno postavy.';
      }
			
      if (isset($_POST['meno_postavy']) && (strlen($_POST['meno_postavy']) > 32)) {
        $ok = false;
        $this->characterNameError =  'Meno tvojej postavy je príliš dlhé. Maximum je 32 znakov.';
      }
				
      if (!isset($_POST['pohlavie']) || (($_POST['pohlavie'] != 'male') && ($_POST['pohlavie'] != 'female'))) $err = $err.'Nebolo vybraté pohlavie, alebo jeho hodnota nie je správna.\n';
				
      if ($ok) {
        $nick = $_POST['nick'];
        $characterName = $_POST['meno_postavy'];
        $gender = $_POST['pohlavie'];
        $password = $_POST['heslo1'];
        $email = $_POST['email'];
      
        Player::newPlayer($nick, $characterName, $gender, $password, $email);
      
        Player::login($nick, $password);
        header('Location: '.URL);
        //print_r($_SESSION);
        //echo $nick . ' '.$password;
      
        exit(0);
      }
      
    }
    
    
    


  }

	// globalna funkcia, ktora naplni premenne s hodnotami atributov a zrucnosti
	function FillValues(){
		// atributy: 
		$this->mod_sila = 0;
		if (isset($_POST['race']) && ($_POST['race'] == 'half-ogre')) $this->mod_sila++;
		if (isset($_POST['trait_sprt']) && ($_POST['trait_sprt'] == 'on')) $this->mod_sila--;
		if (isset($_POST['trait_bitkar']) && ($_POST['trait_bitkar'] == 'on')) $this->mod_sila++;
		$this->mod_vydrz = 0;
		if (isset($_POST['race']) && ($_POST['race'] == 'dwarf')) $this->mod_vydrz++;
		if (isset($_POST['race']) && ($_POST['race'] == 'half-ogre')) $this->mod_vydrz++;
		$this->mod_inteligencia = 0;
		if (isset($_POST['race']) && ($_POST['race'] == 'kremon')) $this->mod_inteligencia++;
		if (isset($_POST['trait_sprt']) && ($_POST['trait_sprt'] == 'on')) $this->mod_inteligencia++;
		if (isset($_POST['trait_bitkar']) && ($_POST['trait_bitkar'] == 'on')) $this->mod_inteligencia--;
		$this->mod_rychlost = 0;
		if (isset($_POST['race']) && ($_POST['race'] == 'human')) $this->mod_rychlost++;
		$this->mod_charizma = 0;
		if (isset($_POST['trait_mutant']) && ($_POST['trait_mutant'] == 'on')) $this->mod_charizma--;
		if (isset($_POST['race']) && ($_POST['race'] == 'half-ogre')) $this->mod_charizma--;
		if (isset($_POST['race']) && ($_POST['race'] == 'elf')) $this->mod_charizma++;
		$this->mod_stastie = 0;
		if (isset($_POST['race']) && ($_POST['race'] == 'gnome')) $this->mod_stastie++;
		$this->mod_zostavajuce = 0;
		if (isset($_POST['trait_nadany']) && ($_POST['trait_nadany'] == 'on')) $this->mod_zostavajuce += 5;
		
		$this->zostavajuce = 20 + $this->mod_zostavajuce;
		
		// zrucnosti: 
		if (isset($_POST['race']) && isset($_POST['sila']) && isset($_POST['vydrz']) && isset($_POST['inteligencia']) && isset($_POST['rychlost']) && isset($_POST['charizma']) && isset($_POST['stastie'])) {
		$this->zostavajuce_zrucnosti = 5*$_POST['inteligencia'];
		
		$this->base_chladne_zbrane = min(49,3*$_POST['sila']+$_POST['vydrz']+$_POST['rychlost']);
		$this->base_strelne_zbrane = min(49,$_POST['sila']+$_POST['vydrz']+2*$_POST['rychlost']+$_POST['stastie']);
		$this->base_cudzinecke_zbrane = min(49,2*$_POST['inteligencia']+$_POST['vydrz']+2*$_POST['sila']);
		$this->base_utocna_magia = min(49,4*$_POST['inteligencia']+$_POST['rychlost']);
		$this->base_obranna_magia = min(49,4*$_POST['inteligencia']+$_POST['charizma']);
		$this->base_obchodovanie = min(49,3*$_POST['charizma']+2*$_POST['inteligencia']);
		$this->base_kradnutie = min(49,3*$_POST['rychlost']+2*$_POST['stastie']);
		$this->base_gamblovanie = min(49,3*$_POST['stastie']+2*$_POST['inteligencia']);
		$this->base_liecenie = min(49,2*$_POST['inteligencia']+$_POST['stastie']+2*$_POST['vydrz']);
		$this->base_vyroba_lektvarov = min(49,3*$_POST['inteligencia']+$_POST['rychlost']+$_POST['stastie']);
		
		$this->mod_chladne_zbrane = 0;
		if (isset($_POST['trait_skuseny']) && ($_POST['trait_skuseny'] == 'on')) $this->mod_chladne_zbrane += 15;
		if (isset($_POST['trait_nadany']) && ($_POST['trait_nadany'] == 'on')) $this->mod_chladne_zbrane -= 15;
		if ($_POST['race'] == 'half-ogre') $this->mod_chladne_zbrane += 15; 
		if (isset($_POST['trait_surovec']) && ($_POST['trait_surovec'] == 'on')) $this->mod_chladne_zbrane += 15;
		if (isset($_POST['trait_humanista']) && ($_POST['trait_humanista'] == 'on')) $this->mod_chladne_zbrane -= 15;
		if (isset($_POST['trait_psychopat']) && ($_POST['trait_psychopat'] == 'on')) $this->mod_chladne_zbrane += 15;
		$this->mod_strelne_zbrane = 0;
		if (isset($_POST['trait_skuseny']) && ($_POST['trait_skuseny'] == 'on')) $this->mod_strelne_zbrane += 15;
		if (isset($_POST['trait_nadany']) && ($_POST['trait_nadany'] == 'on')) $this->mod_strelne_zbrane -= 15;
		if ($_POST['race'] == 'human') $this->mod_strelne_zbrane += 10; 
		if (isset($_POST['trait_surovec']) && ($_POST['trait_surovec'] == 'on')) $this->mod_strelne_zbrane += 15;
		if (isset($_POST['trait_humanista']) && ($_POST['trait_humanista'] == 'on')) $this->mod_strelne_zbrane -= 15;
		$this->mod_cudzinecke_zbrane = 0;
		if (isset($_POST['trait_skuseny']) && ($_POST['trait_skuseny'] == 'on')) $this->mod_cudzinecke_zbrane += 15;
		if (isset($_POST['trait_nadany']) && ($_POST['trait_nadany'] == 'on')) $this->mod_cudzinecke_zbrane -= 15;
		if (isset($_POST['trait_technomaniak']) && ($_POST['trait_technomaniak'] == 'on')) $this->mod_cudzinecke_zbrane += 20;
		if ($_POST['race'] == 'human') $this->mod_cudzinecke_zbrane += 5; 
		$this->mod_utocna_magia = 0;
		if (isset($_POST['trait_skuseny']) && ($_POST['trait_skuseny'] == 'on')) $this->mod_utocna_magia += 15;
		if (isset($_POST['trait_nadany']) && ($_POST['trait_nadany'] == 'on')) $this->mod_utocna_magia -= 15;
		if (isset($_POST['trait_technomaniak']) && ($_POST['trait_technomaniak'] == 'on')) $this->mod_utocna_magia -= 10;
		if ($_POST['race'] == 'human') $this->mod_utocna_magia += 5; 
		if ($_POST['race'] == 'kremon') $this->mod_utocna_magia += 20; 
		if (isset($_POST['trait_psychopat']) && ($_POST['trait_psychopat'] == 'on')) $this->mod_utocna_magia += 15;
		$this->mod_obranna_magia = 0;
		if (isset($_POST['trait_skuseny']) && ($_POST['trait_skuseny'] == 'on')) $this->mod_obranna_magia += 15;
		if (isset($_POST['trait_nadany']) && ($_POST['trait_nadany'] == 'on')) $this->mod_obranna_magia -= 15;
		if (isset($_POST['trait_technomaniak']) && ($_POST['trait_technomaniak'] == 'on')) $this->mod_obranna_magia -= 10;
		if ($_POST['race'] == 'elf') $this->mod_obranna_magia += 15; 
		if (isset($_POST['trait_psychopat']) && ($_POST['trait_psychopat'] == 'on')) $this->mod_obranna_magia -= 10;
		$this->mod_obchodovanie = 0;
		if (isset($_POST['trait_skuseny']) && ($_POST['trait_skuseny'] == 'on')) $this->mod_obchodovanie += 15;
		if (isset($_POST['trait_nadany']) && ($_POST['trait_nadany'] == 'on')) $this->mod_obchodovanie -= 15;
		if ($_POST['race'] == 'dwarf') $this->mod_obchodovanie += 20; 
		if (isset($_POST['trait_gambling']) && ($_POST['trait_gambling'] == 'on')) $this->mod_obchodovanie -= 25;
		if (isset($_POST['trait_surovec']) && ($_POST['trait_surovec'] == 'on')) $this->mod_obchodovanie -= 15;
		if (isset($_POST['trait_humanista']) && ($_POST['trait_humanista'] == 'on')) $this->mod_obchodovanie += 15;
		if (isset($_POST['trait_psychopat']) && ($_POST['trait_psychopat'] == 'on')) $this->mod_obchodovanie -= 10;
		$this->mod_kradnutie = 0;
		if (isset($_POST['trait_skuseny']) && ($_POST['trait_skuseny'] == 'on')) $this->mod_kradnutie += 15;
		if (isset($_POST['trait_nadany']) && ($_POST['trait_nadany'] == 'on')) $this->mod_kradnutie -= 15;
		if ($_POST['race'] == 'gnome') $this->mod_kradnutie += 20; 
		$this->mod_gamblovanie = 0;
		if (isset($_POST['trait_skuseny']) && ($_POST['trait_skuseny'] == 'on')) $this->mod_gamblovanie += 15;
		if (isset($_POST['trait_nadany']) && ($_POST['trait_nadany'] == 'on')) $this->mod_gamblovanie -= 15;
		if (isset($_POST['trait_gambling']) && ($_POST['trait_gambling'] == 'on')) $this->mod_gamblovanie += 25;
		$this->mod_liecenie = 0;
		if (isset($_POST['trait_skuseny']) && ($_POST['trait_skuseny'] == 'on')) $this->mod_liecenie += 15;
		if (isset($_POST['trait_nadany']) && ($_POST['trait_nadany'] == 'on')) $this->mod_liecenie -= 15;
		if ($_POST['race'] == 'half-ogre') $this->mod_liecenie += 5; 
		if (isset($_POST['trait_surovec']) && ($_POST['trait_surovec'] == 'on')) $this->mod_liecenie -= 15;
		if (isset($_POST['trait_humanista']) && ($_POST['trait_humanista'] == 'on')) $this->mod_liecenie += 15;
		if (isset($_POST['trait_psychopat']) && ($_POST['trait_psychopat'] == 'on')) $this->mod_liecenie -= 10;
		$this->mod_vyroba_lektvarov = 0;
		if (isset($_POST['trait_skuseny']) && ($_POST['trait_skuseny'] == 'on')) $this->mod_vyroba_lektvarov += 15;
		if (isset($_POST['trait_nadany']) && ($_POST['trait_nadany'] == 'on')) $this->mod_vyroba_lektvarov -= 15;
		if ($_POST['race'] == 'elf') $this->mod_vyroba_lektvarov += 5; 
		}
		
		// kuzla:
		if (isset ($_POST['inteligencia'])) {
			$this->kuzla_pocet = floor($_POST['inteligencia'] / 3);
		}
	}


	
	

	// pripisanie <SCRIPT> tagu na koniec hlavicky
	/*function DisplayHead($css_file,$title){
		parent::DisplayHead($css_file,$title);
		// engine pre tool-tipy
		echo '<SCRIPT language="JavaScript1.2" src="create_player_tooltips.js" type="text/javascript""></SCRIPT>';
	}*/
	
	
	// funkcia, ktora vypise error - javascriptovym alertom
	function DisplayError($err){
		echo '<SCRIPT>alert("CHYBA:\n'.$err.'");</SCRIPT>';
	}
	
	// funkcia, ktora vypocita, kolko bodov na zrucnosti bolo pouzitych podla hodnoty, na ktorej je dana zrucnost
	function Spotreba($hod,$base){
		
		if ($hod > 149) {
		// 150 a viac
			return 49 + 100 + 150 + ($hod - 149)*4 - $base;
		} elseif ($hod > 99) {
		// 100-149
			return 49 + 100 + ($hod - 99)*3 - $base;
		} elseif ($hod > 49) {
		// 50-99
			return 49 + ($hod - 49)*2 - $base;
		} else {
		// 0-49 => 1 bod
			return $hod - $base;
		}
	}

	// funkcia, ktora skontroluje formular v prislusnom kroku a ak je chyba, vrati ju. ak nie je chyba, tak vrati prazdny string
	function CheckForm($step){
		$err = '';
		switch ($step) {
			case 2:
				$err = $err.$this->CheckForm(1);
				if (!isset($_POST['race']) || ($_POST['race'] == '') || 
					(($_POST['race'] != 'human') && ($_POST['race'] != 'elf') && ($_POST['race'] != 'dwarf') && ($_POST['race'] != 'gnome') && ($_POST['race'] != 'half-ogre') && ($_POST['race'] != 'kremon'))
					) $err = $err.'Nevybral si si rasu.\n';
				break;
			case 3:
				$err = $err.$this->CheckForm(2);
				$trait_count = 0;
				
				if (array_keys($_POST) != array_unique(array_keys($_POST))) $err = $err.'Niektoré traity alebo kúzla sa opakujú.\n';
				
				foreach ($_POST as $key => $value) {
					if ((substr($key,0,6) == 'trait_') && ($value == 'on')) $trait_count++;
				}
				if ($trait_count != 2) $err = $err.'Musíš vybrať práve 2 osobnostné črty. Vybral si '.$trait_count.'.\n';
				
				foreach ($_POST as $key => $value) {
					if ((substr($key,0,6) == 'trait_') && ($value == 'on')) {
						if (($key != 'trait_mutant') &&
							($key != 'trait_vampir') &&
							($key != 'trait_tenka_postava') &&
							($key != 'trait_nadany') &&
							($key != 'trait_skuseny') &&
							($key != 'trait_gambler') &&
							($key != 'trait_surovec') &&
							($key != 'trait_humanista') &&
							($key != 'trait_psychopat') &&
							($key != 'trait_sprt') &&
							($key != 'trait_oziareny') &&
							($key != 'trait_bitkar') &&
							($key != 'trait_kamikadze') &&
							($key != 'trait_technomaniak')
						) {
							$err = $err.'Nerozpoznané traity.\n';
						}
					}
				}
				
				break;
			case 4:
				$err = $err.$this->CheckForm(3);
				if (!isset($_POST['sila']) || !isset($_POST['vydrz']) || !isset($_POST['inteligencia']) || !isset($_POST['rychlost']) || !isset($_POST['charizma']) || !isset($_POST['stastie'])) $err = $err.'Niektoré atribúty nie sú definované.\n';
				$kontrolny_sucet = $this->min_sila+$this->min_vydrz+$this->min_inteligencia+$this->min_rychlost+$this->min_charizma+$this->min_stastie+$this->mod_sila+$this->mod_vydrz+$this->mod_inteligencia+$this->mod_rychlost+$this->mod_charizma+$this->mod_stastie+$this->zostavajuce;
				$sucet = $_POST['sila']+$_POST['vydrz']+$_POST['inteligencia']+$_POST['rychlost']+$_POST['charizma']+$_POST['stastie'];
				if ($sucet < $kontrolny_sucet) $err = $err.'Nepoužil si všetky body.\n';
				if ($sucet > $kontrolny_sucet) $err = $err.'Pokus o neoprávnené zvýšenie atribútov. Hanba ti!\n';
				break;
			case 5:
				$err = $err.$this->CheckForm(4);
				if (!isset($_POST['chladne_zbrane']) || !isset($_POST['strelne_zbrane']) || !isset($_POST['cudzinecke_zbrane']) || !isset($_POST['utocna_magia']) || !isset($_POST['obranna_magia']) || !isset($_POST['obchodovanie']) || !isset($_POST['kradnutie']) || !isset($_POST['gamblovanie']) || !isset($_POST['liecenie']) || !isset($_POST['vyroba_lektvarov'])) $err = $err.'Niektoré zručnosti nie sú definované.\n';
				$kontrolny_sucet = $this->zostavajuce_zrucnosti;
				$sucet = $this->Spotreba($_POST['chladne_zbrane'],$this->base_chladne_zbrane)+$this->Spotreba($_POST['strelne_zbrane'],$this->base_strelne_zbrane)+$this->Spotreba($_POST['cudzinecke_zbrane'],$this->base_cudzinecke_zbrane)+$this->Spotreba($_POST['obranna_magia'],$this->base_obranna_magia)+$this->Spotreba($_POST['utocna_magia'],$this->base_utocna_magia)+$this->Spotreba($_POST['obchodovanie'],$this->base_obchodovanie)+$this->Spotreba($_POST['kradnutie'],$this->base_kradnutie)
						+$this->Spotreba($_POST['gamblovanie'],$this->base_gamblovanie)+$this->Spotreba($_POST['liecenie'],$this->base_liecenie)+$this->Spotreba($_POST['vyroba_lektvarov'],$this->base_vyroba_lektvarov);
				if ($sucet < $kontrolny_sucet) $err = $err.'Nepoužil si všetky body. Zostalo ti '.($kontrolny_sucet - $sucet).' nevyužitých bodov.\n';
				if ($sucet > $kontrolny_sucet) $err = $err.'Pokus o neoprávnené zvýšenie zručností. Hanba ti!\n';
				break;
			case 6:
				$err = $err.$this->CheckForm(5);
				$kuzla_count = 0;
				foreach ($_POST as $key => $value) {
					if ((substr($key,0,6) == 'kuzlo_') && ($value == 'on')) $kuzla_count++;
				}
				if ($kuzla_count != $this->kuzla_pocet) $err = $err.'Máš si vybrať '.$this->kuzla_pocet.' kúzla. (Vybral si '.$kuzla_count.')';
				
				foreach ($_POST as $key => $value) {
					if ((substr($key,0,6) == 'kuzlo_') && ($value == 'on') && 
						($key != 'kuzlo_nizsie_liecenie') &&
						($key != 'kuzlo_tvrda_koza') &&
						($key != 'kuzlo_pomoc_prirody1') &&
						($key != 'kuzlo_magicky_vyboj') &&
						($key != 'kuzlo_pomoc_zahrobia1') &&
						($key != 'kuzlo_slepota') 
						) $err = $err.'Nerozpoznané kúzlo.\n';
				}
				
				break;
		}
		
		return $err;
	}

	function DisplayPart($number){
		// cast 1
		if ($number == 1){
			require('register/step1.php');
		} elseif ($number == 2){
			require('register/step2.php');
		} elseif ($number == 3){
      require('register/step3.php');
		} elseif ($number == 4){
      require('register/step4.php');
		} elseif ($number == 5){
      require('register/step5.php');
		} elseif ($number == 6){
      require('register/step6.php');
		} elseif ($number == 7){
      require('register/step7.php');
    }
		
	}

	function DisplayContent() {
    
    
echo '<h2>Prihlasovacie údaje - krok 1/6</h2>';
			// uvodny text:
			echo '
        <p>
          V prvom kroku tvorby postavy musíš zadať údaje, ktoré budeš potrebovať pre prihlásenie do hry - LOGIN a HESLO, ďalej E-MAIL, na ktorý ti zašleme nové heslo v prípade, že staré zabudneš.<br/><br/>Ďalšie údaje, ktoré vypĺňaš v prvom kroku sú MENO a POHLAVIE, ktoré sa budú zobrazovať v hre.
        </p>
          ';
          
			echo '
        <form action="'.URL.'register" method="post" id="regform">
				<div style="background-color:black;border:solid 1px white;padding:5px;">
					<p>
						<label for="inick">Nick:</label></td>
            <input id="inick" name="nick" type="text" '.(isset($_POST['register'])?('value="'.$_POST['nick'].'"'):'').' />
            '.(isset($_POST['register'])?('<span class="error">'.$this->nickError.'</span>'):'').'
          </p>
          <p>
            <label for="ipass">Heslo</label>
            <input id="ipass" name="heslo1" type="password" />
            '.(isset($_POST['register'])?('<span class="error">'.$this->passwordError.'</span>'):'').'
          </p>
          <p>
            <label for="ipass2">Potvrdenie hesla</label>
            <input id="ipass2" name="heslo2" type="password" />
            '.(isset($_POST['register'])?('<span class="error">'.$this->password2Error.'</span>'):'').'
          </p>
          <p>
            <label for="iemail">Email</label>
            <input id="iemail" name="email" type="text" '.(isset($_POST['register'])?('value="'.$_POST['email'].'"'):'').' />
            '.(isset($_POST['register'])?('<span class="error">'.$this->emailError.'</span>'):'').'
          </p>
          <p>
            <label for="iname">Meno postavy</label>
            <input id="iname" name="meno_postavy" type="text" '.(isset($_POST['register'])?('value="'.$_POST['meno_postavy'].'"'):'').' />
            '.(isset($_POST['register'])?('<span class="error">'.$this->characterNameError.'</span>'):'').'
          </p>
          <p>
            Pohlavie postavy:             
            <label for="male">muž</label><input type="radio" id="male" value="male" checked="checked" name="pohlavie" />
            <label for="female">žena</label><input type="radio" id="female" value="female" name="pohlavie" />
          </p>
          <p>
            <input type="submit" name="register" value="Pokračovať krokom 2" />
          </p>
				</div>
        </form>'. /* popisky k formularu: */ '
        ';
    
    
		
	}


}



?>
