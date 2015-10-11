<?php

class Location {
	private $x;
	private $y;
	private $location;
	private $encounter;
	
	public function __construct($x,$y, $location, $encounter) {
		$this->x = $x;
		$this->y = $y;
		$this->location = $location;
		$this->encounter = $encounter;
	}
	
	public function getEnemy() {
		if (mt_rand(1,1000) <= $this->encounter) {
			return 'creature_imp';			
		}
		
		return null;
		
	}
	
	public function __get($name) {
		switch($name) {
			case 'x': return $this->x;
			case 'y': return $this->y;
			case 'location': return $this->location;
			case 'encounter': return $this->encounter;
			default: throw new Exception('Object of class Location has no member '.$name);
			
		}
		
	}
	
	
	
	public function getUnavoidableTask() {
		$player = Player::actualPlayer();
		
		$quests = UsersQuest::getUsersQuests($player->id);
		
		
		foreach($quests as $quest) {
			$task = $quest->getActualTask();
			
			if ( ($task->type == TASK::TYPE_KILL) && ($task->param1 == $this->x) && ($task->param2 == $this->y)) {
				return $task;
			}
		}
		
		return null;
		
		
	}
	
	
	public static function getLocations() {
		$query = 'SELECT x, y, location, title, encounter FROM map';
		$stmt = MySQLConnection::getInstance()->prepare($query);
		$stmt->execute();
		$stmt->store_result();
		
		$locations = array();
		
		$stmt->bind_result($x, $y, $location, $title, $encounter);
		
		while($stmt->fetch()) {
			$locations[] = new self($x,$y,$location,$encounter);
		}
		
		$stmt->close();		
		return $locations;
	}
	
	public static function getLocation($x,$y) {
		$query = 'SELECT x, y, location, title, encounter FROM map WHERE x=? AND y=?';
		$stmt = MySQLConnection::getInstance()->prepare($query);
		$stmt->bind_param('ii', $x, $y);
		$stmt->execute();
		$stmt->store_result();
		
		$locations = array();
		
		$stmt->bind_result($x, $y, $location, $title, $encounter);
		
		$stmt->fetch();
		$location = new self($x,$y,$location,$encounter);
		
		
		$stmt->close();		
		return $location;
	}

	
	
}


?>