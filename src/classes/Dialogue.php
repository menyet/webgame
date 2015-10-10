<?php

require_once('Npc.php');

require_once('dialogAction.php');

require_once('DialogueAction.php');
require_once('DialogueResponse.php');
require_once('DialoguePosition.php');
require_once('DialogueResponseCondition.php');


class Dialogue
{
  private $playerId = 0;
	private $npcName;
  private $position;
	
	public function __get($name) {
		switch($name) {
			case 'playerId': return $this->playerId;
			case 'npcName': return $this->npcName;
			case 'position': return $this->position;
		}
	}

	

  private function beginDialogue($npcid) {
    $npc = $this->npcMgr->getNPC($npcid);

    $player = playerManager::getInstance()->getPlayer();
    $player->dialogue = $npc->dialogue;
    echo 'dialogue: '.$npc->dialogue;
    $player->save();


    $query = 'INSERT INTO dialogues_players (player_id, dialogue, dialogue_position) VALUES (?,?,?)';
    $stmt = MySQLConnection::getInstance()->prepare($query);

    $pid = $player->id;
    $dialogue = $npc->dialogue;
    $position = 1;

    $stmt->bind_param('iii',$pid,$dialogue,$position);

    $stmt->execute();




  }
	
	private function jump($jump_to) {
		$position = DialoguePosition::getPosition($this->npcName, $jump_to);
		if ($position == null) {
			return;
		}
		
		
		$this->position = $jump_to;
		$query = 'UPDATE dialogues_players SET dialogue_position=? WHERE player_id=? AND npc_name=?';
		$stmt = MySQLConnection::getInstance()->prepare($query);
		$stmt->bind_param('iis',$jump_to, $this->playerId, $this->npcName);
		$stmt->execute();
	}
	
	
	public function selectResponse($responseId) {
		//echo 'Processign response';
		
		$position = DialoguePosition::getPosition($this->npcName, $this->position);
		
		
		
		if ($position->jumpTo == 0) {
			$response = DialogueResponse::getResponse($this->npcName, $this->position, $responseId) ;
		} else {
			//$position2 = DialoguePosition::getPosition($this->npcName, $position->jumpTo);
			$response = DialogueResponse::getResponse($this->npcName, $position->jumpTo, $responseId) ;
		}
		
		if ($response == null) {
			return;
		}
		
		$jump_to = $response->jumpTo;
		
		$this->jump($jump_to);
		$actions = DialogueAction::getActions($this->npcName, $response->position, $response->id);
		
		foreach($actions as $action) {
			$action->execute();
		}
		
		print_r($actions);
		return $actions;	
	}
	

	public function __construct($playerId, $npcName, $position) {
		//session_start();
    $this->npcName = $npcName;
		$this->playerId = $playerId;
		$this->position = $position;
	}
	
	
	private function getActions($dialog, $position, $response) {
    $query = 'SELECT action_type, action_param1, action_param2, action_param3 FROM dialogue_responses_actions WHERE dialog_id=? AND position_id=? AND response_id=?';
    $stmt = MySQLConnection::getInstance()->prepare($query);
    $stmt->bind_param('iii',$dialog, $position, $response);
    $stmt->execute();
    $stmt->store_result(); 
    $stmt->bind_result($type, $param1, $param2, $param3);
    
    $actions= array();
    
    while ($stmt->fetch()) {
      $actions[] = new DialogAction($type, $param1, $param2, $param3);
    }
    
    return $actions;
    
    
  }
	
	
	public static function newDialogue($playerId, $npcName) {
		
		//$
		
		$query = 'INSERT INTO dialogues_players SET player_id=?, npc_name=?, dialogue_position=?';
	  $stmt = MySQLConnection::getInstance()->prepare($query);
		$position = 1;
		$stmt->bind_param('isi', $playerId, $npcName, $position);
	  $stmt->execute();
		$stmt->store_result();
		$stmt->close();
		
		return new self($playerId, $npcName, $position);	
		
	}
	
	
	
	
	
	
	public static function getDialogue($playerId, $npcName) {
		//echo $playerId.' '.$npcName;
		
		
		$query = 'SELECT dialogue_position FROM dialogues_players WHERE player_id = ? AND npc_name = ?';
	  $stmt = MySQLConnection::getInstance()->prepare($query);
		$stmt->bind_param('is', $playerId, $npcName);
	  $stmt->execute();

	  $stmt->store_result();
		
		if ($stmt->num_rows == 0) {
			$stmt->close();
			return null;
		}
    
    /*if ($stmt->num_rows == 0) {
			return null;
      $stmt->close();
      $stmt = MySQLConnection::getInstance()->prepare('INSERT INTO dialogues_players SET player_id = ?, npc_name = ?, dialogue_position = ?');
      $this->position = 1;
      $stmt->bind_param('isi', $this->playerId, $this->id, $this->position);
      $stmt->execute();
    }*/
		
    $stmt->bind_result($position);
    $stmt->fetch();
    $stmt->close();
		return new self($playerId, $npcName, $position);		
	}
	
	
}

	// vytvorenie objektu skriptu

?>
