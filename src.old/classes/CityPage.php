<?php
require_once('functions/functions_display_play.php');
require_once('classes/Npc.php');

class CityPage extends PlayPage
{
  protected $player;
	private $place;

  function __construct(){
    Styles::addStyle('play_page');
		
	  $this->player = Player::actualPlayer();
		$this->place = Place::getPlaceOnCoords($this->player->x, $this->player->y);

		
		
		if (isset($GLOBALS['modifiers']['obnova_zdravia'])) {
			$hp_modifier = $GLOBALS['modifiers']['obnova_zdravia'];
		} else {
			$hp_modifier = 0;
		}
		
		// kontrola, ci nema hrac viac HP ako je jeho maximum (moze nastat ked si zmeni inventar)
		$this->player->checkHealthPoints();

		// nastavenie obnovy zdravia - $this->hp sa pouzije, ak sa hrac posunie o policko
		$this->player->hp = min($this->player->maxHP, ($this->player->hp +$this->player->attributes->base_replenish_life + $hp_modifier));


		// zdvihnutie predmetu
		if (isset($GLOBALS['action']) && ($GLOBALS['action'] == 'pick_up_item') && isset($GLOBALS['parameters']['id']) && is_numeric($GLOBALS['parameters']['id'])) {
			if ($item = mysql_fetch_assoc(mysql_query("SELECT id,player_id FROM items WHERE id='".$GLOBALS['parameters']['id']."' AND player_id='".$_SESSION['id']."' AND on_ground='1' AND map_x='".$this->x."' AND map_y='".$this->y."';",DATABASE))) {
				mysql_query("UPDATE items SET on_ground='0' WHERE id='".$GLOBALS['parameters']['id']."';",DATABASE);
			}
		}
		
		$quests = UsersQuest::getUsersQuests($this->player->id);
		foreach($quests as $quest) {
			$task = $quest->getActualTask();
		}

	}
	
  public function displayLocation(){
    echo '
      <h2>'.$this->place->name.'</h2>';
			$NPCs = Npc::getNPCs($this->place->id);
			
			if (is_file('images/cities/'.$this->place->id.'.png')) {
				echo '
					<div class="citymap" style="background-image:url('.URL.'images/cities/'.$this->place->id.'.png)">';
					
					foreach($NPCs as $npc) {
						echo '<a href="'.URL.'dialogue/begin/'.$npc->name.'"><img src="'.URL.'images/play_map_dot.gif" style="position:absolute;left:'.$npc->x.'px;top:'.$npc->y.'px" title="'.$npc->title.'" /></a>';
// <a href="'.URL.'dialogue/begin/'.$npc->name.'">Približiť sa</a>';
					}
					
					echo '
					</div>';
			} else {
				$location = Location::getLocation($this->player->x,$this->player->y);
				/*echo '
					<img class="location_image" src="'.URL.'locations/'.$location->filename.'/'.$location->filename.'0'.rand(1,$location_image_number).'.jpg" alt="ilustrácia" />';*/
			}
		
//<img class="location_image" src="'.URL.'locations/'.$location_filename.'/'.$location_filename.'0'.rand(1,$location_image_number).'.jpg" alt="ilustrácia" />';

  }

  function displayNPCs() {
    //$this->player = Player::actualPlayer();
    $NPCs = Npc::getNPCs($this->place->id);

    foreach($NPCs as $npc) {
      echo '<hr style="clear:both" />'.$npc->title.' <a href="'.URL.'dialogue/begin/'.$npc->name.'">Približiť sa</a>';
    }
  }

  
  
  public function DisplayContent() {

    $dialogue = $this->player->dialogueNPC;
    
    if ($dialogue) {
      $dialogue->show();
    } else {
      
      require 'locations/map.php';
      // rozhodnutie, aka lokacia sa vytvori, podla suradnic hraca
    
      if (isset($_GET['move']) && $this->player->Overloaded()) {
        AddMessage('Si preťažený! Musíš vyložiť niektoré predmety.');
      }
    
    
      $this->DisplayLocation();
    
      DisplayMessages();
      DisplayItemsOnGround();
      $this->displayNPCs();
    
		}

		

  }
  
		// ak niekto ma spoluhlaskovu runu (modifier)
		/*if (Chance(5) && ($this->player->IsEquipped('rune_spoluhlaska')) && isset($GLOBALS['just_moved']) && ($GLOBALS['just_moved'] == 1)) {
			$this->DatabaseQuery('UPDATE players SET turns_remaining=\''.($this->turns_remaining).'\' WHERE id='.$_SESSION['id'].';');
			AddMessage('Tvoj magický predmet spôsobil časový posun a zabezpečil ti tak 1 ťah navyše.');
		}*/

	

}

?>