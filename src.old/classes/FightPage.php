<?php
require_once('functions/functions_display_play.php');
require_once('Npc.php');

require_once('Fight.php');


class FightPage extends PlayPage {
  protected $player;
	private $fight;
	private $npc;
	
	private $spells;
	
	public function __get($name) {
		throw new Exception('Object of class FightPage has no public attribute');
	}

  function __construct(){
    Styles::addStyle('play_page');
    Styles::addStyle('fight');
    Styles::addStyle('common');


		$this->player = Player::actualPlayer();
		$this->fight = Fight::getFight($this->player->id);
		$this->npc = NPC::getNPC($this->fight->npcName);
		
		$this->spells = Spell::getPlayersSpells($this->player->id);
    
    
		if ($this->fight->npcHP < 1) {
			$location = Location::getLocation($this->player->x, $this->player->y);
			$task = $location->getUnavoidableTask();
			
			if ($task != null) {
				$quest = UsersQuest::getUsersQuest($this->player->id, $task->questName);
				$quest->nextTask();				
			}
			
			$this->fight->delete();
		}
		
		
		$this->inventory = new Inventory($this->player->id);
    
    
    if (isset($_GET['action'])) {
      switch($_GET['action']) {
        case 'attack': $this->attack(0);break;
        case 'attack1': $this->attack(1);break;
        case 'attack2': $this->attack(2);break;
				case 'spell': $this->spell();break;
				case 'endturn': $this->endTurn();break;
      }
        
    }
	}
	
	public function endTurn() {
		$this->fight->makeAction('Čakal si jedno kolo. Akčné body sa ti regenerujú rýchlejšie');
		$this->fight->playerAP = $this->player->baseAP;
		$this->NPCturn();
		$this->fight->save();
		
	}
	
	
	public function  NPCturn() {
		$actionPoints = $this->fight->npcAP;
		
		while(true) {
			$damage = 10* $this->npc->strength / $this->player->totalArmor;
			$APcost = 3;
			
			if ($actionPoints - $APcost >= 0) {
				$this->fight->makeAction('Potvora ti spôsobila '.$damage.' bodov zranenia');
				$actionPoints -= $APcost;
				
				$this->player->hp = $this->player->hp - $damage;
				$this->player->save();
			} else {
				break;
			}
			
		}
		
	}
	

  
  public function attack($mode) {
		
		$weapon1 = $this->inventory->getEquippedItem('weapon1'); 
		$weapon2 = $this->inventory->getEquippedItem('weapon1'); 
		
	
	
		if ( ($mode == 0) && ($weapon1 == null) && ($weapon2 == null) ) {
			$this->fight->playerAP = $this->fight->playerAP - 1;
		} elseif ( ($mode == 1) && ($weapon1 != null) ) {
			$this->fight->playerAP = $this->fight->playerAP - $weapon1->APcost;
		} elseif ( ($mode == 2) && ($weapon2 != null) ) {
			$this->fight->playerAP = $this->fight->playerAP - $weapon2->APcost;
		}
		
		$damage = 10;
		
		
    $this->fight->makeAction('Zaútočil si na potvoru a spôsobil si jej '.$damage.' bodov zranenia.');
    $this->fight->npcHP = $this->fight->npcHP - $damage;
		
		$this->fight->save();
    
    header('location:'.URL);
    exit();
    
  }
	
	public function spell() {
		$spell = Spell::getPlayersSpell($this->player->id, $_GET['spell']);
		
		//echo $spell->title;
		
		if ($this->fight->playerAP >= $spell->APcost) {
			$this->player->hp = $this->player->hp + $spell->heal;
			
			$this->fight->playerAP = $this->fight->playerAP - $spell->APcost;
			
			$this->fight->makeAction('Použil si kúzlo '.$spell->title);
			
			$this->fight->save();
			$this->player->save();
		}
		
	}
  


  public function showFight() {
    echo '
    <div id="fight" class="rounded">
      <img src="'.URL.'images/creatures/dino.png" id="player_image" />

      <img src="'.URL.'images/creatures/re.png" id="enemy_image" />

      <div id="playerstats">
        Život: '.$this->player->hp.'/'.$this->player->maxHP.'<br/>
        Sila: '.$this->player->totalStrength.'<br/>
        Obrana: '.$this->player->totalArmor.'<br/>
        Akčné body: '.$this->fight->playerAP.'
      </div>

      <div id="actions">';
			
			
			if ($this->fight->npcHP > 0) {
				$weapon1 = $this->inventory->getEquippedItem('weapon1'); 
				if ($weapon1 != null) {
					if ($weapon1->APcost <= $this->playerAP) {
						echo '			
						<a href="'.URL.'attack/1">Útok - '.$weapon1->name.' - '.$weapon1->APcost.' AP</a>';
					} else {
						echo '
						<a href="#">Útok - '.$weapon1->name.' - '.$weapon1->APcost.' AP</a>';
					}
				}
				
				$weapon2 = $this->inventory->getEquippedItem('weapon2'); 
				if ($weapon2 != null) {
					if ($weapon2->APcost <= $this->playerAP) {
						echo '			
						<a href="'.URL.'attack/2">Útok - '.$weapon2->name.' - '.$weapon2->APcost.' AP</a>';
					} else {
						echo '
						<a href="#">Útok - '.$weapon2->name.' - '.$weapon2->APcost.' AP</a>';
					}
				}
				
				if (($weapon1 == null) && ($weapon2 == null)) {
					if ($this->fight->playerAP > 0) {
						echo '			
						<a href="'.URL.'attack">Útok holými rukami - 1 AP</a>';
					}
				}
				
				
				foreach($this->spells as $spell) {
					echo '<a href="'.URL.'spell/'.$spell->name.'">'.$spell->title.'</a>';
					
				}
				


				echo '
					<a href="'.URL.'endturn">Ukonči kolo</a>';
			} else {
				echo 'Zabil si potvoru.
	
					<a href="'.URL.'">Pokračuj</a>';
				
			}
					
					
			echo '
      </div>

      <div id="enemystats">
        Život: '.$this->fight->npcHP.'/'.$this->npc->hp.'<br/>
        Sila: '.$this->npc->strength.'<br/>
        Obrana: '.$this->npc->defense.'<br/>
				Akčné body: '.$this->fight->npcAP.'
      </div>


    </div>

    

    <div id="fightlog" class="rounded" style="position:relative;width:800px;height:200px;margin:auto;margin-top:10px;background-color:black;">';
    for($i=count($this->fight->history)-1; $i >=0; $i--) {
      echo '<div>'.$this->fight->history[$i].'</div>';
      
    }
    
    
    
    echo '
    </div>
';
  
    
    
  }












  
  
  public function DisplayContent() {
  }
  
 
  public function popup() {
    echo '
        <div id="popupbackground" style="display:block;"></div>
        <div id="popupcontainer" style="display:block;">';
      $this->showFight();
      echo '</div>';
  }



}

?>