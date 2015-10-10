<?php

class UsersQuest {
	private $playerId;
	private $questName;
	private $actualTask;
	private $questState;
	
	private function __construct($playerId, $questName, $actualTask, $questState) {
		$this->playerId = $playerId;
		$this->questName = $questName;
		$this->actualTask = $actualTask;
		$this->questState = $questState;
	}
	
	public function __get($name) {
		switch($name) {
			case 'playerId': return $this->playerId;
			case 'questName': return $this->questName;
			case 'actualTask': return $this->actualTask;
			case 'state': return $this->questState;
			case 'quest': return Quest::getQuest($this->questName);
			default: throw new Exception('Object of class UsersQuest has no member '.$name);
			
		}
		
	}
	
	public function nextTask() {
		$query = 'SELECT task_id FROM quests_tasks WHERE quest_name=? AND task_id > ? LIMIT 1';
		$stmt = MySQLConnection::getInstance()->prepare($query);
		$stmt->bind_param('si', $this->questName, $this->actualTask);
		$stmt->execute();
		$stmt->store_result();
		if ($stmt->num_rows == 0) {
			$this->questState = 1;
			$this->actualTask = 0;
			$this->save();
			return 0;
		}
		
		$stmt->bind_result($nextTask);
		$stmt->fetch();
		
		$this->actualTask = $nextTask;
		$this->save();
	}
	
	public function save() {
		$query = 'UPDATE quests_players SET task_id=?, quest_finished=? WHERE player_id=? AND quest_name=?';
    $stmt = MySQLConnection::getInstance()->prepare($query);
    $stmt->bind_param('siis', $this->actualTask, $this->questState, $this->playerId, $this->questName);
    $stmt->execute();
    $stmt->close();
	}
	
	
	
	
	
	public function getTasks() {
		return Task::getTasks($this->questName);
	}
	
	public function getActualTask() {
		return Task::getTask($this->questName, $this->actualTask);
	}
	
	
  public static function getActiveQuests($playerId) {
    return self::getQuestsWithState(0, $playerId);
  }

  public static function getFinishedQuests($playerId) {
    return self::getQuestsWithState(1, $playerId);
  }

  public static function getFailedQuests($playerId) {
    return self::getQuestsWithState(2, $playerId);
  }




	public static function getUsersQuest($playerId, $questName) {
    $query = 'SELECT quest_name, task_id, player_id, quest_finished FROM quests_players
								WHERE player_id = ? AND quest_name=?';
		$stmt = MySQLConnection::getInstance()->prepare($query);
    $stmt->bind_param('is', $playerId, $questName);
    $stmt->execute();
    $stmt->store_result();
		if ($stmt->num_rows == 0) {
			return null;
		}
		
    $stmt->bind_result($questName, $taskId, $playerId, $questState);
    $stmt->fetch();
		return new UsersQuest($playerId, $questName, $taskId, $questState);
  }



	
	
	public static function getUsersQuests($playerId) {
    $query = 'SELECT quest_name, task_id, player_id, quest_finished FROM quests_players
								WHERE player_id = ?';
		$stmt = MySQLConnection::getInstance()->prepare($query);
    $stmt->bind_param('i', $playerId);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($questName, $taskId, $playerId, $questState);
    $quests = Array();
    while ($stmt->fetch()) {
    	$quests[] = new UsersQuest($playerId, $questName, $taskId, $questState);
    }
    return $quests;
  }

    public static function getQuestsWithState($state, $playerId) {
      $query = 'SELECT quest_name, task_id, player_id, quest_finished FROM quests_players
		              WHERE player_id = ? AND quest_finished = ?';
      $stmt = MySQLConnection::getInstance()->prepare($query);
      $stmt->bind_param('ii', $playerId, $state);
      $stmt->execute();
      $stmt->store_result();
      $stmt->bind_result($questName, $taskId, $playerId, $questState);
      $quests = Array();
      while ($stmt->fetch()) {
      	$quests[] = new self($playerId, $questName, $taskId, $questState);
      }
      return $quests;
    }
    
    
    
    public static function getQuestOfPlayer($quest, $playerId) {
			echo $quest.' '.$playerId;
      $query = 'SELECT quest_name, task_id, player_id, quest_finished FROM quests_players
		              WHERE player_id = ? AND quest_name = ?';
      $stmt = MySQLConnection::getInstance()->prepare($query);
			
			
      $stmt->bind_param('is', $playerId, $quest);
      $stmt->execute();
      $stmt->store_result();
      if ($stmt->num_rows==0) {
				return null;
			}
      
      $stmt->bind_result($questName, $taskId, $playerId, $questState);
      $stmt->fetch();
      $quest = new self($playerId, $questName, $taskId, $questState);
      
      return $quest;
    }

	
	
	
}








?>