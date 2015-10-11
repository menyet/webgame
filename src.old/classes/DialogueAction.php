<?php

class DialogueAction {
	private $npcName;
	private $positionId;
	private $responseId;
	private $actionId;
	private $actiontype;
	private $actionParam1;
	private $actionParam2;
	private $actionParam3;
	
	
	const TYPE_ADDQUEST = 1;
	const TYPE_FAILQUEST = 2;
	const TYPE_FINISH_TASK = 3;
	
	const TYPE_ADDITEM = 4;
	const TYPE_REMOVEITEM = 5;
	
	const TYPE_EXIT_DIALOGUE = 6;
	
	const TYPE_ADD_XP = 7;
	
	const TYPE_ADD_GOLD = 8;
	const TYPE_ENTER_SHOP = 9;
	
	
	
	private function __construct($npcName, $positionId, $responseId, $actionId, $actiontype, $actionParam1, $actionParam2, $actionParam3) {
		$this->npcName = $npcName;
		$this->positionId = $positionId;
		$this->responseId = $responseId;
		$this->actionId = $actionId;
		$this->actionType = $actiontype;
		$this->actionParam1 = $actionParam1;
		$this->actionParam2 = $actionParam2;
		$this->actionParam3 = $actionParam3;
	}
	
	public function __get($name) {
		switch($name) {
			case 'npcName': return $this->npcName;
			case 'positionId': return $this->positionId;
			case 'responseId': return $this->responseId;
			case 'actionId': return $this->actionId;
			case 'actionType': return $this->actionType;
			case 'param1':
			case 'actionParam1': return $this->actionParam1;
			case 'param2':
			case 'actionParam2': return $this->actionParam2;
			case 'param3':
			case 'actionParam3': return $this->actionParam3;			
			default: throw new Exception('Object of class DialogueAction has no member '.$name);
		}		
	}
	
	public function __set($name, $value) {
		switch($name) {
			case 'actionType': $this->actionType = $value; break;
			case 'actionParam1': $this->actionParam1 = $value; break;
			case 'actionParam2': $this->actionParam2 = $value; break;
			case 'actionParam3': $this->actionParam3 = $value; break;			
		}		
	}
	
	
	public function delete() {
		$query = 'DELETE FROM dialogue_responses_actions WHERE npc_name=? AND position_id=? AND response_id=? AND action_id=?';
		$stmt = MySQLConnection::getInstance()->prepare($query);
		$stmt->bind_param('siii', $this->npcName, $this->positionId, $this->responseId, $this->actionId);
		$stmt->execute();
		$stmt->close();
	}

	
	public function save() {
		$query = 'UPDATE dialogue_responses_actions 
							SET action_type=?, action_param1=?, action_param2=?, action_param3=?
							WHERE npc_name=? AND position_id=? AND response_id=? AND action_id=?';
		$stmt = MySQLConnection::getInstance()->prepare($query);
		
		$stmt->bind_param('issssiii', $this->actionType, $this->actionParam1, $this->actionParam2, $this->actionParam3 ,$this->npcName, $this->positionId, $this->responseId, $this->actionId);
		$stmt->execute();
	}
	
	
	
	public function execute() {
		$player = Player::actualPlayer();
		switch($this->actionType) {
			case self::TYPE_ADDQUEST: 
				Quest::addQuest($this->actionParam1, Player::actualPlayer()->id);
				return;
			case self::TYPE_FAILQUEST: break;
			case self::TYPE_FINISH_TASK:
				//print_r($this);
				
				$quest = UsersQuest::getQuestOfPlayer($this->actionParam1,$player->id);
				$quest->nextTask();
				
				
			
				break;
	
			case self::TYPE_ADDITEM: 
			
				$inventory = new Inventory($player->id);
				break;
			case self::TYPE_REMOVEITEM: break;
			
			case self::TYPE_EXIT_DIALOGUE: 
				Player::actualPlayer()->exitDialogue();
				break;
			case self::TYPE_ADD_XP:
				$player->addXP($this->param1);
				break;
			case self::TYPE_ADD_GOLD:
				$player->gold = $player->gold + $this->param1;
				$player->save();
				break;
			case self::TYPE_ENTER_SHOP:
				
				$player->shop = $this->npcName;
				$player->save();
				break;
			
		}
		
		
	}
	
	
	
	public static function getAction($npcName, $positionId, $responseId, $actionId) {
		$query = 'SELECT npc_name, position_id, response_id, action_id, action_type, action_param1, action_param2, action_param3 FROM dialogue_responses_actions
							WHERE npc_name=? AND position_id=? AND response_id=? AND action_id=?';
		$stmt = MySQLConnection::getInstance()->prepare($query);
		$stmt->bind_param('siii', $npcName, $positionId, $responseId, $actionId);
		$stmt->execute();
		$stmt->store_result();
		$stmt->bind_result($npcName, $positionId, $responseId, $actionId, $actiontype, $actionParam1, $actionParam2, $actionParam3);
		$actions = array();
		while($stmt->fetch()) {
			$actions = new DialogueAction($npcName, $positionId, $responseId, $actionId, $actiontype, $actionParam1, $actionParam2, $actionParam3);
		}
		return $actions;		
	}
	
	public static function getActions($npcName, $positionId, $responseId) {
		$query = 'SELECT npc_name, position_id, response_id, action_id, action_type, action_param1, action_param2, action_param3 FROM dialogue_responses_actions
							WHERE npc_name=? AND position_id=? AND response_id=?';
							
		$stmt = MySQLConnection::getInstance()->prepare($query);
		$stmt->bind_param('sii', $npcName, $positionId, $responseId);
		$stmt->execute();
		$stmt->store_result();
		
		$stmt->bind_result($npcName, $positionId, $responseId, $actionId, $actiontype, $actionParam1, $actionParam2, $actionParam3);
		$actions = array();
		while($stmt->fetch()) {
			$actions[] = new DialogueAction($npcName, $positionId, $responseId, $actionId, $actiontype, $actionParam1, $actionParam2, $actionParam3);
		}
		
		return $actions;		
	}
	
	public static function newAction($npcName, $positionId, $responseId, $actionType, $actionParam1, $actionParam2, $actionParam3) {
		
		$query = 'SELECT MAX(action_id) FROM dialogue_responses_actions
							WHERE npc_name=? AND position_id=? AND response_id=?';
							
		$stmt = MySQLConnection::getInstance()->prepare($query);
		$stmt->bind_param('sii', $npcName, $positionId, $responseId);
		$stmt->execute();
		$stmt->store_result();
		
		$stmt->bind_result($maxId);
		$stmt->fetch();
		
		$actionId = $maxId+1;
		
		
		$query = 'INSERT INTO dialogue_responses_actions 
							SET npc_name=?, position_id=?, response_id=?, action_id=?, 
									action_type=?, action_param1=?, action_param2=?, action_param3=?';
		$stmt = MySQLConnection::getInstance()->prepare($query);
		$stmt->bind_param('siiiisss',$npcName, $positionId, $responseId, $actionId, $actionType, $actionParam1, $actionParam2, $actionParam3);
		$stmt->execute();
		
	}

	
}


?>