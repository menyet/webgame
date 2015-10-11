<?php

class DialoguePosition {
	private $npcName;
	private $positionId;
	private $positionText;
	private $positionJumpTo;	
	
	private function __construct($npcName, $positionId, $positionText, $positionJumpTo) {
		$this->npcName = $npcName;
		$this->positionId = $positionId;
		$this->positionText = $positionText;
		$this->positionJumpTo = $positionJumpTo;
	}
	
	public function __get($name) {
		switch($name) {
			case 'npcName': return $this->npcName;
			case 'id': return $this->positionId;
			case 'text': return $this->positionText;
			case 'jumpTo': return $this->positionJumpTo;
		}
	}
	
	public function __set($name, $value) {
		switch($name) {
			case 'text': $this->positionText = $value;break;
			case 'jumpTo': $this->positionJumpTo = $value;break;
		}
	}
	
	
	public function save() {
		//echo 'asd'.$this->positionJumpTo;
		$query = 'UPDATE dialogue_positions SET position_text=?, position_jump_to=? WHERE npc_name=? AND position_id=? ';
		$stmt = MySQLConnection::getInstance()->prepare($query);
		$stmt->bind_param('sisi',$this->positionText, $this->positionJumpTo, $this->npcName, $this->positionId);
		$stmt->execute();
		
	}
	
	public function getResponses() {
		return DialogueResponse::getResponses($this->npcName, $this->positionId);
	}
	
	
	public static function getPositions($npcName) {
		//echo $npcName;
		$query = 'SELECT npc_name, position_id, position_text, position_jump_to FROM dialogue_positions WHERE npc_name = ?';
		$stmt = MySQLConnection::getInstance()->prepare($query);
		$stmt->bind_param('s',$npcName);
		$stmt->execute();
		$stmt->store_result();
		$stmt->bind_result($npcName, $positionId, $positionText, $positionJumpTo);
		$positions = array();
		
		while ($stmt->fetch()) {
			$positions[] = new self($npcName, $positionId, $positionText, $positionJumpTo);
			
		}
		$stmt->close();
		return $positions;
	}
	
	public static function getPosition($npcName,$position) {
		$query = 'SELECT npc_name, position_id, position_text, position_jump_to FROM dialogue_positions WHERE npc_name = ? AND position_id=?';
		$stmt = MySQLConnection::getInstance()->prepare($query);
		$stmt->bind_param('si',$npcName, $position);
		$stmt->execute();
		$stmt->store_result();
		
		if ($stmt->num_rows == 0) {
			return null;
		}	
		
		
		$stmt->bind_result($npcName, $positionId, $positionText, $positionJumpTo);
		$positions = array();
		$stmt->fetch();
		$position = new self($npcName, $positionId, $positionText, $positionJumpTo);
		$stmt->close();
		return $position;
	}
	
	public static function newPosition($npcName, $text) {
		$query = 'SELECT MAX(position_id) FROM dialogue_positions WHERE npc_name = ?';
		$stmt = MySQLCOnnection::getInstance()->prepare($query);
		$stmt->bind_param('s', $npcName);
		$stmt->execute();
		$stmt->store_result();
		$stmt->bind_result($maxId);
		$stmt->fetch();
		$stmt->close();
		$nextId = $maxId+1;
		
		$jumpTo = 0;
		
		$query = 'INSERT INTO dialogue_positions SET npc_name=?, position_id=?, position_text=?, position_jump_to=?';
		$stmt = MySQLConnection::getInstance()->prepare($query);
		
		$stmt->bind_param('sisi', $npcName, $nextId, $text, $jumpTo);
		$stmt->execute();
		$stmt->close();
		
		
		
		
		
		
		
		
	}
	
}


?>