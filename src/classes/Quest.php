<?php

  require_once('QuestTask.php');
	

  define('TASK_FILTER_COMPLETED',1);
  define('TASK_FILTER_PENDING',2);
  define('TASK_FILTER_FAILED',4);
  define('TASK_FILTER_UNDISCOVERED',8);

  define('TASK_FILTER_DISCOVERED',1+2+4);
  define('TASK_FILTER_ALL',0);


  class Quest {


    private $id = 0;
    private $title = 'undefined';
		
		private $name;

    private $actualTask = 0;

    public function __construct($id, $name, $title, $player, $actualTask) {
      $this->id = $id;
      $this->title = $title;
			$this->name=$name;
    }

    public function __get($name) {
      switch ($name) {
				case 'id': return $this->id;
				case 'title': return $this->title;
				case 'first_task': return $this->start;
				case 'name': return $this->name;
      }
    }

    public function getTasks() {
      return Task::getTasks($this->name);
    }
		

      
    public function getFinishedTasks() {
    }
		
	public static function getAllQuests() {
      $query = 'SELECT q.quest_id, q.quest_name, q.quest_title FROM quests q';
      $stmt = MySQLConnection::getInstance()->prepare($query);
      $stmt->execute();
      $stmt->store_result();
      $stmt->bind_result($questId, $questName, $questTitle);
      $quests = Array();
      while ($stmt->fetch()) {
      	$quests[] = new Quest($questId, $questName, $questTitle, 0, 0);
      }
      return $quests;
    }

	public static function getQuest($questName) {
      $query = 'SELECT q.quest_id, q.quest_name, q.quest_title FROM quests q WHERE quest_name = ?';
      $stmt = MySQLConnection::getInstance()->prepare($query);
			$stmt->bind_param('s', $questName);
      $stmt->execute();
      $stmt->store_result();
      $stmt->bind_result($questId, $questName, $questTitle);
			$stmt->fetch();
			$quest = new Quest($questId, $questName, $questTitle, 0, 0);
      return $quest;
    }

      
      
    
    
    
    
    
    
    
    
    


    
    
    public static function addQuest($questName, $playerId) {
			$query = 'SELECT COUNT(*) FROM quests_players WHERE quest_name=? AND player_id=?';
			$stmt = MySQLConnection::getInstance()->prepare($query);
			//echo MySQLConnection::getInstance()->error;
      $stmt->bind_param('si', $questName, $playerId);
      $stmt->execute();
			$stmt->store_result();
			$stmt->bind_result($count);
			$stmt->fetch();
      $stmt->close();
			
			if ($count > 0) {
				return;
			}
			
			
      $query = 'INSERT INTO quests_players SET quest_name=?, task_id=1, player_id=?, quest_finished=0';
      $stmt = MySQLConnection::getInstance()->prepare($query);
      $stmt->bind_param('si', $questName, $playerId);
      $stmt->execute();
      $stmt->close();
    }
		
		
		public static function newQuest($questName, $questTitle) {
			$query = 'INSERT INTO quests SET quest_name=?, quest_title=?';
			$stmt = MySQLConnection::getInstance()->prepare($query);
      $stmt->bind_param('ss', $questName, $questTitle);
      $stmt->execute();
      $stmt->close();
		}
		

  }




?>
