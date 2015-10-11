<?php

class Fight {
  protected $player;
	private $inventory;
	
	private $id;
	private $npcName;
	private $npcHP;
	private $playerId;
	private $history;
	private $playerAP;
	private $npcAP;
	
	public function __get($name) {
		switch($name) {
			case 'id': return $this->id;
			case 'npcName': return $this->npcName;
			case 'npcHP': return $this->npcHP;
			case 'playerId': return $this->playerId;
			case 'history': return $this->history;
			case 'playerAP': return $this->playerAP;
			case 'npcAP': return $this->npcAP;
			default: throw new Exception('Object of class Fight has no attribute '.$name);
		}
	}
	
	public function __set($name, $value) {
		switch($name) {
			case 'npcHP': $this->npcHP=$value;break;
			case 'playerAP': $this->playerAP=$value;break;
			case 'npcAP': $this->npcAP=$value;break;
			default: throw new Exception('Object of class Fight has no attribute '.$name);
		}
	}
	
	public function makeAction($action) {
		$this->history[] = $action;		
	}


	public function delete() {
		$stmt = MySQLConnection::getInstance()->prepare('DELETE FROM fights WHERE fight_player=?');
		$id = Player::actualPlayer()->id;
		$stmt->bind_param('i',$id);
		$stmt->execute();		
		$stmt->close();
	}
	
	public function save() {
		$query = 'UPDATE fights SET fight_history=?, fight_creature_hp=?, fight_player_ap=? WHERE fight_id=?';
    $serializedHistory = serialize($this->history);
    $stmt = MySQLConnection::getInstance()->prepare($query);
    $stmt->bind_param('siii',$serializedHistory, $this->npcHP, $this->playerAP, $this->id);
    $stmt->execute();
    $stmt->close();
	}

	
	public function __construct($id, $npcName, $npcHP, $playerId, $history, $playerAP, $npcAP) {
		$this->id = $id;
		$this->npcName = $npcName;
		$this->npcHP = $npcHP;
		$this->playerId = $playerId;
		$this->history = $history;
		$this->playerAP = $playerAP;
		$this->npcAP = $npcAP;
	}
	
	
	public static function getFight($playerId) {
    $sql= MySQLConnection::getInstance();
    $stmt = $sql->prepare('SELECT fight_id, fight_npc, fight_creature_hp, fight_player, fight_history, fight_player_ap, fight_enemy_ap
													 FROM fights WHERE fight_player=?');
    
		$stmt->bind_param('i',$playerId);
    $stmt->execute();
    $stmt->store_result();
		
		if ($stmt->num_rows ==0) {
			return null;
		}
		
    $stmt->bind_result($id, $npcName, $npcHP, $playerId, $history, $playerAP, $npcAP);    
    $stmt->fetch();
		
		return new self($id, $npcName, $npcHP, $playerId, unserialize($history), $playerAP, $npcAP);
		
		if ($this->creatureHP < 1) {
			$location = new Location($this->player->x, $this->player->y);
			$task = $location->getUnavoidableTask();
			
			if ($task != null) {
				$quest = UsersQuest::getUsersQuest($this->player->id, $task->questName);
				$quest->nextTask();
				
			}
			
			//delete
			
		}
	}
	
	
}


?>