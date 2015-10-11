<?php
require_once('class_not_logged_page.php');

class STORY_PAGE extends NOT_LOGGED_PAGE
{
	// konstruktor triedy
	function STORY_PAGE(){
		
		// zobrazenie stranky
		$this->Display("title_page.css");
	}
	
	function DisplayContent() {
	    
	}
	
	function DisplayBody(){
		echo '<div class="center">';
		echo '<div class="story">';
		if (isset($GLOBALS['parameters']['page']) && ($GLOBALS['parameters']['page'] == 13)) {
			echo "
			<img class=\"story_image\" src=\"images/story/13.jpg\">
			<div class=\"story_div\">
			Tí, ktorí prežili, zabudli na pôvodné klanové rozdelenie a naučili sa žiť jeden vedľa 
			druhého. Už pól druha storočia spolupracujú na tvorbe novej civilizácie. Zmenilo sa ich 
			myslenie. Zmenil sa aj ich výzor. Zmenil sa celý svet, ale jedna vec sa nikdy nezmení.
				... Vojna - tá je vždy rovnaká.
			</div>
			";
			echo '<a class="story_link" href="'.$_SERVER['PHP_SELF'].'?action=story&page=12">späť</a>';
		}
		elseif (isset($GLOBALS['parameters']['page']) && ($GLOBALS['parameters']['page'] == 12)) {
			echo "
			<img class=\"story_image\" src=\"images/story/12.jpg\">
			<div class=\"story_div\">
			Celý svet zmenil svoju tvár, získanú tisícročiami vývoja, za niekoľko dní.
			Vražedné explózie rúcali mestá a pálili lesy. Nebezpečné chemikálie otrávili 
			vodu i vzduch a na mnoho ďalších desaťročí zanechávali ohavné mutácie na všetkých 
			tvoroch, ktorí ho dýchali. Málokto zostal nepoznačený. Či už rasa hrdých Elfov, 
			bezstarostných Gnomov, či Trpaslíkov, démonických Kremonov, Obrov, alebo Cudzincom 
			najviac podobná rasa Ľudí - nikto nebol voči zhubným následkom chemikálií dostatočne 
			odolný.
			</div>
			";
			echo '<a class="story_link" href="'.$_SERVER['PHP_SELF'].'?action=story&page=11">späť</a>';
			echo '<a class="story_link" href="'.$_SERVER['PHP_SELF'].'?action=story&page=13">ďalej</a>';
		}
		elseif (isset($GLOBALS['parameters']['page']) && ($GLOBALS['parameters']['page'] == 11)) {
			echo "
			<img class=\"story_image\" src=\"images/story/11.jpg\">
			<div class=\"story_div\">
			Cudzinci v zúfalstve pristúpili k drastickému riešeniu. Rozhodli sa použiť dlho pripravované 
			a utajované zbrane hromadného ničenia. Vedeli, že aj oni sami sa stanú obeťou chemických a biologických zbraní, no 
			rodení bojovníci vo svätej vojne nehľadia na vlastný život. Zbrane, ktoré boli pôvodne vyrobené na ochranu 
			ich žien a detí pred neprieteľmi na Zemi, nakoniec rozpútali peklo v úplne inom svete. Vo svete ktorý každý 
			od tohto osudného dňa volal \"Wasteland\".
			</div>
			";
			echo '<a class="story_link" href="'.$_SERVER['PHP_SELF'].'?action=story&page=10">späť</a>';
			echo '<a class="story_link" href="'.$_SERVER['PHP_SELF'].'?action=story&page=12">ďalej</a>';
		}
		elseif (isset($GLOBALS['parameters']['page']) && ($GLOBALS['parameters']['page'] == 10)) {
			echo "
			<img class=\"story_image\" src=\"images/story/10.jpg\">
			<div class=\"story_div\">
			Spojením síl a kombináciou rôznych schopností a vedomostí sa klanom podarilo sformovať 
			armádu schopnú poraziť cudzincov.
			Či už pomocou klasických zbraní a mágie, alebo pomocou pištolí a samopalov, ktoré zostávali 
			po mŕtvych nepriateľoch, sa spojencom podarilo zatlačiť cudzincov späť na ostrov Nhydeos. 
			Keď už sa zdalo, že víťazstvo je na dosah, všetko sa zmenilo.
			</div>
			";
			echo '<a class="story_link" href="'.$_SERVER['PHP_SELF'].'?action=story&page=9">späť</a>';
			echo '<a class="story_link" href="'.$_SERVER['PHP_SELF'].'?action=story&page=11">ďalej</a>';
		}
		elseif (isset($GLOBALS['parameters']['page']) && ($GLOBALS['parameters']['page'] == 9)) {
			echo "
			<img class=\"story_image\" src=\"images/story/9.jpg\">
			<div class=\"story_div\">
			Bez váhania sa vrhli do svätej vojny proti svojim \"únoscom\". So svojimi vyspelými zbraňami 
			a pokročilou bojovou taktikou pre nich nebolo problémom za pár dní poraziť Calebov klan kúzelníkov. 
			To ich však nezastavilo...
			Vodcovia ostatných klanov rýchlo pochopili, že čelia katastrofe a jediným riešením je spolupráca.
			Po pólstoročí nepriateľstva sa tak z rivalov stali spojenci a zo súbojov o moc sa stal boj o prežitie...
			</div>
			";
			echo '<a class="story_link" href="'.$_SERVER['PHP_SELF'].'?action=story&page=8">späť</a>';
			echo '<a class="story_link" href="'.$_SERVER['PHP_SELF'].'?action=story&page=10">ďalej</a>';
		}
		elseif (isset($GLOBALS['parameters']['page']) && ($GLOBALS['parameters']['page'] == 8)) {
			echo "
			<img class=\"story_image\" src=\"images/story/8.jpg\">
			<div class=\"story_div\">
			Calebova túžba ukončiť konflikt siedmich klanov bola nakoniec predsa len naplnená.
			Záhadní cudzinci boli totiž pre vodcov klanov oveľa väčšou hrozbou, ako ktorýkoľvek 
			nepriateľský klan. Cudzineckí bojovníci totiž vôbec neboli nadšení tým, že sa ocitli v 
			inom svete a zanechali svoju vlasť takmer bezbrannú pred nadchádzajúcou vojnou na Zemi...
			</div>
			";
			echo '<a class="story_link" href="'.$_SERVER['PHP_SELF'].'?action=story&page=7">späť</a>';
			echo '<a class="story_link" href="'.$_SERVER['PHP_SELF'].'?action=story&page=9">ďalej</a>';
		}
		elseif (isset($GLOBALS['parameters']['page']) && ($GLOBALS['parameters']['page'] == 7)) {
			echo "
			<img class=\"story_image\" src=\"images/story/7.jpg\">
			<div class=\"story_div\">
			Transportné kúzlo namiesto dračích vajec, z ktorých by klan kúzelníkov vychoval 
			mocnú armádu drakov, prenieslo do Calebovho sveta niečo celkom iné.
			Na ostrove Nhydeos sa ocitla armáda zvláštne oblečených ľudí, ktorí sa zrejme vo 
			svojom svete pripravovali na celkom iné boje. Zástupy odhodlaných mužov so zbraňami, 
			aké doteraz Caleb nevidel, v ňom budili rešpekt.
			</div>
			";
			echo '<a class="story_link" href="'.$_SERVER['PHP_SELF'].'?action=story&page=6">späť</a>';
			echo '<a class="story_link" href="'.$_SERVER['PHP_SELF'].'?action=story&page=8">ďalej</a>';
		}
		elseif (isset($GLOBALS['parameters']['page']) && ($GLOBALS['parameters']['page'] == 6)) {
			echo "
			<img class=\"story_image\" src=\"images/story/6.jpg\">
			<div class=\"story_div\">
			Calebova myseľ, zdeformovaná životom plným bojov, sa však v rozhodujúcej chvíli 
			nedokázala naplno sústrediť sa svet dračích ľudí - Is'dhurull, ale skĺzla do sveta 
			celkom iného. Caleb otvoril bránu do sveta, zvaného \"Zem\".
			</div>
			";
			echo '<a class="story_link" href="'.$_SERVER['PHP_SELF'].'?action=story&page=5">späť</a>';
			echo '<a class="story_link" href="'.$_SERVER['PHP_SELF'].'?action=story&page=7">ďalej</a>';
		}
		elseif (isset($GLOBALS['parameters']['page']) && ($GLOBALS['parameters']['page'] == 5)) {
			echo "
			<img class=\"story_image\" src=\"images/story/5.jpg\">
			<div class=\"story_div\">
			Po toľkých rokoch bojov už žiaden z klanov nemal dostatok bojovníkov a zbraní, 
			aby dokázal vojnu vyhrať. Caleb preto pristúpil k odvážnemu kroku. Pomocou piatich 
			magických kľúčov otvoril starodávny dimenzionálny portál na ostrove Nhydeos v nádeji, 
			že sa mu podarí použiť ho na získanie mocných spojencov pre svoj klan.
			</div>
			";
			echo '<a class="story_link" href="'.$_SERVER['PHP_SELF'].'?action=story&page=4">späť</a>';
			echo '<a class="story_link" href="'.$_SERVER['PHP_SELF'].'?action=story&page=6">ďalej</a>';
		}
		elseif (isset($GLOBALS['parameters']['page']) && ($GLOBALS['parameters']['page'] == 4)) {
			echo "
			<img class=\"story_image\" src=\"images/story/4.jpg\">
			<div class=\"story_div\">
			Jedným z mladých vodcov a detí vychovaných vojnou bol Caleb. Talentovaný elf, ktorý 
			strávil mladosť štúdiom starej mágie a v dospelosti sa stal vodcom klanu kúzelníkov. 
			Napriek tomu, že Caleb zabezpečil svojmu klanu víťazstvo v mnohých ťažkých bojoch, videl, 
			že konečné riešenie musí hľadať inde.
			</div>
			";
			echo '<a class="story_link" href="'.$_SERVER['PHP_SELF'].'?action=story&page=3">späť</a>';
			echo '<a class="story_link" href="'.$_SERVER['PHP_SELF'].'?action=story&page=5">ďalej</a>';
		}
		elseif (isset($GLOBALS['parameters']['page']) && ($GLOBALS['parameters']['page'] == 3)) {
			echo "
			<img class=\"story_image\" src=\"images/story/3.jpg\">
			<div class=\"story_div\">
			Po pólstoročí krviprelievania a zbytočného násilia si už málokto pamätal, prečo
			to všetko začalo.
			Deti sa rodili do sveta plného nenávisti a starých vodcov po ich zavraždení nahradilo 
			niekoľko generácií nových.
			</div>
			";
			echo '<a class="story_link" href="'.$_SERVER['PHP_SELF'].'?action=story&page=2">späť</a>';
			echo '<a class="story_link" href="'.$_SERVER['PHP_SELF'].'?action=story&page=4">ďalej</a>';
		}
		elseif (isset($GLOBALS['parameters']['page']) && ($GLOBALS['parameters']['page'] == 2)) {
			echo "
			<img class=\"story_image\" src=\"images/story/2.jpg\">
			<div class=\"story_div\">
			Koncom šiesteho storočia tretieho veku vyústili drobné potyčky medzi siedmimi klanmi,
			ovládajúcimi celý známy svet, do otvoreného konfliktu.
			Vodcovia klanov, bažiaci po moci a bohatstve, hnali svoj ľud do čoraz krvavejších bojov.
			</div>
			";
			echo '<a class="story_link" href="'.$_SERVER['PHP_SELF'].'?action=story&page=1">späť</a>';
			echo '<a class="story_link" href="'.$_SERVER['PHP_SELF'].'?action=story&page=3">ďalej</a>';
		}
		else {
			echo "
			<img class=\"story_image\" src=\"images/story/1.jpg\">
			<div class=\"story_div\">
			Vojna ...tá je vždy rovnaká.
			Hrôzostrašná hra plná krvi a sĺz, ktorú hrajú hráči proti svojej vôli.
			Hra, ktorá má iba jedno pravidlo - všetci sú porazení a svet zostáva nenávratne zmenený.
			</div>
			";
			echo '<a class="story_link" href="'.$_SERVER['PHP_SELF'].'?action=story&page=2">ďalej</a>';
		}
		
		echo '</div>';
		
		$this->DisplayRightPanel();
		
		echo '</div>';
	}

}

?>
