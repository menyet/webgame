<?php

class DialogueResponse {
	private $npcName;
  private $positionId;
	private $responseId;
	private $responseText;
	private $responseJumpTo;
	
	private function __construct($npcName, $positionId, $responseId, $responseText, $responseJumpTo) {
		$this->npcName = $npcName;
		$this->positionId = $positionId;
		$this->responseId = $responseId;
		$this->responseText = $responseText;
		$this->responseJumpTo = $responseJumpTo;
	}
	
	public function __get($name) {
		switch($name) {
			case 'id': return $this->responseId;
			case 'position': return $this->positionId;
			case 'npcName': return $this->npcName;
			case 'jumpTo': return $this->responseJumpTo;
			case 'text': return $this->responseText;
			default: throw new Exception('Object of class <b>DialogueResponse</b> has no member <b>'.$name.'</b>');
		}
		
	}
	
	public function __set($name, $value) {
		switch($name) {
			case 'jumpTo': $this->responseJumpTo = $value;break;
			case 'text': $this->responseText = $value;break;
			default: throw new Exception('Object of class <b>DialogueResponse</b> has no member <b>'.$name.'</b>');
		}
	}
	
	public function save() {
		$query = 'UPDATE dialogues_responses SET response_text=?, response_jump_to_position=? WHERE npc_name =? AND response_position=? AND response_id=?';
		$stmt = MySQLConnection::getInstance()->prepare($query);
		$stmt->bind_param('sisii', $this->responseText, $this->responseJumpTo, $this->npcName, $this->positionId, $this->responseId);
		$stmt->execute();
		$stmt->close();
	}
	
	public function checkConditions() {
		$conditions = DialogueResponseCondition::getConditions($this->npcName, $this->position, $this->id);
		$check = true;
		foreach($conditions as $condition) {
			$check = $check && $condition->check();
		}
			
		return $check;
		
	}
	
	
	
	
	
	
	
	
	
	
	

	
	public static function getResponses($npcName, $position) {
		$query = 'SELECT npc_name, response_position, response_id, response_text, response_jump_to_position FROM dialogues_responses WHERE npc_name =? AND response_position=?';
		$stmt = MySQLConnection::getInstance()->prepare($query);
		
		$stmt->bind_param('si',$npcName, $position);
		$stmt->execute();

		$stmt->store_result();
		$stmt->bind_result($npcName, $positionId, $responseId, $responseText, $responseJumpTo);
		$responses = array();
		
		while ($stmt->fetch()) { 
			$responses[] = new self($npcName, $positionId, $responseId, $responseText, $responseJumpTo);
		}
		$stmt->close();
		return $responses;
	}
	
	public static function getResponse($npcName, $position, $response) {
		//echo $npcName.' '.$position.' '.$response;
		
		$query = 'SELECT npc_name, response_position, response_id, response_text, response_jump_to_position FROM dialogues_responses WHERE npc_name =? AND response_position=? AND response_id = ?';
		$stmt = MySQLConnection::getInstance()->prepare($query);
		
		$stmt->bind_param('sii',$npcName, $position, $response);
		$stmt->execute();

		$stmt->store_result();
		
		if ($stmt->num_rows ==0 ) {
			return null;
		}
		
		$stmt->bind_result($npcName, $positionId, $responseId, $responseText, $responseJumpTo);
		$responses = array();
		
		$stmt->fetch();
		$response = new self($npcName, $positionId, $responseId, $responseText, $responseJumpTo);
		$stmt->close();
		return $response;
	}
	
	public static function newResponse($npcName, $position, $text) {
		$query = 'SELECT MAX(response_id) FROM dialogues_responses WHERE npc_name = ? AND response_position=?';
		$stmt = MySQLCOnnection::getInstance()->prepare($query);
		//echo MySQLCOnnection::getInstance()->error;
		$stmt->bind_param('si', $npcName, $position);
		$stmt->execute();
		$stmt->store_result();
		$stmt->bind_result($maxId);
		$stmt->fetch();
		$stmt->close();
		$nextId = $maxId+1;
		
		$jumpTo = 0;
		
		$query = 'INSERT INTO dialogues_responses SET npc_name=?, response_position=?, response_id=?, response_text=?, response_jump_to_position=?';
		$stmt = MySQLConnection::getInstance()->prepare($query);
		$stmt->bind_param('siisi', $npcName, $position, $nextId, $text, $jumpTo);
		
		$stmt->execute();
		$stmt->close();
		
	}

	
	
}


?>