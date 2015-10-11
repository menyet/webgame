<?php

require '../security.php';
require '../classes/class_external_script.php';

class MANUAL extends EXTERNAL_SCRIPT
{

	function DisplayBody() {
		
		echo '<div class="manual_menu">';
		$this->DisplayMenu();
		echo '</div>';
		
		echo '<div class="manual_content">';
		if (isset($_GET['page'])) {
			$page = $_GET['page'];
			
			if ($page == 'runy') {
				$this->DisplayRunes();
			} else {
			
			}
			
		}
		echo '</div>';
	
	}

	function MANUAL() {
		$this->Display('external_script.css','Fórum');
	}
	
	function DisplayMenu() {
		?>
		
		<ol>
			<li><a href="./manual.php?page=page1" title="">menu item</a></li>
			<li><a href="./manual.php?page=page1" title="">menu item</a></li>
			<li><a href="./manual.php?page=runy" title="Runy a runové slová">Runy a runové slová</a></li>
			<li><a href="./manual.php?page=page1" title="">menu item</a></li>
			<li><a href="./manual.php?page=page1" title="">menu item</a></li>
		</ol>
		
		<?php
	}
	
	function DisplayRunes() {
		?>
		
		<h1>Runy a runové slová</h1>
		
		<p>
		Väčšina predmetov, ktoré v hre nájdete má nejaký počet socketov - miest, do ktorých môžete umiestniť runy. Každá runa 
		pridá danému predmetu určitú jednu (alebo viac) magických vlastností. Navyše ak do predmetu pridáte správnu kombináciu 
		týchto rún - runové slovo, získate silné bonusy. Runové slová sú tajné. Niektoré vám môžu prezradiť NPC postavy v hre, 
		no na väčšinu musíte prísť sami skúšaním, alebo sa radiť s ostatnými hráčmi. Runové slovo je funkčné, iba ak sú obsadené 
		všetky sockety v danom predmete a runy sú v správnom poradí. Ak však už umiestnite runu do predmetu, nedá sa vybrať.
		</p>
		
		<p>
		Runy sú rozdelené do šiestich skupín. Každá skupina rún pridáva predmetom rôzne magické vlastnosti a má rôznu šancu na výskyt
		v hre. Skupiny rún podľa výskytu v hre:
			<ol>
				<li>"A" - runy (najčastejšie sa vyskytujúce)</li>
				<li>"E" - runy</li>
				<li>"I" - runy</li>
				<li>"O" - runy</li>
				<li>"U" - runy</li>
				<li>"samohláskové" runy (najcenejšie)</li>
			</ol>
		</p>
		
		<h2><img src="../images/items/runes/a.gif" alt="">"A" runy</h2>
		<p>Runy svetla nesú magické schopnosti schopné odľahčiť predmet, do ktorého sú zasadené, čím mierne zlepšia jeho bojové schopnosti a požiadavky na jeho používanie.</p>
		<p>
			<i>Magické vlastnosti:</i>
			<ul>
				<li>Poškodenie zvýšené o 5 (pri zbraniach)</li>
				<li>Armor zvýšený o 5 (pri zbroji alebo helme)</li>
				<li>Minimálna sila znížená o 1</li>
				<li>Váha predmetu znížená o 25%</li>
			</ul>
		</p>
		<p>
		BA, CA, DA, AB, FA, GA, JA, HA, AR, TA, RA, KA, AT, MA, NA, AN, PA, AQ, AS, AV
		</p>
		
		<h2><img src="../images/items/runes/e.gif" alt="">"E" runy</h2>
		<p>
			<p>Runy krvi zvyšujú vražednosť zbraní a dodávajú nositeľovi odhodlanie a chuť do boja.</p>
			<i>Magické vlastnosti:</i>
			<ul>
				<li>Maximálne poškodenie zvýšené o 15 (pri zbraniach)</li>
				<li>Maximálne zdravie zvýšené o 10</li>
				<li>Minimálny level znížený o 1</li>
			</ul>
		</p>
		<p>
		BE, EB, CE, DE, ED EF, FE, GE, EG, HE, JE, KE, LE, EL, ME, EM, NE, EN, PE, EQ, ER, RE, SE, ES, TE, VE
		</p>
		
		<h2><img src="../images/items/runes/i.gif" alt="">"I" runy</h2>
		<p>Takzvané runy múdrosti zvyšujú efektivitu predmetov, do ktorých sú vsadené. Magicky zlepšujú usporiadanie častí zbroje, alebo šetria muníciu potrebnú pre niektoré zbrane.</p>
		<p>
			<i>Magické vlastnosti:</i>
			<ul>
				<li>Armor zvýšený o 20 (pri zbroji alebo helmách)</li>
				<li>Spotreba munície znížená o 25% (pri zbraniach)</li>
				<li>Nosnosť zvýšená o 10kg</li>
			</ul>
		</p>
		<p>
		BI, CI, DI, ID, IF, FI, GI, IG HI, KI, LI, MI, IM, NI, IN, IP, IQ, RI, IR, IS, SI, TI, IT, VI
		</p>
		
		<h2><img src="../images/items/runes/o.gif" alt="">"O" runy</h2>
		<p>Runy vitality zlepšujú fyzickú kondíciu ich nositeľa. Je tak schopný odniesť ťažší náklad, či vydržať silnejšie zranenia.</p>
		<p>
			<i>Magické vlastnosti:</i>
			<ul>
				<li>Nosnosť zvýšená o 20kg</li>
				<li>Maximálny počet Hit Pointov zvýšený o 25</li>
				<li>Základný armor zvýšený o 5</li>
			</ul>
		</p>
		<p>
		OB, BO, CO, DO, OD, OF, GO, HO, JO, KO, OK, OL, OM, MO, NO, PO, OP, QO, OR, RO, OS, OT, TO, OV, VO
		</p>
		
		<h2><img src="../images/items/runes/u.gif" alt="">"U" runy</h2>
		<p>Runy šťastia magickým spôsobom dokážu ovplyvniť ako sa vyvinú zdanlivo náhodné javy. Zvyšujú tak šance na úspech pri boji.</p>
		<p>
			<i>Magické vlastnosti:</i>
			<ul>
				<li>Šanca na kritický zásah zvýšená o 3%</li>
				<li>Šanca na zásah v noci zvýšená o 3%</li>
				<li>Šanca na zásah cez deň zvýšená o 3%</li>
			</ul>
		</p>
		<p>
		BU, UB, UC, CU, DU, UF, UG, GU, HU, UK, UL, MU, UM, NU, UN, UP, UR, SU, UT, TU, UV
		</p>
		
		<h2><img src="../images/items/runes/s.gif" alt="">"spoluhláskové" runy</h2>
		<p>Runy času sú veľmi zriedkavé a vzácne runy nosiace prastarú mágiu schopnú ohýbať čas v prospech ich používateľa.</p>
		<p>
			<i>Magické vlastnosti:</i>
			<ul>
				<li>Každý ťah šanca 5% na posun času a získanie ďalšieho ťahu</li>
				<li>Obnova zdravia zvýšená o 5 HP za ťah</li>
				<li>Potrebné Action Pointy znížené o 1 (len pri zbraniach)</li>
			</ul>
		</p>
		<p>
		BB, CC, DH, FH, GH, HH, JJ, KK, LL, MM, NN, PP, QU, RR, SS, TH, VU
		</p>
		
		<h2>Príklad runového slova - TimeKiss</h2>
		<p>
		Ak umiestniš do 4-socketového predmetu postupne runy Ti,Me,Ki a SS, tak vytvoríš runové
		slovo TimeKiss. Tým tvoj predmet získa k magickým vlastnostiam, ktoré má od každej z rún 
		ešte bonusové vlastnosti za celé slovo. V tomto prípade získaš 1 bonusový Action-point a 
		bonus 10 k obnove zdravia. Toto je však iba jedno z mnohých funkčných runových slov - ďalšie 
		mocnejšie slová už však musíš objaviť sám.
		<img src="../images/manual/runeword_example.png">
		</p>
		
		
		<?php
	}


}

	// vytvorenie objektu skriptu
	$page = new MANUAL();

?>