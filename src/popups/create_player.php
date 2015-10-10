<?php
require '../security.php';
require '../classes/class_external_script.php';

class CREATE_PLAYER extends EXTERNAL_SCRIPT
{

	// vrati id hraca, ktoreho chceme vlozit
	function GetPlayerID() {
		$ret = -1;
		$res = mysql_query('SELECT id FROM players',DATABASE);
		while ($line = mysql_fetch_assoc($res)) {
			if ($line['id'] > $ret) $ret = $line['id'];
		}
		return $ret + 1;
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
		$this->min_sila = 2;
		$this->min_vydrz = 2;
		$this->min_inteligencia = 5;
		$this->min_rychlost = 5;
		$this->min_charizma = 1;
		$this->min_stastie = 1;
		$this->max = 10;
		
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


	
	// funkcia, ktora vypise modifikator zrucnosti
	function ShowModifier($int){
		if ($int > 0) {
			return '(+'.$int.')';
		}
		if ($int < 0) {
			return '('.$int.')';
		}
	}

	// pripisanie <SCRIPT> tagu na koniec hlavicky
	function DisplayHead($css_file,$title){
		parent::DisplayHead($css_file,$title);
		// engine pre tool-tipy
		echo '<SCRIPT language="JavaScript1.2" src="create_player_tooltips.js" type="text/javascript""></SCRIPT>';
	}
	
	// funkcia, ktora do dokumentu vpise hidden inputy s informaciami z predchadzajucich krokov
	function DisplayHiddenInfo($step){
		switch ($step) {
			case 2:
				echo '<input type="hidden" name="login" value="'.$_POST['login'].'">
				    <input type="hidden" name="heslo1" value="'.$_POST['heslo1'].'">
				    <input type="hidden" name="heslo2" value="'.$_POST['heslo2'].'">';
				echo '<input type="hidden" name="email" value="'.$_POST['email'].'">';
				echo '<input type="hidden" name="meno_postavy" value="'.$_POST['meno_postavy'].'">';
				echo '<input type="hidden" name="pohlavie" value="'.$_POST['pohlavie'].'">';
				break;
			case 3:
				$this->DisplayHiddenInfo(2);
				echo '<input type="hidden" name="race" value="'.$_POST['race'].'">';
				break;
			case 4:
				$this->DisplayHiddenInfo(2);
				$this->DisplayHiddenInfo(3);
				foreach ($_POST as $key => $value) {
					if ((substr($key,0,6) == 'trait_') && ($value == 'on')) echo '<input type="hidden" name="'.$key.'" value="on">';
				}
				break;
			case 5:
				$this->DisplayHiddenInfo(2);
				$this->DisplayHiddenInfo(3);
				$this->DisplayHiddenInfo(4);
				echo '<input type="hidden" name="sila" value="'.$_POST['sila'].'">';
				echo '<input type="hidden" name="vydrz" value="'.$_POST['vydrz'].'">';
				echo '<input type="hidden" name="inteligencia" value="'.$_POST['inteligencia'].'">';
				echo '<input type="hidden" name="rychlost" value="'.$_POST['rychlost'].'">';
				echo '<input type="hidden" name="charizma" value="'.$_POST['charizma'].'">';
				echo '<input type="hidden" name="stastie" value="'.$_POST['stastie'].'">';
				break;
			case 6:
				$this->DisplayHiddenInfo(2);
				$this->DisplayHiddenInfo(3);
				$this->DisplayHiddenInfo(4);
				$this->DisplayHiddenInfo(5);
				echo '<input type="hidden" name="chladne_zbrane" value="'.$_POST['chladne_zbrane'].'">';
				echo '<input type="hidden" name="strelne_zbrane" value="'.$_POST['strelne_zbrane'].'">';
				echo '<input type="hidden" name="cudzinecke_zbrane" value="'.$_POST['cudzinecke_zbrane'].'">';
				echo '<input type="hidden" name="utocna_magia" value="'.$_POST['utocna_magia'].'">';
				echo '<input type="hidden" name="obranna_magia" value="'.$_POST['obranna_magia'].'">';
				echo '<input type="hidden" name="obchodovanie" value="'.$_POST['obchodovanie'].'">';
				echo '<input type="hidden" name="kradnutie" value="'.$_POST['kradnutie'].'">';
				echo '<input type="hidden" name="gamblovanie" value="'.$_POST['gamblovanie'].'">';
				echo '<input type="hidden" name="liecenie" value="'.$_POST['liecenie'].'">';
				echo '<input type="hidden" name="vyroba_lektvarov" value="'.$_POST['vyroba_lektvarov'].'">';
				break;
		}
	}
	
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
			case 1:
				if (!isset($_POST['login']) || ($_POST['login'] == '')) $err = $err.'Nezadal si login.\n';
				if (isset($_POST['login']) && (strlen($_POST['login']) > 32)) $err = $err.'Login je príliš dlhý. Maximum je 32 znakov.\n';
				
				if (!isset($_POST['heslo1']) || ($_POST['heslo1'] == '')) $err = $err.'Nezadal si heslo.\n';
				if (isset($_POST['heslo1']) && (strlen($_POST['heslo1']) > 32)) $err = $err.'Heslo je príliš dlhé. Maximum je 32 znakov.\n';
				
				if (!isset($_POST['heslo2']) || ($_POST['heslo2'] == '')) $err = $err.'Nezopakoval si heslo.\n';
				if (isset($_POST['heslo2']) && (strlen($_POST['heslo2']) > 32)) $err = $err.'Heslo je príliš dlhé. Maximum je 32 znakov.\n';
				
				if (isset($_POST['heslo1']) && isset($_POST['heslo2']) && ($_POST['heslo1'] != $_POST['heslo2'])) $err = $err.'Nesprávne si zopakoval heslo.\n';
				
				if (!isset($_POST['email']) || ($_POST['email'] == '')) $err = $err.'Nezadal si e-mail.\n';
				if (isset($_POST['email']) && (strlen($_POST['email']) > 32)) $err = $err.'Mailová adresa je príliš dlhá. Maximum je 32 znakov.\n';
				
				if (!isset($_POST['meno_postavy']) || ($_POST['meno_postavy'] == '')) $err = $err.'Nezadal si meno postavy.\n';
				if (isset($_POST['meno_postavy']) && (strlen($_POST['meno_postavy']) > 32)) $err = $err.'Meno tvojej postavy je príliš dlhé. Maximum je 32 znakov.\n';
				
				if (!isset($_POST['pohlavie']) || (($_POST['pohlavie'] != 'male') && ($_POST['pohlavie'] != 'female'))) $err = $err.'Nebolo vybraté pohlavie, alebo jeho hodnota nie je správna.\n';
				
				if (isset($_POST['login']) && ($_POST['login'] != '')){
					$res = mysql_query('SELECT username FROM players WHERE username =\''.$_POST['login'].'\'',DATABASE);
					if (mysql_num_rows($res) > 0) $err = $err.'Požadovaný login sa už používa. Vyber si iný.\n';
				}
				if (isset($_POST['meno_postavy']) && ($_POST['meno_postavy'] != '')){
					$res = mysql_query('SELECT username FROM players WHERE name =\''.$_POST['meno_postavy'].'\'',DATABASE);
					if (mysql_num_rows($res) > 0) $err = $err.'Požadované meno hráča sa už používa. Vyber si iné.\n';
				}
				break;
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
			echo '<h1>Prihlasovacie údaje - krok 1/6</h1>';
			// uvodny text:
			echo '<div class="create_player_div" style="left: 20px; top: 60px; width: 786px; height: 70px;">';
			echo 'V prvom kroku tvorby postavy musíš zadať údaje, ktoré budeš potrebovať pre prihlásenie do hry - LOGIN a HESLO, ďalej E-MAIL, na ktorý ti zašleme nové heslo v prípade, že staré zabudneš.<br><br>Ďalšie údaje, ktoré vypĺňaš v prvom kroku sú MENO a POHLAVIE, ktoré sa budú zobrazovať v hre.';
			echo '</div>';
			
			echo '<div class="create_player_div"  style="left: 20px; top: 155px; width: 786px; height: 230px; text-align: center;">';
			// formular:
			echo '<form action="'.$_SERVER['PHP_SELF'].'" method="post">';
			
			echo '<input name="login" class="create_player_input" type="text" style="top: 10px; left: 10px;">';
			echo '<input name="heslo1" class="create_player_input" type="password" style="top: 32px; left: 10px;">';
			echo '<input name="heslo2" class="create_player_input" type="password" style="top: 54px; left: 10px;">';
			echo '<input name="email" class="create_player_input" type="text" style="top: 76px; left: 10px;">';
			
			echo '<input name="meno_postavy" class="create_player_input" type="text" style="top: 110px; left: 10px;">';
			
			echo '<input class="create_player_input" type="radio" style="top: 170px; left: 10px;" value="male" checked="checked" name="pohlavie" id="male">';
			echo '<input class="create_player_input" type="radio" style="top: 190px; left: 10px;" value="female" name="pohlavie" id="female">';
			
			echo '<label style="top: 170px; left: 35px; position: absolute;" for="male">muž</label>';
			echo '<label style="top: 190px; left: 35px; position: absolute;" for="female">žena</label>';
			
			echo '<input class="submit_button" type="submit" name="submit1" value="Pokračovať krokom 2" style="position: relative; top: 190px;">';
			echo '</form>';
			// popisky k formularu:
			echo '<div style="position: absolute; top: 10px; left: 195px !important; left: 165px; font-size: 16px; padding: 1px; color: rgb(210,220,240);"><b>LOGIN</b></div>';
			echo '<div style="position: absolute; top: 32px; left: 195px !important; left: 165px; font-size: 16px; padding: 1px; color: rgb(210,220,240);"><b>HESLO</b></div>';
			echo '<div style="position: absolute; top: 54px; left: 195px !important; left: 165px; font-size: 16px; padding: 1px; color: rgb(210,220,240);"><b>HESLO (zopakovať)</b></div>';
			echo '<div style="position: absolute; top: 76px; left: 195px !important; left: 165px; font-size: 16px; padding: 1px; color: rgb(210,220,240);"><b>E-MAIL</b></div>';
			echo '<div style="position: absolute; top: 110px; left: 195px !important; left: 165px; font-size: 16px; padding: 1px; color: rgb(210,220,240);"><b>MENO POSTAVY</b></div>';
			echo '<div style="position: absolute; top: 147px; left: 15px; font-size: 16px; padding: 1px; color: rgb(210,220,240);"><b>POHLAVIE POSTAVY</b></div>';
			
			echo '</div>';
			
		}
		// cast 2
		if ($number == 2){
			
			
			echo '<h1>Rasa - krok 2/6</h1>';
			
			echo '<img id="nahlad" src="../images/create_player/human.gif" style="position: absolute; left:20px; top: 60px; border-color: rgb(200,250,220); border-style: solid; border-width: 1px; -moz-border-radius: 6px;">';
			
			echo '<div class="create_player_div"  style="font-size: 13px; left: 180px; top: 60px; width: 626px; height: 100px;">';
			echo 'Výber rasy je dôležitým krokom pri tvorbe tvojej postavy. Skôr ako si vyberieš svoju rasu, mal by si sa rozhodnúť akým štýlom chceš hrať. Existuje množstvo spôsobov ako prežiť a prosperovať vo svete Wasteland a na každý z nich sa hodí iná rasa. Ak sa napríklad chceš živiť ako obchodník, najlepší pre vás pravdepodobne bude Trpaslík. Ak však považuješ za najlepšie riešenie problémov pár fireballov, tvoja voľba bude skôr démonický Kremon.<br><br>Pozorne si preto prečítaj aké má ktorá rasa výhody a nevýhody a vyber si to, čo ti najviac vyhovuje.';
			echo '</div>';
			
			echo '<div class="create_player_div"  style="left: 20px; top: 398px; width: 786px; height: 40px;">';
			echo '<b>POZOR:</b> Výber rasy priamo ovplyvňuje niektoré udalosti v hre. To znamená, že k rôznym rasám sa niektoré NPC postavy budú správať rôzne. Navyše niektoré questy sú určené iba pre konkrétne rasy.';
			echo '</div>';
			
			echo '<div class="create_player_div"  style="padding: 0px; margin: 0px; left: 180px; top: 185px; width: 640px; height: 202px;">';
			
			echo '<form action="'.$_SERVER['PHP_SELF'].'" method="post">';
			
			$this->DisplayHiddenInfo(2);

			echo '<ul style="list-style: none; font-size: 18px; margin: 10px; padding: 0px;">';
			echo '<li>';
			$this->CreateToolTip(0,'Človek','Človek je jedným z tvorov, ktorí majú vo svete najpočetnejšie zastúpenie. Oproti ostatným rasám majú pomerne krátky život, no zdá sa že ľudskú rasu práve to robí mocnejšou. Môžu si hrdo povedať, ze svoje krátke životy žijú naplno. Majú viac potomkov ako ostatné rasy (s výnikou Gnomov) a ich deti dospievajú v nižsom veku. Žijú rýchly a uponáhľaný život a snažia sa silou-mocou o zachovanie silnej pozície svojej rasy. Bohužiaľ na to vo väčšine prípadov neváhajú použiť násilie. Celkom pochopiteľne majú sklony používať silu ako najrýchlejšie riešenie problémov napriek tomu, že fyzickou silou neprevyšujú ostatné tvory. V očiach iných rás sú ľudia často zákernými bytosťami, ktorým nemožno celkom dôverovať. Možno nie náhodou boli všetci votrelci v osudnej Veľkej vojne práve ľudia.<br><br><b>+ 10% strelné zbrane<br>+ 5% cudzinecké zbrane a útočná mágia<br>+ 1 rýchlosť</b>');
			$this->CreateToolTip(1,'Elf','Nevieme či je pravdou, že Elfovia sú najstaršou rasu sveta, ale s určitosťou môžme povedať, že sú rasou najviac namyslenou. Pre svoj vznešený výzor a nezaujaté vystupovanie sú často obdivovaní ostatnými tvormi. Elfovia majú sklony vyvyšovať sa nad ostatné rasy, aj keď je otázne, či na to majú aj objektívne dôvody. Priemerný Elf sa dožíva 300 až 400 rokov a svoj život trávi v harmónii s prírodou. Výzorovo sa najviac ponáša na Človeka, s niekoľkými drobnými rozdielmi. Postava Elfa je o niečo štíhlejšia a tvár zdobia dlhšie zašpicatené uši. Elfovia sú často dobrými mágmi, no špecializujú sa skôr na obrannú a liečebnú mágiu. Spory riešia radšej slovami ako bojom. <br><br><b>+ 15% obranná mágia<br>+ 5% výroba lektvarov<br>+ 1 charizma</b>');
			$this->CreateToolTip(2,'Trpaslík','Trpaslíci majú dlhú a krvavú minulosť. O svoje miesto na tomto svete museli dlhé stáročia bojovať s každou civilizáciou na ktorú natrafili. Dôvod je jednoduchý - bohatstvo. Trpaslíci sú geniálni obchodníci a zhromažďovanie majetku je ich vrodeným celoživotným cieľom. Práve toto bohatstvo je však zároveň silným lákadlom pre všetky barbarské, či lúpežnícke spoločenstvá. Stáročia obranných bitiek z Trpaslíkov urobili okrem obchodníkov aj schopných a statočných bojovníkov. Trpaslíci sú síce nižší ako Ľudia či Elfovia (merajú približne 140 cm), no majú silnú a odolnú stavbu tela. <br><br><b>+ 20% obchodovanie<br>+ 1 výdrž</b>');
			$this->CreateToolTip(3,'Gnom','Malí veselí ľudia, ktorí by celí život najradšej strávili spievaním niekde v krčme pri pive. Gnomov má každý rád napriek tomu, že sú to vlastne alkoholickí kleptomani. Majú zvláštnu slabosť pre všetky šperky a drahokamy a neváhajú prisvojiť si to, čo im nepatrí. Sú jednou z mladších rás, ktorá sa rýchlo rozšírila vo svete vďaka tomu, že sa veľmi rýchlo množia a udržiavajú priateľské vzťahy so všetkými tvormi. Až na výnimky je najobľúbenejšou bojovou taktikou Gnomov útek. Priemerný gnom je o pár centimetrov vyšší ako trpaslík, no má štíhlejšiu postavu. Dožíva sa približne 150 rokov. <br><br><b>+ 20% kradnutie<br>+ 1 šťastie</b>');
			$this->CreateToolTip(4,'Polo-obor','Polo-obri v podstate nie sú plnohodnotnou rasou. Sú to iba miešanci príslušníka rasy Obrov a niektorej z iných, rozšírenejších rás. Polo-obri sú vo svete zriedkaví pretože drvivá väčšina Obrov žije mimo civilizácie v horách a lesoch. O Obroch, a teda aj ich miešancoch je rozšírená povera, že sú to krvilačné, hlúpe stvorenia, žijúce v jaskyniach. Nie je to však pravda. Obri sú inteligentnou rasou, ktorá má však rada svoje súkromie. Nie sú násilní, no dokážu sa veľmi efektívne brániť. Polo-obor je asi 3 metre vysoký a zarastený hustou srsťou. Jeho telo je svalnaté, pretože skoro všetko v živote robí vlastnými rukami a bez nástrojov. <br><br><b>+ 1 sila a výdrž<br>- 1 charizma<br>+ 15% chladné zbrane<br>+ 5% liečenie</b>');
			$this->CreateToolTip(5,'Kremon','Kremoni, alebo ako sa sami zvyknú nazývať, Deimovia sú potomkami prívržencov démonov, ktorí prišli na tento svet pred troma tisícročiami. Deimovia sa jeden na druhého veľmi nepodobajú, pretože pochádzajú zo všetkých ostatných rás, no skutočnosť, že ich predkovia boli posadnutí prisluhovači pekelných tvorov je na nich na prvý pohľad vidieť. Vačšina z nich má na hlave rohy a ťahá za sebou rôzne dlhý chvost. Niektorým dokonca zostali diabolské krídla, ktoré však nemôžu používať na let. Okrem toho im zostali typické črty rasy z ktorej pochádzali ich predkovia. (elfské špicaté uši, či výška trpaslíka) Jedinou vecou, ktorú majú všetci spoločnú, sú ich démonické žlté oči. Sú to obávaní mágovia, ktorí neváhajú použiť svoju moc.<br><br><b>+ 20% útočná mágia<br>+ 1 inteligencia</b>');
			echo '<img id="img_human" src="../images/create_player/human.gif" style="visibility: hidden; position: absolute; left: -500px; top: -500px;">';
			echo '<input type="radio" name="race" value="human" id="human" onClick="nahlad.src=img_human.src" checked> ';
			echo '<label '.$this->UseToolTip(0).' onClick="nahlad.src=img_human.src"  for="human">Človek</label> ';
			echo '</li>';
			echo '<li>';
			echo '<img id="img_elf" src="../images/create_player/elf.gif" style="visibility: hidden; position: absolute; left: -500px; top: -500px;">';
			echo '<input type="radio" name="race" value="elf" id="elf" onClick="nahlad.src=img_elf.src" > ';
			echo '<label '.$this->UseToolTip(1).' onClick="nahlad.src=img_elf.src" for="elf">Elf</label> ';
			echo '</li>';
			echo '<li>';
			echo '<img id="img_dwarf" src="../images/create_player/dwarf.gif" style="visibility: hidden; position: absolute; left: -500px; top: -500px;">';
			echo '<input type="radio" name="race" value="dwarf" id="dwarf" onClick="nahlad.src=img_dwarf.src"> ';
			echo '<label '.$this->UseToolTip(2).' onClick="nahlad.src=img_dwarf.src" for="dwarf">Trpaslík</label> ';
			echo '</li>';
			echo '<li>';
			echo '<img id="img_gnome" src="../images/create_player/gnome.gif" style="visibility: hidden; position: absolute; left: -500px; top: -500px;">';
			echo '<input type="radio" name="race" value="gnome" id="gnome" onClick="nahlad.src=img_gnome.src"> ';
			echo '<label '.$this->UseToolTip(3).' onClick="nahlad.src=img_gnome.src" for="gnome">Gnom</label> ';
			echo '</li>';
			echo '<li>';
			echo '<img id="img_halfogre" src="../images/create_player/half-ogre.gif" style="visibility: hidden; position: absolute; left: -500px; top: -500px;">';
			echo '<input type="radio" name="race" value="half-ogre" id="half-ogre" onClick="nahlad.src=img_halfogre.src"> ';
			echo '<label '.$this->UseToolTip(4).' onClick="nahlad.src=img_halfogre.src" for="half-ogre">Polo-obor</label> ';
			echo '</li>';
			echo '<li>';
			echo '<img id="img_kremon" src="../images/create_player/kremon.gif" style="visibility: hidden; position: absolute; left: -500px; top: -500px;">';
			echo '<input type="radio" name="race" value="kremon" id="kremon" onClick="nahlad.src=img_kremon.src"> ';
			echo '<label '.$this->UseToolTip(5).' onClick="nahlad.src=img_kremon.src" for="kremon">Kremon / Deimos</label> ';
			echo '</li>';
			echo '</ul>';
			echo '<input class="submit_button" type="submit" name="submit2" value="Pokračovať krokom 3">';
			echo '</form>';
			
			echo '</div>';

			
		}
		// cast 3
		if ($number == 3){
			
			
			echo '<h1>Osobnostné črty - krok 3/6</h1>';
			
			// uvodny text:
			echo '<div class="create_player_div" style="left: 20px; top: 60px; width: 786px; height: 70px;">';
			echo 'Ďalším krokom je výber osobnostných čŕt postavy. Sú to jej charakteristické vlastnosti, ktoré sú či už vrodené, alebo získané v mladosti. Ich kombináciou dáš svojej postave základ unikátnej osobnosti.<br><br>Osobnostné črty sú povinné a musíš si vybrať práve 2!';
			echo '</div>';
			
			// javascript na kontrolu kolko checkboxov je oznacenych
			echo '<SCRIPT>';
			echo 'trait1 = 0;';
			echo 'trait2 = 0;';
			echo 'function CheckTraits(checkbox) {';
			echo '	if ((navigator.appName == "Netscape") && (checkbox.checked) && (checkbox != trait2) && (checkbox != trait1)) {';
			echo '		trait1.checked = false;';
			echo '		trait1 = trait2;';
			echo '		trait2 = checkbox;';
			echo '	}';
			echo '}';
			echo '</SCRIPT>';
			
			// vytvorenie tooltipov, ktore budeme pouzivat vo formulari
			$this->CreateToolTip(0,'Mutant','Biologické zbrane, ktoré Cudzinci použili vo Veľkej vojne na tebe a tvojej rodine zanechali stopy. Tvoja koža vplyvom mutácie získala podarené zelené zafarbenie a časti z nej z času na čas odpadávajú. V podstate si obluda. Na druhej strane ťa ale zhrubnutá koža lepšie chráni pred zranením. A ten pekný bonsaj, čo ti rastie z hlavy vážne nemá chybu. <br><br><b>+ 10 armor<br>- 1 charizma </b>');
			$this->CreateToolTip(1,'Vampír','V mladosti ťa uhryzol upír. Okrem toho, že si zomrel to má na tvoj život (?) aj iné vplyvy. Tvoje zmysly sú zostrené počas noci, no cez deň ti slnečné svetlo vôbec nerobí dobre. <br><br><b>+ 20% šanca na zásah pri boji v noci<br>- 20% šanca na zásah pri boji cez deň</b>');
			$this->CreateToolTip(2,'Štíhla postava','Nech robíš čokoľvek, nedarí sa ti pribrať. Tvoja mierne vyziabnutá stavba tela však pre teba nikdy nebola problémom. Práve naopak. Si obratnejší a ľahšie sa vyhýbaš útokom. Čo na tom, že nie si žiaden kulturista? <br><br><b>+ 10 armor<br>- 15kg nosnosť</b>');
			$this->CreateToolTip(3,'Nadaný','Si všeobecne talentovaný. Vo všetkom, do čoho si sa pustil, si bol lepší ako tvoji kamaráti bez toho, aby si sa nejako snažil. Na dobré sa však ľahko zvyká a tak si zlenivel. Nechcelo sa ti študovať a zdokonaľovať svoje schopnosti. <br><br><b>+ 5 bodov na atribúty<br>- 15% z každej zručnosti<br>- 5 bodov na zručnosti získaných za level </b>');
			$this->CreateToolTip(4,'Skúsený','Väčšinu svojho života si strávil sebazdokonaľovaním. Každú voľnú chvíľku si študoval, alebo trénoval všetko čo vieš. To, že si na seba taký tvrdý však spôsobí, že počas života premrháš niektoré šance získať rôzne extra schopnosti. <br><br><b>+ 15% na každý skill<br>+ 5 bodov na skilly za level<br>perk získaš každé 4 levely namiesto troch</b>');
			$this->CreateToolTip(5,'Gambler','Si chorý. Si závislý na hazarde, ale nikdy si to nepriznáš. Od malička si oberal svojich kamarátov o najlepšie hračky rôznymi stávkami a hrami, no pravá vášeň začala až keď sa ti do rúk dostali hracie karty. Čím viac gambluješ, tým viac sa ti to oplatí. Keď však prehrávaš, neváhaš rozpredať pod cenu všetko čo máš, len aby si mohol hrať ďalej.<br><br><b>+ 25% gambling<br>- 25% obchodovanie</b>');
			$this->CreateToolTip(6,'Surovec','Si tyran. Nič ťa nebaví tak, ako šikanovanie slabších. Vekom si sa v tom stále zdokonaľoval, hoci aj na úkor ostatných schopností a vedomostí. Vyjednávaš radšej zbraňami ako slovami. Pre tvoj prístup ťa miluje každý hrobár v okolí.<br><br><b>+ 15% chladné a strelné zbrane<br>- 15% obchod a liečenie</b>');
			$this->CreateToolTip(7,'Humanista','Násilie ti bolo vždy odporné. Ak si vyslovene nemusel, snažil si sa vyhnúť zbraniam a vôbec všetkým otvoreným konfliktom. Radšej sa z problémov vykecáš, ako by si mal za sebou nechávať kopy mŕtvol.<br><br><b>+ 15% obchod a liečenie<br>- 15% chladné a strelné zbrane</b>');
			$this->CreateToolTip(8,'Psychopat','Rád o sebe tvrdíš, že si zabil viac ľudí ako mor. Si asociálny maniak, ktorý trpí keď netrpia všetci okolo neho. Na naplnenie tvojich zvrátených chúťok neváhaš použiť okrem zbraní ani mágiu. Oveľa radšej spôsobuješ bolesť, ako ju odstraňuješ, takže z teba nikdy nebude dobrý liečiteľ. <br><br><b>+ 15% útočná mágia a chladné zbrane<br>- 10% liečenie, obranná mágia a obchodovanie</b>');
			$this->CreateToolTip(9,'Šprt','Mladosť si strávil s nosom v knihách. Štúdium bolo pre teba vždy príťažlivejšie ako fyzické aktivity. Tvoja inteligencia je nadpriemerná, no oproti ostatným strácaš na sile. <br><br><b>+ 1 inteligencia<br>- 1 sila</b>');
			$this->CreateToolTip(10,'Ožiarený','Tomu, že v tme mierne svietiš vďačíš rádioaktívnemu žiareniu z čias Veľkej vojny s Cudzincami. Ich mocné zbrane spôsobili, že okolo seba máš zelenkastú žiaru, ktorá ti svieti na cestu. Okrem toho, že ľahšie zasiahneš v noci nepriateľa, nemusíš si ani svietiť, keď potrebuješ ísť na toaletu. Ožiarenie však škodí tvojmu zdraviu. <br><br><b>+ 5% šanca na zásah pri boji v noci<br>obnova zdravia je spomalená o 2 HP za ťah</b>');
			$this->CreateToolTip(11,'Bitkár','Vyžívaš sa v krčmových bitkách. Keď zacítiš konflikt, neváhaš sa doňho zamiešať aj keď vôbec netušíš o čo ide. Tvoja sila tým získala, no príliš veľa rán do hlavy zanechali stopy na tvojom dôvtipe. <br><br><b>+ 1 sila<br>- 1 inteligencia</b>');
			$this->CreateToolTip(12,'Kamikadze','V boji ťa netrápi žiadne nebezpečie. Súsredíš sa len na to, čo robíš ty a nezaujíma ťa kto čo spraví tebe. Znášaš bolesť ľahšie ako ostatní ľudia... asi preto, že ostatní ľudia prežili v živote o pár desiatok zlomenín menej ako ty. <br><br><b>+ 1 action-point (rýchlostný bod)<br>- 10 armor</b>');
			$this->CreateToolTip(13,'Techno-maniak','Od malička ťa zaujímali všetky technológie, ktoré sa objavili vo svete počas Veľkej vojny. Cudzinecké zbrane a pomôcky ťa vždy fascinovali viac ako mágia a kúzla. <br><br><b>+ 20% cudzinecké zbrane<br>- 10% útočná a obranná mágia</b>');
			
			// formular:
			echo '<div class="create_player_div"  style="left: 20px; top: 155px; width: 786px; height: 200px; text-align: center;">';
			echo '<form action="'.$_SERVER['PHP_SELF'].'" method="post">';
			
			$this->DisplayHiddenInfo(3);

			echo '<ul style="list-style: none; margin: 0px; padding: 0px; position: absolute; top: 10px; left: 10px; text-align: left;">';
			
			echo '<li>';
			echo '<input name="trait_mutant" id="trait_mutant" type="checkbox" onChange="CheckTraits(this);">';
			echo '<label for="trait_mutant" style="margin-left: 5px;" '.$this->UseToolTip(0).'>Mutant</label>';
			echo '</li>';
			
			echo '<li>';
			echo '<input name="trait_vampir" id="trait_vampir" type="checkbox" onChange="CheckTraits(this);">';
			echo '<label for="trait_vampir" style="margin-left: 5px;" '.$this->UseToolTip(1).'>Vampír</label>';
			echo '</li>';
			
			echo '<li>';
			echo '<input name="trait_tenka_postava" id="trait_tenka_postava" type="checkbox" onChange="CheckTraits(this);">';
			echo '<label for="trait_tenka_postava" style="margin-left: 5px;" '.$this->UseToolTip(2).'>Tenká postava</label>';
			echo '</li>';
			
			echo '<li>';
			echo '<input name="trait_nadany" id="trait_nadany" type="checkbox" onChange="CheckTraits(this);">';
			echo '<label for="trait_nadany" style="margin-left: 5px;" '.$this->UseToolTip(3).'>Nadaný</label>';
			echo '</li>';
			
			echo '<li>';
			echo '<input name="trait_skuseny" id="trait_skuseny" type="checkbox" onChange="CheckTraits(this);">';
			echo '<label for="trait_skuseny" style="margin-left: 5px;" '.$this->UseToolTip(4).'>Skúsený</label>';
			echo '</li>';
			
			echo '<li>';
			echo '<input name="trait_gambler" id="trait_gambler" type="checkbox" onChange="CheckTraits(this);">';
			echo '<label for="trait_gambler" style="margin-left: 5px;" '.$this->UseToolTip(5).'>Gambler</label>';
			echo '</li>';
			
			echo '<li>';
			echo '<input name="trait_surovec" id="trait_surovec" type="checkbox" onChange="CheckTraits(this);">';
			echo '<label for="trait_surovec" style="margin-left: 5px;" '.$this->UseToolTip(6).'>Surovec</label>';
			echo '</li>';
			
			echo '</ul>';
			
			echo '<ul style="list-style: none; margin: 0px; padding: 0px; position: absolute; top: 10px; left: 180px; text-align: left;">';
			
			echo '<li>';
			echo '<input name="trait_humanista" id="trait_humanista" type="checkbox" onChange="CheckTraits(this);">';
			echo '<label for="trait_humanista" style="margin-left: 5px;" '.$this->UseToolTip(7).'>Humanista</label>';
			echo '</li>';
			
			echo '<li>';
			echo '<input name="trait_psychopat" id="trait_psychopat" type="checkbox" onChange="CheckTraits(this);">';
			echo '<label for="trait_psychopat" style="margin-left: 5px;" '.$this->UseToolTip(8).'>Psychopat</label>';
			echo '</li>';

			echo '<li>';
			echo '<input name="trait_sprt" id="trait_sprt" type="checkbox" onChange="CheckTraits(this);">';
			echo '<label for="trait_sprt" style="margin-left: 5px;" '.$this->UseToolTip(9).'>Šprt</label>';
			echo '</li>';

			echo '<li>';
			echo '<input name="trait_oziareny" id="trait_oziareny" type="checkbox" onChange="CheckTraits(this);">';
			echo '<label for="trait_oziareny" style="margin-left: 5px;" '.$this->UseToolTip(10).'>Ožiarený</label>';
			echo '</li>';

			echo '<li>';
			echo '<input name="trait_bitkar" id="trait_bitkar" type="checkbox" onChange="CheckTraits(this);">';
			echo '<label for="trait_bitkar" style="margin-left: 5px;" '.$this->UseToolTip(11).'>Bitkár</label>';
			echo '</li>';

			echo '<li>';
			echo '<input name="trait_kamikadze" id="trait_kamikadze" type="checkbox" onChange="CheckTraits(this);">';
			echo '<label for="trait_kamikadze" style="margin-left: 5px;" '.$this->UseToolTip(12).'>Kamikadze</label>';
			echo '</li>';
			
			echo '<li>';
			echo '<input name="trait_technomaniak" id="trait_technomaniak" type="checkbox" onChange="CheckTraits(this);">';
			echo '<label for="trait_technomaniak" style="margin-left: 5px;" '.$this->UseToolTip(13	).'>Techno-Maniak</label>';
			echo '</li>';
			
			echo '</ul>';
			
			echo '<input class="submit_button" type="submit" name="submit3" value="Pokračovať krokom 4" style="position: relative; top: 155px;">';
			echo '</form>';
			echo '</div>';
			
		}
		// cast 4
		if ($number == 4){
			
			
			// vytvorenie tooltipov, ktore budeme pouzivat vo formulari
			$this->CreateToolTip(0,'','Body, ktoré môžeš prerozdeliť medzi atribúty akokoľvek chceš.<br>Základná hodnota je 20, no môže byť zvýšená osobnostnou črtou Nadaný.');
			$this->CreateToolTip(1,'Sila','Fyzická sila postavy.<br> - Predmety v hre potrebujú určitú silu, aby si ich mohol použiť.<br> - Určuje, koľko kilogramov vecí môžeš mať v inventári. (10kg na 1 bod sily)<br> - Prispieva k základnému počtu Hit-Pointov (života) hodnotou 1 HP za 1 bod sily.<br> - Určuje damage doplňujúcich bojových schopností - kopov a úderov. (kop = 3xSila, úder = 2xSila)');
			$this->CreateToolTip(2,'Výdrž','Odolnosť voči útokom.<br> - Udáva počet získaných HP za level. (1 HP za 1 výdrž)<br> - Prispieva k základnému počtu Hit-Pointov (života) postavy hodnotou 2 HP za 1 bod výdrže.<br> - Prispieva k počtu vyliečených Hit-Pointov za ťah pri pohybe po svete. (počet = počet bodov vo výdrži + 1HP za každých 20% v liečení)');
			$this->CreateToolTip(3,'Inteligencia','Vynaliezavosť a dôvtip postavy.<br> - Povoľuje niektoré možnosti pri dialógoch.<br> - Určuje počet získaných bodov na zručnosti za level. (2 body za 1 inteligenčný bod)<br> - Udáva základný počet bodov na zručnosti na rozdelenie v ďalšom kroku tvorby postavy. (5 bodov za 1 inteligenčný bod)');
			$this->CreateToolTip(4,'Rýchlosť','Rýchlosť reakcií postavy.<br> - Určuje počet Action-Pointov (akčných bodov), ktoré máš k dispozícii v boji. Action-Pointy určia, koľko toho stihneš za jeden ťah v boji vykonať. Každá akcia (útok,kúzlo,vypitie lektvaru...) spotrebuje špecifický počet Action-Pointov. Počet AP je rovný počtu bodov, ktoré máš v rýchlosti.');
			$this->CreateToolTip(5,'Charizma','Výzor a vystupovanie postavy.<br> - Ovplyvňuje reakcie niektorých postáv na teba.<br> - Každé 3 body v charizme ti dovolia mať v hre jedného spoločníka. (NPC postavu, ktorá ťa nasleduje a pomáha ti v boji)');
			$this->CreateToolTip(6,'Šťastie','Šťastie postavy.<br> - Zvyšuje šance na zásah pri boji o 1% za 1 bod šťastia.<br> - Napomáha pri kradnutí a gamblovaní.<br> - Zvyšuje šancu na kritický zásah o 1% za bod šťastia. (kritické zásahy spôsobujú podstatne väčší damage)');
			
			echo '<h1>Atribúty - krok 4/6</h1>';
			
			// text
			echo '<div class="create_player_div" style="left: 300px; top: 60px; width: 500px; height: 300px; text-align: justify;">';
			echo 'Atribúty sú základnou charakteristikou tvojej postavy. V podstate od nich závisí každá jej schopnosť a vlastnosť. Okrem toho určujú počiatočnú hodnotu zručností a dávajú ti rôzne možnosti riešenia questov v hre.<br><br>Na rozdelenie máš k dispozícii '.$this->zostavajuce.' atribútových bodov. Od toho, ako ich rozdelíš priamo závisí tvoj herný štýl, tak si pozorne preštuduj čo ktorý atribút ovplyvňuje a pred rozdelením popremýšľaj.<br><br>Maximálnou hodnotou každého atribútu je 10 bodov.<br>Minimum je rôzne:<br><br>';
			echo 'SILA: '.$this->min_sila.'-'.$this->max.'<br>';
			echo 'VYDRZ: '.$this->min_vydrz.'-'.$this->max.'<br>';
			echo 'INTELIGENCIA: '.$this->min_inteligencia.'-'.$this->max.'<br>';
			echo 'RYCHLOST: '.$this->min_rychlost.'-'.$this->max.'<br>';
			echo 'CHARIZMA: '.$this->min_charizma.'-'.$this->max.'<br>';
			echo 'STASTIE: '.$this->min_stastie.'-'.$this->max.'<br>';
			echo '</div>';
			
			// skript na pridavanie a odoberanie atributov
			echo '<SCRIPT>';
			echo 'zostavajuce = '.$this->zostavajuce.';';
			echo 'function Attribute(meno,operacia){';
			
			echo '	switch (meno) {'; 
			echo '		case "sila": minimum = '.$this->min_sila.'; break;';
			echo '		case "vydrz": minimum = '.$this->min_vydrz.'; break;';
			echo '		case "inteligencia": minimum = '.$this->min_inteligencia.'; break;';
			echo '		case "rychlost": minimum = '.$this->min_rychlost.'; break;';
			echo '		case "charizma": minimum = '.$this->min_charizma.'; break;';
			echo '		case "stastie": minimum = '.$this->min_stastie.'; break;';
			echo '	}';
			
			echo '	if ((operacia == \'+\') && ((parseInt(document.forms[0][meno].value)) < '.$this->max.') && (zostavajuce > 0)) {';
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
			echo '<form action="'.$_SERVER['PHP_SELF'].'" method="post">';
			
			$this->DisplayHiddenInfo(4);
			
			// zostavajuce body
			echo '<div '.$this->UseToolTip(0).' style="position: absolute; width: 200px; top: 12px; left: 10px; font-size: 14px !important; font-size: 16px; font-weight: bold;">ZOSTÁVAJÚCE BODY: </div>';
			echo '<input id="zostav" name="zostav" type="text" readonly value="'.$this->zostavajuce.'" style="background-color: transparent; border-style: none; color: rgb(200,200,255); font-weight: bold; font-size: 22px; font-family: bookman; position: absolute; left: 185px; width: 30px;">';
			
			echo '	<div onClick="Attribute(\'sila\',\'+\')" class="create_player_button" style="top: 40px; left: 56px;">+</div>';
			echo '	<div onClick="Attribute(\'sila\',\'-\')" class="create_player_button" style="top: 40px; left: 10px;">-</div>';
			echo '	<div '.$this->UseToolTip(1).' style="position: absolute; width: 100px; top: 40px; left: 80px; font-size: 20px;">SILA</div>';
			echo '	<input id="sila" name="sila" class="create_player_input" readonly type="text" style="top: 40px; left: 30px; width: 22px; margin: 0px; padding: 0px;" value="'.($this->min_sila+$this->mod_sila).'">';
			
			echo '	<div onClick="Attribute(\'vydrz\',\'+\')" class="create_player_button" style="top: 65px; left: 56px;">+</div>';
			echo '	<div onClick="Attribute(\'vydrz\',\'-\')" class="create_player_button" style="top: 65px; left: 10px;">-</div>';
			echo '	<div '.$this->UseToolTip(2).' style="position: absolute; width: 100px; top: 65px; left: 80px; font-size: 20px;">VÝDRŽ</div>';
			echo '	<input id="vydrz" name="vydrz" class="create_player_input" readonly type="text" style="top: 65px; left: 30px; width: 22px; margin: 0px; padding: 0px;" value="'.($this->min_vydrz+$this->mod_vydrz).'">';
			
			echo '	<div onClick="Attribute(\'inteligencia\',\'+\')" class="create_player_button" style="top: 90px; left: 56px;">+</div>';
			echo '	<div onClick="Attribute(\'inteligencia\',\'-\')" class="create_player_button" style="top: 90px; left: 10px;">-</div>';
			echo '	<div '.$this->UseToolTip(3).' style="position: absolute; width: 100px; top: 90px; left: 80px; font-size: 20px;">INTELIGENCIA</div>';
			echo '	<input id="inteligencia" name="inteligencia" class="create_player_input" readonly type="text" style="top: 90px; left: 30px; width: 22px; margin: 0px; padding: 0px;" value="'.($this->min_inteligencia+$this->mod_inteligencia).'">';
			
			echo '	<div onClick="Attribute(\'rychlost\',\'+\')" class="create_player_button" style="top: 115px; left: 56px;">+</div>';
			echo '	<div onClick="Attribute(\'rychlost\',\'-\')" class="create_player_button" style="top: 115px; left: 10px;">-</div>';
			echo '	<div '.$this->UseToolTip(4).' style="position: absolute; width: 100px; top: 115px; left: 80px; font-size: 20px;">RÝCHLOSŤ</div>';
			echo '	<input id="rychlost" name="rychlost" class="create_player_input" readonly type="text" style="top: 115px; left: 30px; width: 22px; margin: 0px; padding: 0px;" value="'.($this->min_rychlost+$this->mod_rychlost).'">';
			
			echo '	<div onClick="Attribute(\'charizma\',\'+\')" class="create_player_button" style="top: 140px; left: 56px;">+</div>';
			echo '	<div onClick="Attribute(\'charizma\',\'-\')" class="create_player_button" style="top: 140px; left: 10px;">-</div>';
			echo '	<div '.$this->UseToolTip(5).' style="position: absolute; width: 100px; top: 140px; left: 80px; font-size: 20px;">CHARIZMA</div>';
			echo '	<input id="charizma" name="charizma" class="create_player_input" readonly type="text" style="top: 140px; left: 30px; width: 22px; margin: 0px; padding: 0px;" value="'.($this->min_charizma+$this->mod_charizma).'">';
			
			echo '	<div onClick="Attribute(\'stastie\',\'+\')" class="create_player_button" style="top: 165px; left: 56px;">+</div>';
			echo '	<div onClick="Attribute(\'stastie\',\'-\')" class="create_player_button" style="top: 165px; left: 10px;">-</div>';
			echo '	<div '.$this->UseToolTip(6).' style="position: absolute; width: 100px; top: 165px; left: 80px; font-size: 20px;">ŠŤASTIE</div>';
			echo '	<input id="stastie" name="stastie" class="create_player_input" readonly type="text" style="top: 165px; left: 30px; width: 22px; margin: 0px; padding: 0px;" value="'.($this->min_stastie+$this->mod_stastie).'">';
			
			echo '<input class="submit_button" type="submit" name="submit4" value="Pokračovať krokom 5" style="position: absolute; left: 45px; top: 270px;">';
			echo '</form>';
			echo '</div>';
		}
		// cast 5
		if ($number == 5){
			
			
			echo '<SCRIPT>zostavajuce_zrucnosti = '.$this->zostavajuce_zrucnosti.';</SCRIPT>';
			
			
			// skript na pridavanie a odoberanie atributov
			echo '<SCRIPT>';
			
			echo 'function Spotreba(hod){';
			echo '	if (hod > 150) { return 4; }';
			echo '	if ((hod <= 150) && (hod > 100)) { return 3; }';
			echo '	if ((hod <= 100) && (hod > 50)) { return 2; }';
			echo '	if (hod <= 50) { return 1; }';
			echo '}';
			
			echo 'function Skill(meno,operacia){';
			echo '	minimum = 0;';
			echo '	switch (meno) {'; 
			echo '		case "chladne_zbrane": minimum = '.$this->base_chladne_zbrane.'; break;';
			echo '		case "strelne_zbrane": minimum = '.$this->base_strelne_zbrane.'; break;';
			echo '		case "cudzinecke_zbrane": minimum = '.$this->base_cudzinecke_zbrane.'; break;';
			echo '		case "utocna_magia": minimum = '.$this->base_utocna_magia.'; break;';
			echo '		case "obranna_magia": minimum = '.$this->base_obranna_magia.'; break;';
			echo '		case "obchodovanie": minimum = '.$this->base_obchodovanie.'; break;';
			echo '		case "kradnutie": minimum = '.$this->base_kradnutie.'; break;';
			echo '		case "gamblovanie": minimum = '.$this->base_gamblovanie.'; break;';
			echo '		case "liecenie": minimum = '.$this->base_liecenie.'; break;';
			echo '		case "vyroba_lektvarov": minimum = '.$this->base_vyroba_lektvarov.'; break;';
			echo '	}';
			echo '	if ((operacia == \'+\') && (zostavajuce_zrucnosti >= Spotreba(parseInt(document.forms[0][meno].value)+1))) {';
			echo '		document.forms[0][meno].value = parseInt(document.forms[0][meno].value)+1+"%";';
			echo '		zostavajuce_zrucnosti = zostavajuce_zrucnosti - Spotreba(parseInt(document.forms[0][meno].value)+1);';
			echo '		document.forms[0]["zostav"].value = zostavajuce_zrucnosti;';
			echo '	}';
			echo '	if ((operacia == \'-\') && ((parseInt(document.forms[0][meno].value)) > minimum)) {';
			echo '		document.forms[0][meno].value = parseInt(document.forms[0][meno].value)-1+"%";';
			echo '		zostavajuce_zrucnosti = zostavajuce_zrucnosti + Spotreba(parseInt(document.forms[0][meno].value)-1);';
			echo '		document.forms[0]["zostav"].value = zostavajuce_zrucnosti;';
			echo '	}';
			echo '}';

			echo '</SCRIPT>';
			
			// tooltipy
			$this->CreateToolTip(0,'','Body, ktoré môžeš prerozdeliť medzi zručnosti akokoľvek chceš<br>Čím viac percent už máš v danej zručnosti, tým viac bodov minieš na jedno percento.');
			$this->CreateToolTip(1,'Boj s chladnými zbraňami - zbraňami na boj z blízka',' - nože, meče, sekery, skrutkovače, válčeky na cesto, kladivá, lopaty...');
			$this->CreateToolTip(2,'Streľba z klasických strelných zbraní',' - luky, kuše, praky');
			$this->CreateToolTip(3,'Používanie hi-tech zbraní cudzincov',' - pištole, pušky, samopale, motorové píly, miniguny, guľomety, bazooky, granátomety...');
			$this->CreateToolTip(4,'Útočné kúzla',' - magické výboje, firebally, nekromancia, hnilobné a oslepovacie kúzla, choroby...');
			$this->CreateToolTip(5,'Obranné kúzla',' - liečenia, štíty, regeneračné kúzla, aury, vyvolávanie lesných tvorov...');
			$this->CreateToolTip(6,'Obchodovanie','Obchodovanie určuje cenu predávaných a kupovaných predmetov. Čím viac percent, tým lacnejší nákup a drahší predaj u obchodníkov.');
			$this->CreateToolTip(7,'Kradnutie','Kradnutie určuje šancu na úspech krádeží. Okrem toho určuje aj množstvo ukradnutých šupín (peňazí). ');
			$this->CreateToolTip(8,'Gamblovanie','Šanca na výhru v hazardných hrách. Úspešnosť závisí okrem toho aj od atribútu Šťastie.');
			$this->CreateToolTip(9,'Liečenie','Určuje šancu na úspešné liečenie a počet vyliečených Hit-Pointov.');
			$this->CreateToolTip(10,'Výroba lektvarov - Alchýmia','Úspešnosť výroby lektvarov. Úspech závisí aj od zložitosti lektvaru.');
			
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
			echo 'Tri sociálne zručnosti udávajú cenu predmetov pri obchodovaní, šancu na ukradnutie predmetu, ži šancu na výhru pri hazarde.<br><br>';
			echo 'Posledné 2 zručnosti ovplyvňujú šancu na úspešnú výrobu lektvarov, či množstvo Hit-Pointov, ktoré sa ti podarí za ťah vyliečiť.';
			echo '</div>';
			
			// formular
			echo '<div class="create_player_div" style="left: 20px; top: 60px; width: 250px; height: 400px; text-align: left;">';
			echo '<form action="'.$_SERVER['PHP_SELF'].'" method="post">';
			
			$this->DisplayHiddenInfo(5);

			echo '<div '.$this->UseToolTip(0).' style="position: absolute; width: 220px; top: 12px; left: 10px; font-size: 14px !important; font-size: 16px; font-weight: bold;">ZOSTÁVAJÚCE BODY: </div>';
			echo '<input '.$this->UseToolTip(0).' id="zostav" name="zostav" type="text" readonly value="'.$this->zostavajuce_zrucnosti.'" style="background-color: transparent; border-style: none; color: rgb(200,200,255); font-weight: bold; font-size: 22px; font-family: bookman; position: absolute; left: 185px; width: 30px;">';
			
			echo '	<div onClick="Skill(\'chladne_zbrane\',\'+\')" class="create_player_button" style="top: 40px; left: 67px;">+</div>';
			echo '	<div onClick="Skill(\'chladne_zbrane\',\'-\')" class="create_player_button" style="top: 40px; left: 10px;">-</div>';
			echo '	<div '.$this->UseToolTip(1).' style="position: absolute; width: 180px; top: 40px; left: 91px; font-size: 16px;">chladné zbrane '.$this->ShowModifier($this->mod_chladne_zbrane).'</div>';
			echo '	<input id="chladne_zbrane" name="chladne_zbrane" class="create_player_input" readonly type="text" style="top: 40px; left: 30px; width: 33px; margin: 0px; padding: 0px;" value="'.($this->base_chladne_zbrane).'%">';
			
			echo '	<div onClick="Skill(\'strelne_zbrane\',\'+\')" class="create_player_button" style="top: 60px; left: 67px;">+</div>';
			echo '	<div onClick="Skill(\'strelne_zbrane\',\'-\')" class="create_player_button" style="top: 60px; left: 10px;">-</div>';
			echo '	<div '.$this->UseToolTip(2).' style="position: absolute; width: 180px; top: 60px; left: 91px; font-size: 16px;">strelné zbrane '.$this->ShowModifier($this->mod_strelne_zbrane).'</div>';
			echo '	<input id="strelne_zbrane" name="strelne_zbrane" class="create_player_input" readonly type="text" style="top: 60px; left: 30px; width: 33px; margin: 0px; padding: 0px;" value="'.($this->base_strelne_zbrane).'%">';
			
			echo '	<div onClick="Skill(\'cudzinecke_zbrane\',\'+\')" class="create_player_button" style="top: 80px; left: 67px;">+</div>';
			echo '	<div onClick="Skill(\'cudzinecke_zbrane\',\'-\')" class="create_player_button" style="top: 80px; left: 10px;">-</div>';
			echo '	<div '.$this->UseToolTip(3).' style="position: absolute; width: 180px; top: 80px; left: 91px; font-size: 16px;">cudzinecké zbrane '.$this->ShowModifier($this->mod_cudzinecke_zbrane).'</div>';
			echo '	<input id="cudzinecke_zbrane" name="cudzinecke_zbrane" class="create_player_input" readonly type="text" style="top: 80px; left: 30px; width: 33px; margin: 0px; padding: 0px;" value="'.($this->base_cudzinecke_zbrane).'%">';
			
			echo '	<div onClick="Skill(\'utocna_magia\',\'+\')" class="create_player_button" style="top: 110px; left: 67px;">+</div>';
			echo '	<div onClick="Skill(\'utocna_magia\',\'-\')" class="create_player_button" style="top: 110px; left: 10px;">-</div>';
			echo '	<div '.$this->UseToolTip(4).' style="position: absolute; width: 180px; top: 110px; left: 91px; font-size: 16px;">útočná mágia '.$this->ShowModifier($this->mod_utocna_magia).'</div>';
			echo '	<input id="utocna_magia" name="utocna_magia" class="create_player_input" readonly type="text" style="top: 110px; left: 30px; width: 33px; margin: 0px; padding: 0px;" value="'.($this->base_utocna_magia).'%">';
			
			echo '	<div onClick="Skill(\'obranna_magia\',\'+\')" class="create_player_button" style="top: 130px; left: 67px;">+</div>';
			echo '	<div onClick="Skill(\'obranna_magia\',\'-\')" class="create_player_button" style="top: 130px; left: 10px;">-</div>';
			echo '	<div '.$this->UseToolTip(5).' style="position: absolute; width: 180px; top: 130px; left: 91px; font-size: 16px;">obranná mágia '.$this->ShowModifier($this->mod_obranna_magia).'</div>';
			echo '	<input id="obranna_magia" name="obranna_magia" class="create_player_input" readonly type="text" style="top: 130px; left: 30px; width: 33px; margin: 0px; padding: 0px;" value="'.($this->base_obranna_magia).'%">';
			
			echo '	<div onClick="Skill(\'obchodovanie\',\'+\')" class="create_player_button" style="top: 160px; left: 67px;">+</div>';
			echo '	<div onClick="Skill(\'obchodovanie\',\'-\')" class="create_player_button" style="top: 160px; left: 10px;">-</div>';
			echo '	<div '.$this->UseToolTip(6).' style="position: absolute; width: 180px; top: 160px; left: 91px; font-size: 16px;">obchodovanie '.$this->ShowModifier($this->mod_obchodovanie).'</div>';
			echo '	<input id="obchodovanie" name="obchodovanie" class="create_player_input" readonly type="text" style="top: 160px; left: 30px; width: 33px; margin: 0px; padding: 0px;" value="'.($this->base_obchodovanie).'%">';
			
			echo '	<div onClick="Skill(\'kradnutie\',\'+\')" class="create_player_button" style="top: 180px; left: 67px;">+</div>';
			echo '	<div onClick="Skill(\'kradnutie\',\'-\')" class="create_player_button" style="top: 180px; left: 10px;">-</div>';
			echo '	<div '.$this->UseToolTip(7).' style="position: absolute; width: 180px; top: 180px; left: 91px; font-size: 16px;">kradnutie '.$this->ShowModifier($this->mod_kradnutie).'</div>';
			echo '	<input id="kradnutie" name="kradnutie" class="create_player_input" readonly type="text" style="top: 180px; left: 30px; width: 33px; margin: 0px; padding: 0px;" value="'.($this->base_kradnutie).'%">';
			
			
			echo '	<div onClick="Skill(\'gamblovanie\',\'+\')" class="create_player_button" style="top: 200px; left: 67px;">+</div>';
			echo '	<div onClick="Skill(\'gamblovanie\',\'-\')" class="create_player_button" style="top: 200px; left: 10px;">-</div>';
			echo '	<div '.$this->UseToolTip(8).' style="position: absolute; width: 180px; top: 200px; left: 91px; font-size: 16px;">gamblovanie '.$this->ShowModifier($this->mod_gamblovanie).'</div>';
			echo '	<input id="gamblovanie" name="gamblovanie" class="create_player_input" readonly type="text" style="top: 200px; left: 30px; width: 33px; margin: 0px; padding: 0px;" value="'.($this->base_gamblovanie).'%">';
			
			echo '	<div onClick="Skill(\'liecenie\',\'+\')" class="create_player_button" style="top: 230px; left: 67px;">+</div>';
			echo '	<div onClick="Skill(\'liecenie\',\'-\')" class="create_player_button" style="top: 230px; left: 10px;">-</div>';
			echo '	<div '.$this->UseToolTip(9).' style="position: absolute; width: 180px; top: 230px; left: 91px; font-size: 16px;">liečenie '.$this->ShowModifier($this->mod_liecenie).'</div>';
			echo '	<input id="liecenie" name="liecenie" class="create_player_input" readonly type="text" style="top: 230px; left: 30px; width: 33px; margin: 0px; padding: 0px;" value="'.($this->base_liecenie).'%">';
			
			echo '	<div onClick="Skill(\'vyroba_lektvarov\',\'+\')" class="create_player_button" style="top: 250px; left: 67px;">+</div>';
			echo '	<div onClick="Skill(\'vyroba_lektvarov\',\'-\')" class="create_player_button" style="top: 250px; left: 10px;">-</div>';
			echo '	<div '.$this->UseToolTip(10).' style="position: absolute; width: 180px; top: 250px; left: 91px; font-size: 16px;">výroba lektvarov '.$this->ShowModifier($this->mod_vyroba_lektvarov).'</div>';
			echo '	<input id="vyroba_lektvarov" name="vyroba_lektvarov" class="create_player_input" readonly type="text" style="top: 250px; left: 30px; width: 33px; margin: 0px; padding: 0px;" value="'.($this->base_vyroba_lektvarov).'%">';
			
			echo '<input class="submit_button" type="submit" name="submit5" value="Pokračovať krokom 6" style="position: absolute; left: 45px; top: 370px;">';
			echo '</form>';
			echo '</div>';
		}
		// cast 6
		if ($number == 6){
				
			$this->CreateToolTip(0,'Nižšie liečenie','Najslabšie z liečivých kúzel. Magicky hojí tvoje rany a zceľuje zlomené kosti, čím obnoví 8-12 Hit-Pointov.<br><br>Kategória: Obranná mágia<br>Spotreba: 4 Action-Pointy<br>Cieľ: hráč<br>Základná šanca: 50%');
			$this->CreateToolTip(1,'Tvrdá koža','Slabšia verzia kúziel Kamenná koža, či Oceľová koža. Spôsobí že tvoja pokožka stvrdne a stane sa odolnejšou voči zraneniam. Zvýši tvoj Armor o 5 na dobu 3 ťahov.<br><br>Kategória: Obranná mágia<br>Spotreba: 7 Action-Pointov<br>Cieľ: hráč<br>Základná šanca: 65%');
			$this->CreateToolTip(2,'Pomoc prírody I.','Kúzlo, ktoré rozoznie vábivý zvuk, ktorý sa šíri divočinou a privolá na tvoju ochranu jedného Havrana. Existujú aj vyššie verzie tohto kúzla, ktoré privolajú silnejšie zvieratá.<br><br>Kategória: Obranná mágia<br>Spotreba: 7 Action-Pointov<br>Cieľ: žiaden<br>Základná šanca: 60%');
			$this->CreateToolTip(3,'Magický výboj','Vytvorí výboj magickej energie, ktorý vystrelí na cieľ silou 12-15 damage.<br><br>Kategória: Útočná mágia<br>Spotreba: 5 Action-Pointov<br>Cieľ: nepriateľ<br>Základná šanca: 45%');
			$this->CreateToolTip(4,'Pomoc záhrobia I.','Vyšle do okolia záhrobné volanie, ktoré prebudí najbližšiu mŕtvolu a prinúti ju vo forme Kostlivca pomáhať hráčovi. Mocnejšie verzie tohto kúzla vyvolávajú mŕtvych v silnejších podobách.<br><br>Kategória: Útočná mágia<br>Spotreba: 7 Action-Pointov<br>Cieľ: žiaden<br>Základná šanca: 60%');
			$this->CreateToolTip(5,'Slepota','Zasiahne nepriateľove oči oslepujúcim zábleskom, ktorý mu dočasne poškodí zrak. Cieľ sa spamätá o tri ťahy, no dovtedy má zníženú šancu na zásah v boji o 15%.<br><br>Kategória: Útočná mágia<br>Spotreba: 6 Action-Pointov<br>Cieľ: nepriateľ<br>Základná šanca: 35%');
			
			// javascript na kontrolu kolko checkboxov je oznacenych
			echo '<SCRIPT>';
			echo "
			function Oznacene(){
				ozn = 0;
				if (document.forms[0]['kuzlo_nizsie_liecenie'].checked) ozn++;
				if (document.forms[0]['kuzlo_tvrda_koza'].checked) ozn++;
				if (document.forms[0]['kuzlo_pomoc_prirody1'].checked) ozn++;
				if (document.forms[0]['kuzlo_magicky_vyboj'].checked) ozn++;
				if (document.forms[0]['kuzlo_pomoc_zahrobia1'].checked) ozn++;
				if (document.forms[0]['kuzlo_slepota'].checked) ozn++;
				return ozn;
			}
			
			";
			echo '</SCRIPT>';
			
			
			echo '<h1>Kúzla - krok 6/6</h1>';
			
			// text
			echo '<div class="create_player_div" style="left: 380px; top: 60px; width: 430px; height: 360px; text-align: justify;">';
			echo "
			Posledným krokom je výber kúziel, ktoré budeš mať naštudované hneď od začiatku hry. Na výber máš 6 jednoduchých kúziel, z ktorých si
			môžeš vybrať $this->kuzla_pocet - za každé tri body v inteligencii jedno kúzlo.
			<br><br>
			Pred výberom kúzla si najskôr prečítaj na čo slúži a skontroluj si pod aký typ mágie patrí. Od toho závisí, či sa pri vyhodnotení
			šance na jeho zoslanie bude brať do úvahy zručnosť Obranná mágia, alebo Útočná mágia. 
			<br><br>
			Spotreba kúzla je počet Action-Pointov, ktoré hráč použije na zoslanie kúzla.<br>
			POZOR: Ak máš nízku rýchlosť, tak si nevyberaj kúzla s vysokou spotrebou. Napríklad pri rýchlosti 6 nebudeš môcť použiť kúzlo, 
			ktoré má spotrebu vyššiu ako 6AP.
			<br><br>
			Cieľ pri popise kúzla určuje, či sa kúzlo používa na samotného hráča, na jeho nepriaťeľov, alebo sa zosiela bez zadania cieľa. 
			(Existujú aj kúzla, ktoré sa zosielajú na spoločníkov hráča, ale tie zatiaľ nemáš na výber.)
			<br><br>
			Základná šanca je len orientačné číslo určujúce šancu na úspešné zoslanie kúzla. Šanca je totiž ešte upravovaná zručnosťami Obranná mágia a 
			Útočná mágia.
			";
			echo '</div>';
			
			// text 2
			echo '<div class="create_player_div" style="left: 20px; top: 450px; width: 790px; height: 80px; text-align: justify;">';
			echo "
			<b>POZNÁMKA: </b>Počas hry sa dostaneš k množstvu oveľa užitočnejších kúziel. Buď ich nájdeš ako zvitky s inštrukciami, alebo ťa ich 
			môžu naučiť niektoré NPC postavy. Ak sa už nejaké kúzlo naučíš, môžeš ho kedykoľvek používať v boji. Jedinou podmienkou na naučenie
			niektorých kúziel je určitá hodnota inteligencie a na jeho použitie, dostatok Action-Pointov.
			";
			echo '</div>';
			
			// formular
			echo '<div class="create_player_div" style="left: 20px; top: 60px; width: 330px; height: 360px; text-align: left: 20px;">';
			echo '<form action="'.$_SERVER['PHP_SELF'].'" method="post">';
			
			$this->DisplayHiddenInfo(6);
			
			echo '<div style="text-align: center; width: 100px; position: absolute; left: 20px;">';
			echo '<p><input onChange="if (Oznacene() > '.$this->kuzla_pocet.') checked = false;" type="checkbox" name="kuzlo_nizsie_liecenie" id="kuzlo_nizsie_liecenie"></p>';
			echo '<label for="kuzlo_nizsie_liecenie">';
			echo '<img src="../images/spells/nizsie_liecenie.gif" alt="nižšie liečenie" '.$this->UseToolTip(0).' style="cursor: Pointer;">';
			echo '</label>';
			echo '</div>';
			
			echo '<div style="text-align: center; width: 100px; position: absolute; left: 120px;">';
			echo '<p><input onChange="if (Oznacene() > '.$this->kuzla_pocet.') checked = false;" type="checkbox" name="kuzlo_tvrda_koza" id="kuzlo_tvrda_koza"></p>';
			echo '<label for="kuzlo_tvrda_koza">';
			echo '<img src="../images/spells/tvrda_koza.gif" alt="tvrdá koža" '.$this->UseToolTip(1).' style="cursor: Pointer;">';
			echo '</label>';
			echo '</div>';
			
			echo '<div style="text-align: center; width: 100px; position: absolute; left: 220px;">';
			echo '<p><input onChange="if (Oznacene() > '.$this->kuzla_pocet.') checked = false;" type="checkbox" name="kuzlo_pomoc_prirody1" id="kuzlo_pomoc_prirody1"></p>';
			echo '<label for="kuzlo_pomoc_prirody1">';
			echo '<img src="../images/spells/pomoc_prirody1.gif" alt="pomoc prírody I" '.$this->UseToolTip(2).' style="cursor: Pointer;">';
			echo '</label>';
			echo '</div>';
			
			echo '<div style="text-align: center; width: 100px; position: absolute; left: 20px; top: 120px;">';
			echo '<p><input onChange="if (Oznacene() > '.$this->kuzla_pocet.') checked = false;" type="checkbox" name="kuzlo_magicky_vyboj" id="kuzlo_magicky_vyboj"></p>';
			echo '<label for="kuzlo_magicky_vyboj">';
			echo '<img src="../images/spells/magicky_vyboj.gif" alt="magický výboj" '.$this->UseToolTip(3).' style="cursor: Pointer;">';
			echo '</label>';
			echo '</div>';
			
			echo '<div style="text-align: center; width: 100px; position: absolute; left: 120px; top: 120px;">';
			echo '<p><input onChange="if (Oznacene() > '.$this->kuzla_pocet.') checked = false;" type="checkbox" name="kuzlo_pomoc_zahrobia1" id="kuzlo_pomoc_zahrobia1"></p>';
			echo '<label for="kuzlo_pomoc_zahrobia1">';
			echo '<img src="../images/spells/pomoc_zahrobia1.gif" alt="pomoc záhrobia I" '.$this->UseToolTip(4).' style="cursor: Pointer;">';
			echo '</label>';
			echo '</div>';
			
			echo '<div style="text-align: center; width: 100px; position: absolute; left: 220px; top: 120px;">';
			echo '<p><input onChange="if (Oznacene() > '.$this->kuzla_pocet.') checked = false;" type="checkbox" name="kuzlo_slepota" id="kuzlo_slepota"></p>';
			echo '<label for="kuzlo_slepota">';
			echo '<img src="../images/spells/slepota.gif" alt="slepota" '.$this->UseToolTip(5).' style="cursor: Pointer;">';
			echo '</label>';
			echo '</div>';
			
			
			echo '<input class="submit_button" type="submit" name="submit6" value="Dokončiť tvorbu postavy" style="position: absolute; left: 75px; top: 320px;">';
			echo '</form>';
			echo '</div>';
		}
		// cast 7
		if ($number == 7){
			// posledna kontrola - kvoli refresh buttonu
		if ($this->CheckForm(6) == '') {
						
			$id = $this->GetPlayerID();
			$login = $_POST['login'];
			$meno_postavy = $_POST['meno_postavy'];
			$pohlavie = $_POST['pohlavie'];
			$race = $_POST['race'];
			$heslo = md5($_POST['heslo1']);
			$start_x = START_LOCATION_X;
			$start_y = START_LOCATION_Y;
			$hp = ($_POST['vydrz']*7 + $_POST['sila']);
			$turns_remaining = START_TAHY;
			$lastaction = time();
			$critical_chance = $_POST['stastie'];
			$perk_multiplier = 3;
				if (isset($_POST['trait_skuseny']) && ($_POST['trait_skuseny'] == 'on')) $perk_multiplier = 4;
			$base_armor = 10;
				if (isset($_POST['trait_mutant']) && ($_POST['trait_mutant'] == 'on')) $base_armor += 10;
				if (isset($_POST['trait_tenka_postava']) && ($_POST['trait_tenka_postava'] == 'on')) $base_armor += 10;
				if (isset($_POST['trait_kamikadze']) && ($_POST['trait_kamikadze'] == 'on')) $base_armor -= 10;
			$base_action_points = $_POST['rychlost'];
				if (isset($_POST['trait_kamikadze']) && ($_POST['trait_kamikadze'] == 'on')) $base_action_points++;
			$base_nosnost = $_POST['sila']*10;
				if (isset($_POST['trait_tenka_postava']) && ($_POST['trait_tenka_postava'] == 'on')) $base_nosnost -= 15;
			$base_obnova_zdravia = $_POST['vydrz']+floor($_POST['liecenie'] / 20);
				if (isset($_POST['trait_oziareny']) && ($_POST['trait_oziareny'] == 'on')) $base_obnova_zdravia -= 2;
			$mod_sanca_den = 0;
				if (isset($_POST['trait_vampir']) && ($_POST['trait_vampir'] == 'on')) $mod_sanca_den -= 20;
			$mod_sanca_noc = 0;
				if (isset($_POST['trait_vampir']) && ($_POST['trait_vampir'] == 'on')) $mod_sanca_noc += 20;
				if (isset($_POST['trait_oziareny']) && ($_POST['trait_oziareny'] == 'on')) $mod_sanca_noc += 5;
			$mod_skillpointy_za_level = 0;
				if (isset($_POST['trait_skuseny']) && ($_POST['trait_skuseny'] == 'on')) $mod_skillpointy_za_level += 5;
				if (isset($_POST['trait_nadany']) && ($_POST['trait_nadany'] == 'on')) $mod_skillpointy_za_level -= 5;
			$atribut_sila = $_POST['sila'];
			$atribut_vydrz = $_POST['vydrz'];
			$atribut_inteligencia = $_POST['inteligencia'];
			$atribut_rychlost = $_POST['rychlost'];
			$atribut_charizma = $_POST['charizma'];
			$atribut_stastie = $_POST['stastie'];
			$zrucnost_chladne_zbrane = $_POST['chladne_zbrane']+$this->mod_chladne_zbrane;
			$zrucnost_strelne_zbrane = $_POST['strelne_zbrane']+$this->mod_strelne_zbrane;
			$zrucnost_cudzinecke_zbrane = $_POST['cudzinecke_zbrane']+$this->mod_cudzinecke_zbrane;
			$zrucnost_utocna_magia = $_POST['utocna_magia']+$this->mod_utocna_magia;
			$zrucnost_obranna_magia = $_POST['obranna_magia']+$this->mod_obranna_magia;
			$zrucnost_obchodovanie = $_POST['obchodovanie']+$this->mod_obchodovanie;
			$zrucnost_kradnutie = $_POST['kradnutie']+$this->mod_kradnutie;
			$zrucnost_gamblovanie = $_POST['gamblovanie']+$this->mod_gamblovanie;
			$zrucnost_liecenie = $_POST['liecenie']+$this->mod_liecenie;
			$zrucnost_vyroba_lektvarov = $_POST['vyroba_lektvarov']+$this->mod_vyroba_lektvarov;	
				
			foreach ($_POST as $key => $value) {
				if ((substr($key,0,6) == 'trait_') && ($value == 'on')) {
					mysql_query('INSERT INTO `traits` (`player_id`, `trait_id`) VALUES (\''.$id.'\', \''.$key.'\');',DATABASE);
				}
			}
				
			$sql = "INSERT INTO `players` 
			(`id`, 
			`username`, 
			`name`, 
			`gender`, 
			`race`, 
			`password`, 
			`x`, 
			`y`, 
			`hp`, 
			`hp_max`, 
			`turns_played`, 
			`turns_remaining`, 
			`last_action`, 
			`critical_chance`, 
			`perk_multiplier`, 
			`base_armor`, 
			`base_action_points`, 
			`base_nosnost`, 
			`base_obnova_zdravia`, 
			`mod_sanca_den`, 
			`mod_sanca_noc`, 
			`mod_skillpointy_za_level`, 
			`atribut_sila`, 
			`atribut_vydrz`, 
			`atribut_inteligencia`, 
			`atribut_rychlost`, 
			`atribut_charizma`, 
			`atribut_stastie`, 
			`zrucnost_chladne_zbrane`, 
			`zrucnost_strelne_zbrane`, 
			`zrucnost_cudzinecke_zbrane`, 
			`zrucnost_utocna_magia`, 
			`zrucnost_obranna_magia`, 
			`zrucnost_obchodovanie`, 
			`zrucnost_kradnutie`, 
			`zrucnost_gamblovanie`, 
			`zrucnost_liecenie`, 
			`zrucnost_vyroba_lektvarov`
			) VALUES (
			'$id',
			'$login', 
			'$meno_postavy', 
			'$pohlavie', 
			'$race', 
			'$heslo', 
			'$start_x', 
			'$start_y', 
			'$hp', 
			'$hp', 
			'0', 
			'$turns_remaining', 
			'$lastaction', 
			'$critical_chance', 
			'$perk_multiplier', 
			'$base_armor', 
			'$base_action_points', 
			'$base_nosnost', 
			'$base_obnova_zdravia', 
			'$mod_sanca_den', 
			'$mod_sanca_noc', 
			'$mod_skillpointy_za_level', 
			'$atribut_sila', 
			'$atribut_vydrz', 
			'$atribut_inteligencia', 
			'$atribut_rychlost', 
			'$atribut_charizma', 
			'$atribut_stastie', 
			'$zrucnost_chladne_zbrane', 
			'$zrucnost_strelne_zbrane', 
			'$zrucnost_cudzinecke_zbrane', 
			'$zrucnost_utocna_magia', 
			'$zrucnost_obranna_magia', 
			'$zrucnost_obchodovanie', 
			'$zrucnost_kradnutie', 
			'$zrucnost_gamblovanie', 
			'$zrucnost_liecenie', 
			'$zrucnost_vyroba_lektvarov')";
			
			if ((mysql_num_rows(mysql_query('SELECT id FROM players WHERE username=\''.$_POST['login'].'\' OR name=\''.$_POST['meno_postavy'].'\';',DATABASE)) == 0) && ($res = mysql_query($sql,DATABASE))) {
				echo '<h1>Hotovo!</h1>';
				echo '<div style="width: 100%; text-align: center;">Tvoja postava je úspešne vytvorená a pripravená na život vo svete Wasteland.<br>Ak si pripravený aj ty, môžeš sa prihlásiť a pustiť sa do toho. Veľa šťastia!</div>';
				
				echo '<ul style="position: absolute; top: 185px; left: 180px;">';
				echo '<li>Login: '.$login.'</li>';
				echo '<li>Meno: '.$meno_postavy.'</li>';
				echo '<li>Rasa: '.$race.'</li>';
				echo '<li>Pohlavie: '.$pohlavie.'</li>';
				echo '<li>Hit-Pointov: '.$hp.'</li>';
				echo '<li>Armor: '.$base_armor.'</li>';
				echo '<li>Action-Pointov: '.$base_action_points.'</li>';
				echo '</ul>';
				echo '<ul style="position: absolute; top: 185px; left: 400px;">';
				echo '<li>Perk každé '.$perk_multiplier.' levely.</li>';
				echo '<li>Šanca na kritický zásah: '.$critical_chance.'%</li>';
				echo '<li>Body na zručnosti za level: '.($atribut_inteligencia*2 + $mod_skillpointy_za_level).'</li>';
				echo '<li>Nosnosť: '.$base_nosnost.'kg</li>';
				echo '<li>Obnova zdravia: '.$base_obnova_zdravia.' HP/ťah</li>';
				if ($mod_sanca_den > 0) echo '<li>Bonus k šanci na zásah cez deň: +'.$mod_sanca_den.'%</li>';
				if ($mod_sanca_den < 0) echo '<li>Bonus k šanci na zásah cez deň: '.$mod_sanca_den.'%</li>';
				if ($mod_sanca_noc > 0) echo '<li>Bonus k šanci na zásah v noci: +'.$mod_sanca_noc.'%</li>';
				if ($mod_sanca_noc < 0) echo '<li>Bonus k šanci na zásah v noci: '.$mod_sanca_noc.'%</li>';
				echo '</ul>';
				
				echo '<a href="javascript: window.close();" style="position: absolute; top: 400px; left: 390px;">ZAVRIEŤ</a>';
				
			} else {
				$this->DisplayError('Problem pri vkladaní do databázy. Skús to neskôr.\n'.mysql_error());
			}
			
		} else {
			$this->DisplayError($this->CheckForm(6));
		}
		}
		
	}

	function DisplayBody() {
	
		// nasleduje kod potrebny pre tool-tipy
		echo '
		<div id="tiplayer" style="visibility:hidden;position:absolute;z-index:1000;top:-100;"></DIV>
			<SCRIPT language="JavaScript1.2" >';
		// tu je definovany styl tooltipov a to v tomto poradi: Style[...]=[titleColor,TitleBgColor,TitleBgImag,TitleTextAlign,TitleFontFace,TitleFontSize,TextColor,TextBgColor,TextBgImag,TextTextAlign,TextFontFace,TextFontSize,Width,Height,BorderSize,BorderColor,Textpadding,transition number,Transition duration,Transparency level,shadow type,shadow color,Appearance behavior,TipPositionType,Xpos,Ypos] 
		echo 'Style[0]=["","","","","",,"","","","","",,,,,"",,,,,,"",,,,];';
		echo 'var TipId="tiplayer";';
		echo 'var FiltersEnabled = 0;';
		echo 'mig_clay();';
		echo '</SCRIPT>';
		
		if (isset($_POST['submit6'])){
			if ($this->CheckForm(6) == ''){
				$this->DisplayPart(7);
			} else {
				$this->DisplayError($this->CheckForm(6));
				$this->DisplayPart(6);
			}
		}
		elseif (isset($_POST['submit5'])){
			if ($this->CheckForm(5) == ''){
				$this->DisplayPart(6);
			} else {
				$this->DisplayError($this->CheckForm(5));
				$this->DisplayPart(5);
			}
		}
		elseif (isset($_POST['submit4'])){
			if ($this->CheckForm(4) == ''){
				$this->DisplayPart(5);
			} else {
				$this->DisplayError($this->CheckForm(4));
				$this->DisplayPart(4);
			}
		}
		elseif (isset($_POST['submit3'])){
			if ($this->CheckForm(3) == ''){
				$this->DisplayPart(4);
			} else {
				$this->DisplayError($this->CheckForm(3));
				$this->DisplayPart(3);
			}
		}
		elseif (isset($_POST['submit2'])){
			if ($this->CheckForm(2) == ''){
				$this->DisplayPart(3);
			} else {
				$this->DisplayError($this->CheckForm(2));
				$this->DisplayPart(2);
			}
		}
		elseif (isset($_POST['submit1'])){
			if ($this->CheckForm(1) == ''){
				$this->DisplayPart(2);
			} else {
				$this->DisplayError($this->CheckForm(1));
				$this->DisplayPart(1);
			}
		}
		else {
			$this->DisplayPart(1);
		}
	}

	function CREATE_PLAYER() {
		$this->FillValues();
		$this->Display('external_script.css','Vytvorenie nového hráča');
	}

}

	// vytvorenie objektu skriptu
	$page = new CREATE_PLAYER();

?>