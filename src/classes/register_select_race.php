<?php

require_once('class_not_logged_page.php');
require_once('functions/tooltips.php');

class RegisterSelectRace extends NOT_LOGGED_PAGE
{
	public function __construct() {
		parent::__construct();
		
    Styles::addStyle('title_page');
		
		if(isset($_POST['selectrace'])) {
			$player = Player::actualPlayer();
			$player->changeRace($_POST['race']);
			$player->setRegisterState(2);
			header('location:'.URL);
			exit();

			
		}
	}
	
	
	public function DisplayContent() {


			echo '<h2>Rasa - krok 2/6</h2>';
			
			echo '<img id="nahlad" src="'.URL.'images/create_player/human.gif" style="position: absolute; left:20px; top: 60px; border-color: rgb(200,250,220); border-style: solid; border-width: 1px; -moz-border-radius: 6px;">';
			
			echo '<div class="create_player_div"  style="font-size: 13px; left: 180px; top: 60px; width: 626px; height: 100px;">';
			echo 'Výber rasy je dôležitým krokom pri tvorbe tvojej postavy. Skôr ako si vyberieš svoju rasu, mal by si sa rozhodnúť akým štýlom chceš hrať. Existuje množstvo spôsobov ako prežiť a prosperovať vo svete Wasteland a na každý z nich sa hodí iná rasa. Ak sa napríklad chceš živiť ako obchodník, najlepší pre vás pravdepodobne bude Trpaslík. Ak však považuješ za najlepšie riešenie problémov pár fireballov, tvoja voľba bude skôr démonický Kremon.<br><br>Pozorne si preto prečítaj aké má ktorá rasa výhody a nevýhody a vyber si to, čo ti najviac vyhovuje.';
			echo '</div>';
			
			echo '<div class="create_player_div"  style="left: 20px; top: 398px; width: 786px; height: 40px;">';
			echo '<b>POZOR:</b> Výber rasy priamo ovplyvňuje niektoré udalosti v hre. To znamená, že k rôznym rasám sa niektoré NPC postavy budú správať rôzne. Navyše niektoré questy sú určené iba pre konkrétne rasy.';
			echo '</div>';
			
			echo '<div class="create_player_div"  style="padding: 0px; margin: 0px; left: 180px; top: 185px; width: 640px; height: 202px;">';
			
			echo '<form action="'.URL.'" method="post">';
			

			echo '<ul style="list-style: none; font-size: 18px; margin: 10px; padding: 0px;">';
			echo '<li>';
			CreateToolTip(0,'Človek','Človek je jedným z tvorov, ktorí majú vo svete najpočetnejšie zastúpenie. Oproti ostatným rasám majú pomerne krátky život, no zdá sa že ľudskú rasu práve to robí mocnejšou. Môžu si hrdo povedať, ze svoje krátke životy žijú naplno. Majú viac potomkov ako ostatné rasy (s výnikou Gnomov) a ich deti dospievajú v nižsom veku. Žijú rýchly a uponáhľaný život a snažia sa silou-mocou o zachovanie silnej pozície svojej rasy. Bohužiaľ na to vo väčšine prípadov neváhajú použiť násilie. Celkom pochopiteľne majú sklony používať silu ako najrýchlejšie riešenie problémov napriek tomu, že fyzickou silou neprevyšujú ostatné tvory. V očiach iných rás sú ľudia často zákernými bytosťami, ktorým nemožno celkom dôverovať. Možno nie náhodou boli všetci votrelci v osudnej Veľkej vojne práve ľudia.<br><br><b>+ 10% strelné zbrane<br>+ 5% cudzinecké zbrane a útočná mágia<br>+ 1 rýchlosť</b>');
			CreateToolTip(1,'Elf','Nevieme či je pravdou, že Elfovia sú najstaršou rasu sveta, ale s určitosťou môžme povedať, že sú rasou najviac namyslenou. Pre svoj vznešený výzor a nezaujaté vystupovanie sú často obdivovaní ostatnými tvormi. Elfovia majú sklony vyvyšovať sa nad ostatné rasy, aj keď je otázne, či na to majú aj objektívne dôvody. Priemerný Elf sa dožíva 300 až 400 rokov a svoj život trávi v harmónii s prírodou. Výzorovo sa najviac ponáša na Človeka, s niekoľkými drobnými rozdielmi. Postava Elfa je o niečo štíhlejšia a tvár zdobia dlhšie zašpicatené uši. Elfovia sú často dobrými mágmi, no špecializujú sa skôr na obrannú a liečebnú mágiu. Spory riešia radšej slovami ako bojom. <br><br><b>+ 15% obranná mágia<br>+ 5% výroba lektvarov<br>+ 1 charizma</b>');
			CreateToolTip(2,'Trpaslík','Trpaslíci majú dlhú a krvavú minulosť. O svoje miesto na tomto svete museli dlhé stáročia bojovať s každou civilizáciou na ktorú natrafili. Dôvod je jednoduchý - bohatstvo. Trpaslíci sú geniálni obchodníci a zhromažďovanie majetku je ich vrodeným celoživotným cieľom. Práve toto bohatstvo je však zároveň silným lákadlom pre všetky barbarské, či lúpežnícke spoločenstvá. Stáročia obranných bitiek z Trpaslíkov urobili okrem obchodníkov aj schopných a statočných bojovníkov. Trpaslíci sú síce nižší ako Ľudia či Elfovia (merajú približne 140 cm), no majú silnú a odolnú stavbu tela. <br><br><b>+ 20% obchodovanie<br>+ 1 výdrž</b>');
			CreateToolTip(3,'Gnom','Malí veselí ľudia, ktorí by celí život najradšej strávili spievaním niekde v krčme pri pive. Gnomov má každý rád napriek tomu, že sú to vlastne alkoholickí kleptomani. Majú zvláštnu slabosť pre všetky šperky a drahokamy a neváhajú prisvojiť si to, čo im nepatrí. Sú jednou z mladších rás, ktorá sa rýchlo rozšírila vo svete vďaka tomu, že sa veľmi rýchlo množia a udržiavajú priateľské vzťahy so všetkými tvormi. Až na výnimky je najobľúbenejšou bojovou taktikou Gnomov útek. Priemerný gnom je o pár centimetrov vyšší ako trpaslík, no má štíhlejšiu postavu. Dožíva sa približne 150 rokov. <br><br><b>+ 20% kradnutie<br>+ 1 šťastie</b>');
			CreateToolTip(4,'Polo-obor','Polo-obri v podstate nie sú plnohodnotnou rasou. Sú to iba miešanci príslušníka rasy Obrov a niektorej z iných, rozšírenejších rás. Polo-obri sú vo svete zriedkaví pretože drvivá väčšina Obrov žije mimo civilizácie v horách a lesoch. O Obroch, a teda aj ich miešancoch je rozšírená povera, že sú to krvilačné, hlúpe stvorenia, žijúce v jaskyniach. Nie je to však pravda. Obri sú inteligentnou rasou, ktorá má však rada svoje súkromie. Nie sú násilní, no dokážu sa veľmi efektívne brániť. Polo-obor je asi 3 metre vysoký a zarastený hustou srsťou. Jeho telo je svalnaté, pretože skoro všetko v živote robí vlastnými rukami a bez nástrojov. <br><br><b>+ 1 sila a výdrž<br>- 1 charizma<br>+ 15% chladné zbrane<br>+ 5% liečenie</b>');
			CreateToolTip(5,'Kremon','Kremoni, alebo ako sa sami zvyknú nazývať, Deimovia sú potomkami prívržencov démonov, ktorí prišli na tento svet pred troma tisícročiami. Deimovia sa jeden na druhého veľmi nepodobajú, pretože pochádzajú zo všetkých ostatných rás, no skutočnosť, že ich predkovia boli posadnutí prisluhovači pekelných tvorov je na nich na prvý pohľad vidieť. Vačšina z nich má na hlave rohy a ťahá za sebou rôzne dlhý chvost. Niektorým dokonca zostali diabolské krídla, ktoré však nemôžu používať na let. Okrem toho im zostali typické črty rasy z ktorej pochádzali ich predkovia. (elfské špicaté uši, či výška trpaslíka) Jedinou vecou, ktorú majú všetci spoločnú, sú ich démonické žlté oči. Sú to obávaní mágovia, ktorí neváhajú použiť svoju moc.<br><br><b>+ 20% útočná mágia<br>+ 1 inteligencia</b>');
			echo '<img id="img_human" src="../images/create_player/human.gif" style="visibility: hidden; position: absolute; left: -500px; top: -500px;">';
			echo '<input type="radio" name="race" value="human" id="human" onClick="nahlad.src=img_human.src" checked> ';
			echo '<label '.UseToolTip(0).' onClick="nahlad.src=img_human.src"  for="human">Človek</label> ';
			echo '</li>';
			echo '<li>';
			echo '<img id="img_elf" src="../images/create_player/elf.gif" style="visibility: hidden; position: absolute; left: -500px; top: -500px;">';
			echo '<input type="radio" name="race" value="elf" id="elf" onClick="nahlad.src=img_elf.src" > ';
			echo '<label '.UseToolTip(1).' onClick="nahlad.src=img_elf.src" for="elf">Elf</label> ';
			echo '</li>';
			echo '<li>';
			echo '<img id="img_dwarf" src="../images/create_player/dwarf.gif" style="visibility: hidden; position: absolute; left: -500px; top: -500px;">';
			echo '<input type="radio" name="race" value="dwarf" id="dwarf" onClick="nahlad.src=img_dwarf.src"> ';
			echo '<label '.UseToolTip(2).' onClick="nahlad.src=img_dwarf.src" for="dwarf">Trpaslík</label> ';
			echo '</li>';
			echo '<li>';
			echo '<img id="img_gnome" src="../images/create_player/gnome.gif" style="visibility: hidden; position: absolute; left: -500px; top: -500px;">';
			echo '<input type="radio" name="race" value="gnome" id="gnome" onClick="nahlad.src=img_gnome.src"> ';
			echo '<label '.UseToolTip(3).' onClick="nahlad.src=img_gnome.src" for="gnome">Gnom</label> ';
			echo '</li>';
			echo '<li>';
			echo '<img id="img_halfogre" src="../images/create_player/half-ogre.gif" style="visibility: hidden; position: absolute; left: -500px; top: -500px;">';
			echo '<input type="radio" name="race" value="half-ogre" id="half-ogre" onClick="nahlad.src=img_halfogre.src"> ';
			echo '<label '.UseToolTip(4).' onClick="nahlad.src=img_halfogre.src" for="half-ogre">Polo-obor</label> ';
			echo '</li>';
			echo '<li>';
			echo '<img id="img_kremon" src="../images/create_player/kremon.gif" style="visibility: hidden; position: absolute; left: -500px; top: -500px;">';
			echo '<input type="radio" name="race" value="kremon" id="kremon" onClick="nahlad.src=img_kremon.src"> ';
			echo '<label '.UseToolTip(5).' onClick="nahlad.src=img_kremon.src" for="kremon">Kremon / Deimos</label> ';
			echo '</li>';
			echo '</ul>';
			echo '<input class="submit_button" type="submit" name="selectrace" value="Pokračovať krokom 3">';
			echo '</form>';
			
			echo '</div>';
	}

}


?>