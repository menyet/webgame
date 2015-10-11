<?php

require '../security.php';
require '../classes/class_external_script.php';

require '../functions/tooltips.php';
//require '../classes/class_player.php';



class PopupCharacter
{
	private $player = null;
	
	
		

	function Display() {
		
		echo '




		<div id="tiplayer" style="visibility:hidden;position:absolute;z-index:1000;top:-100;"></DIV>
			<script language="JavaScript1.2" >';
		/* tu je definovany styl tooltipov a to v tomto poradi: 
			Style[...]=[	titleColor,TitleBgColor,TitleBgImag,TitleTextAlign,TitleFontFace,TitleFontSize,
							TextColor,TextBgColor,TextBgImag,TextTextAlign,TextFontFace,TextFontSize,Width,Height,BorderSize,BorderColor,
							Textpadding,transition number,Transition duration,Transparency level,shadow type,shadow color,Appearance behavior,
							TipPositionType,Xpos,Ypos] */
		echo 'Style[0]=["","","","","","","","","","","","","100px" , ,,"",,,,,,"",,,,];
					var TipId="tiplayer";
					var FiltersEnabled = 0;
					mig_clay();
					</script>';
		
			CreateToolTip(0,'','Body, ktoré môžeš prerozdeliť medzi zručnosti akokoľvek chceš<br>Čím viac percent už máš v danej zručnosti, tým viac bodov minieš na jedno percento.');
			CreateToolTip(1,'Boj s chladnými zbraňami - zbraňami na boj z blízka',' - nože, meče, sekery, skrutkovače, válčeky na cesto, kladivá, lopaty...');
			CreateToolTip(2,'Streľba z klasických strelných zbraní',' - luky, kuše, praky');
			CreateToolTip(3,'Používanie hi-tech zbraní cudzincov',' - pištole, pušky, samopale, motorové píly, miniguny, guľomety, bazooky, granátomety...');
			CreateToolTip(4,'Útočné kúzla',' - magické výboje, firebally, nekromancia, hnilobné a oslepovacie kúzla, choroby...');
			CreateToolTip(5,'Obranné kúzla',' - liečenia, štíty, regeneračné kúzla, aury, vyvolávanie lesných tvorov...');
			CreateToolTip(6,'Obchodovanie','Obchodovanie určuje cenu predávaných a kupovaných predmetov. Čím viac percent, tým lacnejší nákup a drahší predaj u obchodníkov.');
			CreateToolTip(7,'Kradnutie','Kradnutie určuje šancu na úspech krádeží. Okrem toho určuje aj množstvo ukradnutých šupín (peňazí). ');
			CreateToolTip(8,'Gamblovanie','Šanca na výhru v hazardných hrách. Úspešnosť závisí okrem toho aj od atribútu Šťastie.');
			CreateToolTip(9,'Liečenie','Určuje šancu na úspešné liečenie a počet vyliečených Hit-Pointov.');
			CreateToolTip(10,'Výroba lektvarov - Alchýmia','Úspešnosť výroby lektvarov. Úspech závisí aj od zložitosti lektvaru.');

		
		
		
		
		
    //$attr = $this->player->attributes;
		
		
		echo '
		<form action="'.URL.'character" id="character" method="post">
			
			<div class="character_screen" id="name">
				
				<input type="hidden" name="skill_0" value="'.$this->player->baseMelee.'" />
				<input type="hidden" name="skill_1" value="'.$this->player->baseRanged.'" />
				<input type="hidden" name="skill_2" value="'.$this->player->baseAlien.'" />
				<input type="hidden" name="skill_3" value="'.$this->player->baseAttackMagic.'" />
				<input type="hidden" name="skill_4" value="'.$this->player->baseDefenseMagic.'" />
				<input type="hidden" name="skill_5" value="'.$this->player->baseMerchant.'" />
				<input type="hidden" name="skill_6" value="'.$this->player->baseThief.'" />
				<input type="hidden" name="skill_7" value="'.$this->player->baseGambling.'" />
				<input type="hidden" name="skill_8" value="'.$this->player->baseHealing.'" />
				<input type="hidden" name="skill_9" value="'.$this->player->baseAlchemy.'" />








				'.$this->player->name.' &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; '.$this->player->gender;
				

				
				
				echo '
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; '.$this->player->rank.'
			</div>



			<div class="character_screen" id="attributes">
				<div class="name">SILA</div>
				<div class="value">'.$this->player->baseStrength.'</div>

				<div class="name">VÝDRŽ</div>
				<div class="value">'.$this->player->baseEndurance.'</div>

				<div class="name">INTELIGENCIA</div>
				<div class="value">'.$this->player->baseIntelligence.'</div>

				<div class="name">RÝCHLOSŤ</div>
				<div class="value">'.$this->player->baseSpeed.'</div>

				<div class="name">CHARIZMA</div>
				<div class="value">'.$this->player->baseCharisma.'</div>

				<div class="name">ŠŤASTIE</div>
				<div class="value">'.$this->player->baseLuck.'</div>

			</div>

			<div class="character_screen" id="experience">
				<div class="name">Level</div>
				<div class="value">'.$this->player->level.'</div>

				<div class="name">Skúsenosti</div>
				<div class="value">'.$this->player->xp.'</div>

				<div class="name">Ďaľší level</div>
				<div class="value">'.$this->player->GetXPNextLevel().'</div>
			</div>

			<div class="character_screen" id="stats">
				<div class="name">HP</div>
				<div class="value">'.$this->player->hp.'/'.$this->player->maxHP.'</div>
				
				<div class="name">Základná obrana</div>
				<div class="value">'.$this->player->totalArmor.' ('.$this->player->baseArmor.')</div>

				<div class="name">Akčné body</div>
				<div class="value">'.$this->player->baseAP.'/'.$this->player->baseAP.'</div>

				<div class="name">Základná nosnosť</div>
				<div class="value">'.$this->player->baseCapacity.' ('.$this->player->totalCapacity.')</div>

				<div class="name">Obnova zdravia</div>
				<div class="value">'.$this->player->baseReplenishLife.'</div>

			</div>








			<input type="hidden" id="skill_points" value="'.$this->player->skillPoints.'" />

			<input type="hidden" id="sp_0" name="sp_0" value="'.$this->player->baseMelee.'" />
			<input type="hidden" id="sp_1" name="sp_1" value="'.$this->player->baseRanged.'" />
			<input type="hidden" id="sp_2" name="sp_2" value="'.$this->player->baseAlien.'" />
			<input type="hidden" id="sp_3" name="sp_3" value="'.$this->player->baseAttackMagic.'" />
			<input type="hidden" id="sp_4" name="sp_4" value="'.$this->player->baseDefenseMagic.'" />
			<input type="hidden" id="sp_5" name="sp_5" value="'.$this->player->baseMerchant.'" />
			<input type="hidden" id="sp_6" name="sp_6" value="'.$this->player->baseThief.'" />
			<input type="hidden" id="sp_7" name="sp_7" value="'.$this->player->baseGambling.'" />
			<input type="hidden" id="sp_8" name="sp_8" value="'.$this->player->baseHealing.'" />
			<input type="hidden" id="sp_9" name="sp_9" value="'.$this->player->baseAlchemy.'" />

			<div class="character_screen" id="skills">
				<div>Zručnosti:</div>
				<div class="name" '.useToolTip(1).'>Chladné zbrane</div>		<div class="value"><span id="val_0">'.$this->player->baseMelee.''.(($this->player->skillPoints > 0)?'</span><a href="#" onclick="add(0)">+</a>':'').'</div>
				<div class="name" '.useToolTip(2).'>Strelné zbrane</div>		<div class="value"><span id="val_1">'.$this->player->baseRanged.''.(($this->player->skillPoints > 0)?'</span><a href="#" onclick="add(1)">+</a>':'').'</div>
				<div class="name" '.useToolTip(3).'>Cudzinecké zbrane</div>	<div class="value"><span id="val_2">'.$this->player->baseAlien.''.(($this->player->skillPoints > 0)?'</span><a href="#" onclick="add(2)">+</a>':'').'</div>
				<div class="name" '.useToolTip(4).'>Útočná_mágia</div>		<div class="value"><span id="val_3">'.$this->player->baseAttackMagic.''.(($this->player->skillPoints > 0)?'</span><a href="#" onclick="add(3)">+</a>':'').'</div>
				<div class="name" '.useToolTip(5).'>Obranná mágia</div>		<div class="value"><span id="val_4">'.$this->player->baseDefenseMagic.''.(($this->player->skillPoints > 0)?'</span><a href="#" onclick="add(4)">+</a>':'').'</div>
				<div class="name" '.useToolTip(6).'>Obchodovanie</div>		<div class="value"><span id="val_5">'.$this->player->baseMerchant.''.(($this->player->skillPoints > 0)?'</span><a href="#" onclick="add(5)">+</a>':'').'</div>
				<div class="name" '.useToolTip(7).'>Krádež</div>				<div class="value"><span id="val_6">'.$this->player->baseThief.''.(($this->player->skillPoints > 0)?'</span><a href="#" onclick="add(6)">+</a>':'').'</div>
				<div class="name" '.useToolTip(8).'>Gamblovanie</div>			<div class="value"><span id="val_7">'.$this->player->baseGambling.''.(($this->player->skillPoints > 0)?'</span><a href="#" onclick="add(7)">+</a>':'').'</div>
				<div class="name" '.useToolTip(9).'>Liečenie</div>			<div class="value"><span id="val_8">'.$this->player->baseHealing.''.(($this->player->skillPoints > 0)?'</span><a href="#" onclick="add(8)">+</a>':'').'</div>
				<div class="name" '.useToolTip(10).'>Výroba lektvarov</div>	<div class="value"><span id="val_9">'.$this->player->baseAlchemy.''.(($this->player->skillPoints > 0)?'</span><a href="#" onclick="add(9)">+</a>':'').'</div>
				<hr/>
				<div class="name">VOĽNÉ ZRUČNOSTI:</div>
				<div class="value" id="val_sp">'.$this->player->skillPoints.'</div>
				
			</div>


		<div class="character_screen" id="perks"><div>
';

		$perks = Perk::getAllPerks();
		
		
		$script = '<script type="text/javascript">
			var hasPerks = Array();
			var freePerks = '.$this->player->freePerks.';

			function setPerk(id) {
				if (hasPerks[id]) {
					return;
				}

				if (document.getElementById("perk_"+id).checked) {
					freePerks--;
				} else {
					freePerks++;
				}

				if (freePerks == 0) {
					for (var i=1; i<=3; i++) {
						if (!document.getElementById("perk_"+i).checked) {
							document.getElementById("perk_"+i).disabled = true;
						}
					}
				} else {
					for (var i=1; i<=3; i++) {
						if (!hasPerks[i]) {
							document.getElementById("perk_"+i).disabled = false;
						}
					}
				}


				
			}
';
		
		
		foreach($perks as $perk) {
			
			
			$disabled = '';
			$checked = '';
			$has = Perk::checkPerk($perk->id, $this->player->id);
			
			
			$script .= 'hasPerks['.$perk->id.'] = '.(($has)?('true'):('false')).';';
			
			if ($has) {
				$disabled = 'disabled="disabled"';
				$checked = 'checked="checked"';
			}			
			
			if ($this->player->freePerks == 0) {
				$disabled = 'disabled="disabled"';
			}
			
			echo '<input type="checkbox" onclick="setPerk('.$perk->id.')" '.$disabled.' '.$checked.' name="perk_'.$perk->id.'" id="perk_'.$perk->id.'" /><label for="perk_'.$perk->id.'">'.$perk->name.'</label><br/>';
		}
		
		$script .= '</script>';
		
		echo $script;


echo '
			</div></div>


			<div class="character_screen" id="save">
				<input type="hidden" name="update_character" value="yes" />
				<a href="#" onclick="updateCharacter()"> ULOZ</a>
				<a href=#" onclick="SPreset()">RESET</a>
			</div>
			
		</form>

';	
	}

