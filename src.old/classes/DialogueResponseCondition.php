<?php

abstract class DialogueResponseCondition {
	private $npcName;
	private $positionId;
	private $responseId;
	private $conditionId;
	private $conditionType;
	private $conditionParam1;
	private $conditionParam2;
	private $conditionParam3;
	
	
	const TYPE_HAS_QUEST = 1;
	const TYPE_HAS_TASK = 2;
	const TYPE_HAS_FINISHED_TASK = 3;
	
	const TYPE_MINIMAL_ATTRIBUTE = 4;
	const TYPE_MINIMAL_LEVEL = 5;
	
	const TYPE_HAS_RACE = 6;
	
	abstract public function check();
	
	
	private function __construct($npcName, $positionId, $responseId, $conditionId, $conditiontype, $conditionParam1, $conditionParam2, $conditionParam3) {
		$this->npcName = $npcName;
		$this->positionId = $positionId;
		$this->responseId = $responseId;
		$this->conditionId = $conditionId;
		$this->conditionType = $conditiontype;
		$this->conditionParam1 = $conditionParam1;
		$this->conditionParam2 = $conditionParam2;
		$this->conditionParam3 = $conditionParam3;
	}
	
	public function __get($name) {
		switch($name) {
			case 'npcName': return $this->npcName;
			case 'positionId': return $this->positionId;
			case 'responseId': return $this->responseId;
			case 'conditionId': return $this->conditionId;
			case 'conditionType': return $this->conditionType;
			case 'param1':
			case 'conditionParam1': return $this->conditionParam1;
			case 'param2':
			case 'conditionParam2': return $this->conditionParam2;
			case 'param3':
			case 'conditionParam3': return $this->conditionParam3;			
		}		
	}
	
	public function __set($name, $value) {
		switch($name) {
			case 'conditionType': $this->conditionType = $value; break;
			case 'conditionParam1': $this->conditionParam1 = $value; break;
			case 'conditionParam2': $this->conditionParam2 = $value; break;
			case 'conditionParam3': $this->conditionParam3 = $value; break;			
		}		
	}
	

	
	public function save() {
		$query = 'UPDATE dialogues_responses_conditions 
							SET condition_type=?, condition_param1=?, condition_param2=?, condition_param3=?
							WHERE npc_name=? AND position_id=? AND response_id=? AND condition_id=?';
		$stmt = MySQLConnection::getInstance()->prepare($query);
		
		$stmt->bind_param('issssiii', $this->conditionType, $this->conditionParam1, $this->conditionParam2, $this->conditionParam3 ,$this->npcName, $this->positionId, $this->responseId, $this->conditionId);
		$stmt->execute();
	}
	
	
	
	private static function constructCondition($npcName, $positionId, $responseId, $conditionId, $conditionType, $conditionParam1, $conditionParam2, $conditionParam3) {
		switch($conditionType) {
			case self::TYPE_HAS_QUEST: return null;
			case self::TYPE_HAS_TASK: 
				require_once('conditions/ConditionHasTask.php');
				return new ConditionHasTask($npcName, $positionId, $responseId, $conditionId, $conditionType, $conditionParam1, $conditionParam2, $conditionParam3);
			case self::TYPE_HAS_FINISHED_TASK: return null;
			case self::TYPE_MINIMAL_ATTRIBUTE: return null;
			case self::TYPE_MINIMAL_LEVEL: return null;
			case self::TYPE_HAS_RACE: return null;
			
			
		}
		
	}
	
	
	
	public static function getCondition($npcName, $positionId, $responseId, $conditionId) {
		$query = 'SELECT npc_name, position_id, response_id, condition_id, condition_type, condition_param1, condition_param2, condition_param3 FROM dialogues_responses_conditions
							WHERE npc_name=? AND position_id=? AND response_id=? AND condition_id=?';
		$stmt = MySQLConnection::getInstance()->prepare($query);
		$stmt->bind_param('siii', $npcName, $positionId, $responseId, $conditionId);
		$stmt->execute();
		$stmt->store_result();
		$stmt->bind_result($npcName, $positionId, $responseId, $conditionId, $conditiontype, $conditionParam1, $conditionParam2, $conditionParam3);
		$conditions = array();
		while($stmt->fetch()) {
			$conditions = self::constructCondition($npcName, $positionId, $responseId, $conditionId, $conditiontype, $conditionParam1, $conditionParam2, $conditionParam3);
		}
		return $conditions;		
	}
	
	public static function getConditions($npcName, $positionId, $responseId) {
		$query = 'SELECT npc_name, position_id, response_id, condition_id, condition_type, condition_param1, condition_param2, condition_param3 FROM dialogues_responses_conditions
							WHERE npc_name=? AND position_id=? AND response_id=?';
							
		$stmt = MySQLConnection::getInstance()->prepare($query);
		echo MySQLConnection::getInstance()->error;
		$stmt->bind_param('sii', $npcName, $positionId, $responseId);
		$stmt->execute();
		$stmt->store_result();
		
		$stmt->bind_result($npcName, $positionId, $responseId, $conditionId, $conditiontype, $conditionParam1, $conditionParam2, $conditionParam3);
		$conditions = array();
		while($stmt->fetch()) {
			$conditions[] = self::constructCondition($npcName, $positionId, $responseId, $conditionId, $conditiontype, $conditionParam1, $conditionParam2, $conditionParam3);
		}
		
		return $conditions;		
	}
	
	public static function newCondition($npcName, $positionId, $responseId, $conditionType, $conditionParam1, $conditionParam2, $conditionParam3) {
		echo $npcName.' '.$positionId.' '.$responseId.' '.$conditionType.' '.$conditionParam1.' '.$conditionParam2.' '.$conditionParam3;
		$query = 'SELECT MAX(condition_id) FROM dialogues_responses_conditions
							WHERE npc_name=? AND position_id=? AND response_id=?';
							
		$stmt = MySQLConnection::getInstance()->prepare($query);
		$stmt->bind_param('sii', $npcName, $positionId, $responseId);
		$stmt->execute();
		$stmt->store_result();
		
		$stmt->bind_result($maxId);
		$stmt->fetch();
		
		$conditionId = $maxId+1;
		
		//echo $conditionId;
		
		
		$query = 'INSERT INTO dialogues_responses_conditions 
							SET npc_name=?, position_id=?, response_id=?, condition_id=?, 
									condition_type=?, condition_param1=?, condition_param2=?, condition_param3=?';
		$stmt = MySQLConnection::getInstance()->prepare($query);
		$stmt->bind_param('siiiisss',$npcName, $positionId, $responseId, $conditionId, $conditionType, $conditionParam1, $conditionParam2, $conditionParam3);
		
		if (!$stmt->execute()) {
			echo $stmt->error;
		}
		
	}

	
}


?>