<?php
require_once('class_not_logged_page.php');
require_once('functions/tooltips.php');

define('MAXTRAITS',2);

class Trait {
	private $id;
	private $name;
	private $showName;
	
	public function __construct($id, $name, $showName) {
		Styles::addStyle('title_page');
		$this->id = $id;
		$this->name = $name;
		$this->showName = $showName;
	}
	
	public function show() {
			echo '<li>';
			echo '<input name="'.$this->name.'" id="trait'.$this->id.'" type="checkbox" onchange="CheckTraits('.$this->id.');" '.(isset($_POST[$this->name])?'checked="checked"':'').'>';
			echo '<label for="trait'.$this->id.'" style="margin-left: 5px;" '.UseToolTip($this->id).'>'.$this->showName.'</label>';
			echo '</li>';

	}
	
	public function __get($name) {
		switch($name){
			case 'id': return $this->name;
			case 'name': return $this->name;
			case 'showName': return $this->showName;
		}
	}
	
}


class RegisterSelectPersonalAttributes extends  NOT_LOGGED_PAGE {
	private $traits = array();
	
	
	public function __construct() {
		$this->traits[] = new Trait(0,'trait_mutant',		'Mutant');
		$this->traits[] = new Trait(1,'trait_vampir',		'Vampír');
		$this->traits[] = new Trait(2,'trait_tenka_postava',		'Tenká postava');
		$this->traits[] = new Trait(3,'trait_nadany',		'Nadaný');
		$this->traits[] = new Trait(4,'trait_skuseny',		'Skúsený');
		$this->traits[] = new Trait(5,'trait_gambler',		'Gambler');
		$this->traits[] = new Trait(6,'trait_surovec',		'Surovec');
		$this->traits[] = new Trait(7,'trait_humanista',		'Humanista');
		$this->traits[] = new Trait(8,'trait_psychopat',		'Psychopat');
		$this->traits[] = new Trait(9,'trait_sprt',		'Šprt');
		$this->traits[] = new Trait(10,'trait_oziareny',		'Ožiarený');
		$this->traits[] = new Trait(11,'trait_bitkar',		'Bitkár');
		$this->traits[] = new Trait(12,'trait_kamikadze',		'Kamikadze');
		$this->traits[] = new Trait(13,'trait_technomaniak',		'Technomaniak');

		
		if (isset($_POST['settraits'])) {
			$traitsNum =0;
			$traitsChecked = array();
			
			try {
				foreach($this->traits as $trait) {
					if (isset($_POST[$trait->name])) {
						$traitsChecked[]=$trait;
						$traitsNum++;
						
					}
				}
				
				if ($traitsNum!=2) {
					throw new Exception();
				}
				
				$player = Player::actualPlayer();
				$player->addTraits($traitsChecked);
				$player->setRegisterState(2);
			
				header('location:'.URL);
				exit();
			} catch(Exception $e) {
				echo 'sok a cucc';
			}
			
		}
		
		
	}
	
	
	
	
	public function displayContent() {
			echo '<h1>Osobnostné črty - krok 3/6</h1>';
			
			// uvodny text:
			echo '<div class="create_player_div" style="left: 20px; top: 60px; width: 786px; height: 70px;">';
			echo 'Ďalším krokom je výber osobnostných čŕt postavy. Sú to jej charakteristické vlastnosti, ktoré sú či už vrodené, alebo získané v mladosti. Ich kombináciou dáš svojej postave základ unikátnej osobnosti.<br><br>Osobnostné črty sú povinné a musíš si vybrať práve 2!';
			echo '</div>';
			
			// javascript na kontrolu kolko checkboxov je oznacenych
			echo '<script type="text/javascript">
					var traitsChecked = 0;
					function CheckTraits(id) {
						if (!document.getElementById("trait"+id).checked || (traitsChecked < 2)) {
							traitsChecked++;
						} else if(document.gementById("trait"+id).checked) {
							traitsChecked--;
						}

						if (traitsChecked==2) {
							for (var i=0; i<14; i++) {
								if (!document.getElementById("trait"+id).checked) {
									document.getElementById("trait"+id).disabled = true;
								}
							}
						}
						
					}
				</script>';
			
			// vytvorenie tooltipov, ktore budeme pouzivat vo formulari
			CreateToolTip(0,'Mutant','Biologické zbrane, ktoré Cudzinci použili vo Veľkej vojne na tebe a tvojej rodine zanechali stopy. Tvoja koža vplyvom mutácie získala podarené zelené zafarbenie a časti z nej z času na čas odpadávajú. V podstate si obluda. Na druhej strane ťa ale zhrubnutá koža lepšie chráni pred zranením. A ten pekný bonsaj, čo ti rastie z hlavy vážne nemá chybu. <br><br><b>+ 10 armor<br>- 1 charizma </b>');
			CreateToolTip(1,'Vampír','V mladosti ťa uhryzol upír. Okrem toho, že si zomrel to má na tvoj život (?) aj iné vplyvy. Tvoje zmysly sú zostrené počas noci, no cez deň ti slnečné svetlo vôbec nerobí dobre. <br><br><b>+ 20% šanca na zásah pri boji v noci<br>- 20% šanca na zásah pri boji cez deň</b>');
			CreateToolTip(2,'Štíhla postava','Nech robíš čokoľvek, nedarí sa ti pribrať. Tvoja mierne vyziabnutá stavba tela však pre teba nikdy nebola problémom. Práve naopak. Si obratnejší a ľahšie sa vyhýbaš útokom. Čo na tom, že nie si žiaden kulturista? <br><br><b>+ 10 armor<br>- 15kg nosnosť</b>');
			CreateToolTip(3,'Nadaný','Si všeobecne talentovaný. Vo všetkom, do čoho si sa pustil, si bol lepší ako tvoji kamaráti bez toho, aby si sa nejako snažil. Na dobré sa však ľahko zvyká a tak si zlenivel. Nechcelo sa ti študovať a zdokonaľovať svoje schopnosti. <br><br><b>+ 5 bodov na atribúty<br>- 15% z každej zručnosti<br>- 5 bodov na zručnosti získaných za level </b>');
			CreateToolTip(4,'Skúsený','Väčšinu svojho života si strávil sebazdokonaľovaním. Každú voľnú chvíľku si študoval, alebo trénoval všetko čo vieš. To, že si na seba taký tvrdý však spôsobí, že počas života premrháš niektoré šance získať rôzne extra schopnosti. <br><br><b>+ 15% na každý skill<br>+ 5 bodov na skilly za level<br>perk získaš každé 4 levely namiesto troch</b>');
			CreateToolTip(5,'Gambler','Si chorý. Si závislý na hazarde, ale nikdy si to nepriznáš. Od malička si oberal svojich kamarátov o najlepšie hračky rôznymi stávkami a hrami, no pravá vášeň začala až keď sa ti do rúk dostali hracie karty. Čím viac gambluješ, tým viac sa ti to oplatí. Keď však prehrávaš, neváhaš rozpredať pod cenu všetko čo máš, len aby si mohol hrať ďalej.<br><br><b>+ 25% gambling<br>- 25% obchodovanie</b>');
			CreateToolTip(6,'Surovec','Si tyran. Nič ťa nebaví tak, ako šikanovanie slabších. Vekom si sa v tom stále zdokonaľoval, hoci aj na úkor ostatných schopností a vedomostí. Vyjednávaš radšej zbraňami ako slovami. Pre tvoj prístup ťa miluje každý hrobár v okolí.<br><br><b>+ 15% chladné a strelné zbrane<br>- 15% obchod a liečenie</b>');
			CreateToolTip(7,'Humanista','Násilie ti bolo vždy odporné. Ak si vyslovene nemusel, snažil si sa vyhnúť zbraniam a vôbec všetkým otvoreným konfliktom. Radšej sa z problémov vykecáš, ako by si mal za sebou nechávať kopy mŕtvol.<br><br><b>+ 15% obchod a liečenie<br>- 15% chladné a strelné zbrane</b>');
			CreateToolTip(8,'Psychopat','Rád o sebe tvrdíš, že si zabil viac ľudí ako mor. Si asociálny maniak, ktorý trpí keď netrpia všetci okolo neho. Na naplnenie tvojich zvrátených chúťok neváhaš použiť okrem zbraní ani mágiu. Oveľa radšej spôsobuješ bolesť, ako ju odstraňuješ, takže z teba nikdy nebude dobrý liečiteľ. <br><br><b>+ 15% útočná mágia a chladné zbrane<br>- 10% liečenie, obranná mágia a obchodovanie</b>');
			CreateToolTip(9,'Šprt','Mladosť si strávil s nosom v knihách. Štúdium bolo pre teba vždy príťažlivejšie ako fyzické aktivity. Tvoja inteligencia je nadpriemerná, no oproti ostatným strácaš na sile. <br><br><b>+ 1 inteligencia<br>- 1 sila</b>');
			CreateToolTip(10,'Ožiarený','Tomu, že v tme mierne svietiš vďačíš rádioaktívnemu žiareniu z čias Veľkej vojny s Cudzincami. Ich mocné zbrane spôsobili, že okolo seba máš zelenkastú žiaru, ktorá ti svieti na cestu. Okrem toho, že ľahšie zasiahneš v noci nepriateľa, nemusíš si ani svietiť, keď potrebuješ ísť na toaletu. Ožiarenie však škodí tvojmu zdraviu. <br><br><b>+ 5% šanca na zásah pri boji v noci<br>obnova zdravia je spomalená o 2 HP za ťah</b>');
			CreateToolTip(11,'Bitkár','Vyžívaš sa v krčmových bitkách. Keď zacítiš konflikt, neváhaš sa doňho zamiešať aj keď vôbec netušíš o čo ide. Tvoja sila tým získala, no príliš veľa rán do hlavy zanechali stopy na tvojom dôvtipe. <br><br><b>+ 1 sila<br>- 1 inteligencia</b>');
			CreateToolTip(12,'Kamikadze','V boji ťa netrápi žiadne nebezpečie. Súsredíš sa len na to, čo robíš ty a nezaujíma ťa kto čo spraví tebe. Znášaš bolesť ľahšie ako ostatní ľudia... asi preto, že ostatní ľudia prežili v živote o pár desiatok zlomenín menej ako ty. <br><br><b>+ 1 action-point (rýchlostný bod)<br>- 10 armor</b>');
			CreateToolTip(13,'Techno-maniak','Od malička ťa zaujímali všetky technológie, ktoré sa objavili vo svete počas Veľkej vojny. Cudzinecké zbrane a pomôcky ťa vždy fascinovali viac ako mágia a kúzla. <br><br><b>+ 20% cudzinecké zbrane<br>- 10% útočná a obranná mágia</b>');
			
			// formular:
			echo '<div class="create_player_div"  style="left: 20px; top: 155px; width: 786px; height: 200px; text-align: center;">';
			echo '<form action="'.URL.'" method="post">';
			
			echo '<ul style="list-style: none; margin: 0px; padding: 0px; position: absolute; top: 10px; left: 10px; text-align: left;">';
			
			for ($i=0;$i<7;$i++) {
				$this->traits[$i]->show();
			}
			
			echo '</ul>';
			
			echo '<ul style="list-style: none; margin: 0px; padding: 0px; position: absolute; top: 10px; left: 180px; text-align: left;">';
			
			for ($i=7;$i<14;$i++) {
				$this->traits[$i]->show();
			}


			echo '</ul>';
			
			echo '<input class="submit_button" type="submit" name="settraits" value="Pokračovať krokom 4" style="position: relative; top: 155px;">';
			echo '</form>';
			echo '</div>';
		}
	}

?>