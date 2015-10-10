<?php

  class Task {
    private $questName;
    private $id;
    private $title;
    private $description;
		
		const TYPE_GENERAL = 1;
		const TYPE_KILL = 2;
		
    public function __construct($questName, $id, $title, $description, $type, $param1, $param2, $param3) {
      $this->questName = $questName;
      $this->id = $id;
      $this->title = $title;
      $this->description = $description;
			$this->type = $type;
			$this->param1 = $param1;
			$this->param2 = $param2;
			$this->param3 = $param3;
    }
    
    public function __get($name) {
      switch($name) {
        case 'questName': return $this->questName;
        case 'id': return $this->id;
        case 'title': return $this->title;
        case 'description': return $this->description;
				
				case 'type': return $this->type;
				case 'param1': return $this->param1;
				case 'param2': return $this->param2;
				case 'param3': return $this->param3;
				
        default: throw new Exception('Object of class Task has no member `'.$name.'`');
      }
    
    }
		
		public function __set($name, $value) {
      switch($name) {
        //case 'quest': $this->quest_id = $value;break;
        //case 'id': $this->task_id = $value;break;
        case 'title': $this->title = $value;break;
        case 'description': $this->description = $value;break;
				
				case 'type': $this->type = $value;break;
				case 'param1': $this->param1 = $value;break;
				case 'param2': $this->param2 = $value;break;
				case 'param3': $this->param3 = $value;break;
				
        default: throw new Exception('Object of class Task has no member `'.$name.'`');
      }
    
    }
		
		
		public function save() {
			$query = 'UPDATE quests_tasks SET task_title=?, task_description=?, task_type=?, task_param1=?, task_param2=?, task_param3=? WHERE quest_name=? AND task_id=?';
			$stmt = MySQLConnection::getInstance()->prepare($query);
			echo MySQLConnection::getInstance()->error;
			$stmt->bind_param('ssissssi', $this->title, $this->description, $this->type, $this->param1, $this->param2, $this->param3, $this->questName, $this->id);
			$stmt->execute();
			$stmt->close();
			
		}
		
		
    public static function getTasks($questName) {
      $query = 'SELECT quest_name, task_id, task_title, task_description, task_type, task_param1, task_param2, task_param3 FROM quests_tasks WHERE quest_name = ?';
      $stmt = MySQLConnection::getInstance()->prepare($query);
			//echo $this->sql->error;
      $stmt->bind_param('s',$questName);
      $stmt->execute();
      $stmt->store_result();
      $stmt->bind_result($row[0], $row[1], $row[2], $row[3], $type, $param1, $param2, $param3);
      $tasks = array();
      while ($stmt->fetch()) {
        $tasks[] = new Task($row[0], $row[1], $row[2], $row[3], $type, $param1, $param2, $param3);
      }
      return $tasks;
    }
		
    public static function getTask($questName, $taskId) {
      $query = 'SELECT quest_name, task_id, task_title, task_description, task_type, task_param1, task_param2, task_param3 FROM quests_tasks WHERE quest_name = ? AND task_id =?';
      $stmt = MySQLConnection::getInstance()->prepare($query);
			$stmt->bind_param('si',$questName, $taskId);
      $stmt->execute();
      $stmt->store_result();
      $stmt->bind_result($row[0], $row[1], $row[2], $row[3], $type, $param1, $param2, $param3);
      $stmt->fetch();
			$task = new Task($row[0], $row[1], $row[2], $row[3], $type, $param1, $param2, $param3);
      return $task;
    }
		
		
		public static function newTask($questName, $title, $description, $type, $param1, $param2, $param3) {
			$query = 'SELECT MAX(task_id) FROM quests_tasks WHERE quest_name=?';
			$stmt = MySQLConnection::getInstance()->prepare($query);
			
			$stmt->bind_param('s',$questName);
			$stmt->execute();
			$stmt->bind_result($taskId);
			$stmt->fetch();
			$stmt->close();
			
			$taskId++;
			
			
			$query = 'INSERT INTO quests_tasks SET quest_name = ?, task_id = ?, task_title = ?, task_description = ?, task_type = ?, task_param1 = ?, task_param2 = ?, task_param3 = ?';
			$stmt = MySQLConnection::getInstance()->prepare($query);
			
			
			$stmt->bind_param('sississs',$questName, $taskId, $title, $description, $type, $param1, $param2, $param3);
			$stmt->execute();
			$stmt->close();
			
			
		}
		
		
		
		
  
  }

?>