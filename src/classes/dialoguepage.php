<?php
require_once('functions/functions_display_play.php');
require_once('classes/Npc.php');

class DialoguePage extends PlayPage
{

  function __construct(){
		parent::__construct();
		Styles::addStyle('dialogue');
		
		
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

  function displayNPCs() {
    $NPCMgr = new NPCManager($_SESSION['id']);
    $this->player = playerManager::getInstance()->getPlayer();
    $NPCs = $NPCMgr->getNPCs($this->player->x,$this->player->y);

    foreach($NPCs as $npc) {
      echo '<hr style="clear:both" />'.$npc->name.' <a href="'.URL.'dialogue/begin/'.$npc->dialogue.'">Približiť sa</a>';
    }
  }

  
  
  public function DisplayContent() {

  }
  
  
  public function popup() {
		$dialogue = Dialogue::getDialogue($this->player->id, $this->player->dialogueNPC);
		print_r($dialogue);
    //$dialogue = DialoguePosition::getPositions($this->player->dialogueNPC);
    echo '
        <div id="popupbackground" style="display:block"></div>
        <div id="popupcontainer" style="display:block">';
      
		$position = DialoguePosition::getPosition($dialogue->npcName, $dialogue->position);
		echo '
			<div id="npc_response" class="rounded">
				<img style="float:left;" src="'.URL.'images/creatures/'.$dialogue->npcName.'.png" />
				'.$position->text.'
			</div>';
		echo '<div id="player_response" class="rounded">';
		
		if($position->jumpTo !=0 ) {
			$position2 = DialoguePosition::getPosition($dialogue->npcName, $position->jumpTo);
			$responses = $position2->getResponses();
		} else {
			$responses = $position->getResponses();
		}
		
		
		
		
		foreach ($responses as $response) {
			
			if ($response->checkConditions()) {
				echo '<a href="'.URL.'dialogue/response/'.$response->id.'" class="response">'.$response->text.'</a>';
			}
		}	
		echo '</div>';


echo '</div>';
  }

}

?>