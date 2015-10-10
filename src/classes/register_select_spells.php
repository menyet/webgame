<?php

require_once('class_not_logged_page.php');
require_once('functions/tooltips.php');

class RegisterSelectSpells extends  NOT_LOGGED_PAGE {
  public function __construct() {
		Styles::addStyle('title_page');
    $player = Player::actualPlayer();
    $attr = $player->attributes;

    $this->kuzla_pocet = floor($attr->attr_intelligence / 3);
    
    if (isset($_POST['selectspells'])) {
			$spells = 0;
			
			if (isset($_POST['kuzlo_nizsie_liecenie'])) $spells++;
			if (isset($_POST['kuzlo_tvrda_koza'])) $spells++;
			if (isset($_POST['kuzlo_pomoc_prirody1'])) $spells++;
			if (isset($_POST['kuzlo_magicky_vyboj'])) $spells++;
			if (isset($_POST['kuzlo_pomoc_zahrobia1'])) $spells++;
			if (isset($_POST['kuzlo_slepota'])) $spells++;
			
			if ($spells == $this->kuzla_pocet) {
				$player->x = 22;
				$player->y = 22;
				$player->dialogueNPC = 'npc_morman';
				Dialogue::newDialogue($player->id, 'npc_morman');

				$player->hp = $player->baseEndurance*7 + $player->baseStrength;
				$player->maxHP = $player->baseEndurance*7 + $player->baseStrength;
		
		
				$player->criticalChance = $player->baseLuck;
				$player->baseArmor = 10;
		
		
				$player->registerState = 10;
				$player->save();
				
				
				$player->finalizeRegistration();
				header('location:'.URL);
				exit();
			}
			
			
      
    }
    
    
    
  }
  
  
  public function DisplayContent() {

			CreateToolTip(0,'Nižšie liečenie','Najslabšie z liečivých kúzel. Magicky hojí tvoje rany a zceľuje zlomené kosti, čím obnoví 8-12 Hit-Pointov.<br><br>Kategória: Obranná mágia<br>Spotreba: 4 Action-Pointy<br>Cieľ: hráč<br>Základná šanca: 50%');
			CreateToolTip(1,'Tvrdá koža','Slabšia verzia kúziel Kamenná koža, či Oceľová koža. Spôsobí že tvoja pokožka stvrdne a stane sa odolnejšou voči zraneniam. Zvýši tvoj Armor o 5 na dobu 3 ťahov.<br><br>Kategória: Obranná mágia<br>Spotreba: 7 Action-Pointov<br>Cieľ: hráč<br>Základná šanca: 65%');
			CreateToolTip(2,'Pomoc prírody I.','Kúzlo, ktoré rozoznie vábivý zvuk, ktorý sa šíri divočinou a privolá na tvoju ochranu jedného Havrana. Existujú aj vyššie verzie tohto kúzla, ktoré privolajú silnejšie zvieratá.<br><br>Kategória: Obranná mágia<br>Spotreba: 7 Action-Pointov<br>Cieľ: žiaden<br>Základná šanca: 60%');
			CreateToolTip(3,'Magický výboj','Vytvorí výboj magickej energie, ktorý vystrelí na cieľ silou 12-15 damage.<br><br>Kategória: Útočná mágia<br>Spotreba: 5 Action-Pointov<br>Cieľ: nepriateľ<br>Základná šanca: 45%');
			CreateToolTip(4,'Pomoc záhrobia I.','Vyšle do okolia záhrobné volanie, ktoré prebudí najbližšiu mŕtvolu a prinúti ju vo forme Kostlivca pomáhať hráčovi. Mocnejšie verzie tohto kúzla vyvolávajú mŕtvych v silnejších podobách.<br><br>Kategória: Útočná mágia<br>Spotreba: 7 Action-Pointov<br>Cieľ: žiaden<br>Základná šanca: 60%');
			CreateToolTip(5,'Slepota','Zasiahne nepriateľove oči oslepujúcim zábleskom, ktorý mu dočasne poškodí zrak. Cieľ sa spamätá o tri ťahy, no dovtedy má zníženú šancu na zásah v boji o 15%.<br><br>Kategória: Útočná mágia<br>Spotreba: 6 Action-Pointov<br>Cieľ: nepriateľ<br>Základná šanca: 35%');
			
			// javascript na kontrolu kolko checkboxov je oznacenych
			echo '
      <script type="text/javascript">
			function Oznacene(){
				ozn = 0;
				if (document.forms[0][\'kuzlo_nizsie_liecenie\'].checked) ozn++;
				if (document.forms[0][\'kuzlo_tvrda_koza\'].checked) ozn++;
				if (document.forms[0][\'kuzlo_pomoc_prirody1\'].checked) ozn++;
				if (document.forms[0][\'kuzlo_magicky_vyboj\'].checked) ozn++;
				if (document.forms[0][\'kuzlo_pomoc_zahrobia1\'].checked) ozn++;
				if (document.forms[0][\'kuzlo_slepota\'].checked) ozn++;
				return ozn;
			}
			
			</script>';
			
			
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
			
			
			echo '<div style="text-align: center; width: 100px; position: absolute; left: 20px;">';
			echo '<p><input onChange="if (Oznacene() > '.$this->kuzla_pocet.') checked = false;" type="checkbox" name="kuzlo_nizsie_liecenie" id="kuzlo_nizsie_liecenie"></p>';
			echo '<label for="kuzlo_nizsie_liecenie">';
			echo '<img src="'.URL.'images/spells/nizsie_liecenie.gif" alt="nižšie liečenie" '.UseToolTip(0).' style="cursor: Pointer;">';
			echo '</label>';
			echo '</div>';
			
			echo '<div style="text-align: center; width: 100px; position: absolute; left: 120px;">';
			echo '<p><input onChange="if (Oznacene() > '.$this->kuzla_pocet.') checked = false;" type="checkbox" name="kuzlo_tvrda_koza" id="kuzlo_tvrda_koza"></p>';
			echo '<label for="kuzlo_tvrda_koza">';
			echo '<img src="'.URL.'images/spells/tvrda_koza.gif" alt="tvrdá koža" '.UseToolTip(1).' style="cursor: Pointer;">';
			echo '</label>';
			echo '</div>';
			
			echo '<div style="text-align: center; width: 100px; position: absolute; left: 220px;">';
			echo '<p><input onChange="if (Oznacene() > '.$this->kuzla_pocet.') checked = false;" type="checkbox" name="kuzlo_pomoc_prirody1" id="kuzlo_pomoc_prirody1"></p>';
			echo '<label for="kuzlo_pomoc_prirody1">';
			echo '<img src="'.URL.'images/spells/pomoc_prirody1.gif" alt="pomoc prírody I" '.UseToolTip(2).' style="cursor: Pointer;">';
			echo '</label>';
			echo '</div>';
			
			echo '<div style="text-align: center; width: 100px; position: absolute; left: 20px; top: 120px;">';
			echo '<p><input onChange="if (Oznacene() > '.$this->kuzla_pocet.') checked = false;" type="checkbox" name="kuzlo_magicky_vyboj" id="kuzlo_magicky_vyboj"></p>';
			echo '<label for="kuzlo_magicky_vyboj">';
			echo '<img src="'.URL.'images/spells/magicky_vyboj.gif" alt="magický výboj" '.UseToolTip(3).' style="cursor: Pointer;">';
			echo '</label>';
			echo '</div>';
			
			echo '<div style="text-align: center; width: 100px; position: absolute; left: 120px; top: 120px;">';
			echo '<p><input onChange="if (Oznacene() > '.$this->kuzla_pocet.') checked = false;" type="checkbox" name="kuzlo_pomoc_zahrobia1" id="kuzlo_pomoc_zahrobia1"></p>';
			echo '<label for="kuzlo_pomoc_zahrobia1">';
			echo '<img src="'.URL.'images/spells/pomoc_zahrobia1.gif" alt="pomoc záhrobia I" '.UseToolTip(4).' style="cursor: Pointer;">';
			echo '</label>';
			echo '</div>';
			
			echo '<div style="text-align: center; width: 100px; position: absolute; left: 220px; top: 120px;">';
			echo '<p><input onChange="if (Oznacene() > '.$this->kuzla_pocet.') checked = false;" type="checkbox" name="kuzlo_slepota" id="kuzlo_slepota"></p>';
			echo '<label for="kuzlo_slepota">';
			echo '<img src="'.URL.'images/spells/slepota.gif" alt="slepota" '.UseToolTip(5).' style="cursor: Pointer;">';
			echo '</label>';
			echo '</div>';
			
			
			echo '<input class="submit_button" type="submit" name="selectspells" value="Dokončiť tvorbu postavy" style="position: absolute; left: 75px; top: 320px;">';
			echo '</form>';
			echo '</div>';
  }
}


?>