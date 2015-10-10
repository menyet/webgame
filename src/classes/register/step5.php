<?php

require_once('class_not_logged_page.php');
require_once('functions/tooltips.php');

class RegisterSelectSkills extends  NOT_LOGGED_PAGE {
			
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
}


?>