	function __construct() {
		session_start();
		$this->player = Player::actualPlayer();
		
		
		if (isset($_POST['update_character'])) {
			$pridane_body =0;
			for ($i =0; $i < 10; $i ++) {
				$pridane_body += $_POST['sp_'.$i];
			}
			
			
			if ($this->player->skill_points >= $pridane_body) {
				$this->player->zrucnost_chladne_zbrane += $_POST['sp_0'];
				$this->player->zrucnost_strelne_zbrane += $_POST['sp_1'];
				$this->player->zrucnost_cudzinecke_zbrane += $_POST['sp_2'];
				$this->player->zrucnost_utocna_magia += $_POST['sp_3'];
				$this->player->zrucnost_obranna_magia += $_POST['sp_4'];
				$this->player->zrucnost_obchodovanie += $_POST['sp_5'];
				$this->player->zrucnost_kradnutie += $_POST['sp_6'];
				$this->player->zrucnost_gamblovanie += $_POST['sp_7'];
				$this->player->zrucnost_liecenie += $_POST['sp_8'];
				$this->player->zrucnost_vyroba_lektvarov += $_POST['sp_9'];
			
				$decrease = $_POST['sp_0'] + $_POST['sp_1'] + $_POST['sp_2'] + $_POST['sp_3'] + $_POST['sp_4'] + $_POST['sp_5'] + $_POST['sp_6'] + $_POST['sp_7'] + $_POST['sp_8'] + $_POST['sp_9'];
				$this->player->skill_points -= $decrease;
				//$this->player->volne
				$this->player->save_zrucnosti();
			} else {
			}
			
			
			
			$perks = $this->player->getAllPerks();
			
			$added_perks_count = 0;
			$added_perks = array();
			
			
			foreach($perks as $perk) {
				if (!$this->player->hasPerk($perk->id) && (isset($_POST['perk_'.$perk->id]))) {
						
					$added_perks_count++;
					$added_perks[] = $perk->id;
				}
			}
			
			if ($added_perks_count <= $this->player->volne_perky) {
				foreach ($added_perks as $perk) {
					$this->player->addPerk($perk);
				}
				
			}
			
			
			
			
			
			
			
		}
		
		$this->Display('external_script.css','Postava');
	}
}

	// vytvorenie objektu skriptu
	$page = new PopupCharacter();

?>