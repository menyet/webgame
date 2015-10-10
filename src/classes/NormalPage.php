<?php
require_once('functions/functions_display_play.php');
require_once('classes/Npc.php');

class NORMAL_PAGE extends PlayPage
{
  protected $player;

  function __construct(){
    Styles::addStyle('play_page');
		
	  $this->player = Player::actualPlayer();
		
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
    $sql= MySQLConnection::getInstance();
    $stmt = $sql->prepare('SELECT l.location_name, l.location_filename, l.location_description, l.location_image_number 
                            FROM locations l, map m 
                            WHERE m.x=? AND m.y=? AND l.location_id = m.location');
    $x = $this->player->x;
    $y = $this->player->y;
    
    $stmt->bind_param('ii',$x,$y);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($location_name, $location_filename, $location_description, $location_image_number);    
    $stmt->fetch();

    echo '
      <h2>'.$location_name.'</h2>
      <img class="location_image" src="'.URL.'locations/'.$location_filename.'/'.$location_filename.'0'.rand(1,$location_image_number).'.jpg" alt="ilustrácia" />';
		
		// popis prostredia
		echo '
      <div class="location_description">'.
      $location_description.'
      </div>';
      
    

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
    
		}

		

  }
  
		// ak niekto ma spoluhlaskovu runu (modifier)
		/*if (Chance(5) && ($this->player->IsEquipped('rune_spoluhlaska')) && isset($GLOBALS['just_moved']) && ($GLOBALS['just_moved'] == 1)) {
			$this->DatabaseQuery('UPDATE players SET turns_remaining=\''.($this->turns_remaining).'\' WHERE id='.$_SESSION['id'].';');
			AddMessage('Tvoj magický predmet spôsobil časový posun a zabezpečil ti tak 1 ťah navyše.');
		}*/

	

}

?>