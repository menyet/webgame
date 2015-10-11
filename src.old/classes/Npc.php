<?php

require_once('mysql.php');


class NPC {
  private $name = 0;
	private $placeId = 0;
  private $posX = -1;
  private $posY = -1;
  private $title = 'undefined';
  private $dialogue = 0;
  private $image = '';
	
	private $hp;
	private $strength;
	private $defense;
  
  private function __construct($name, $placeId, $x, $y, $title, $dialogue, $image, $hp, $strength, $defense) {
    $this->name = $name;
		$this->placeId = $placeId;
    $this->posX = $x;
    $this->posY = $y;
    $this->title = $title;
    $this->dialogue = $dialogue;
    $this->image = $image;
		$this->hp = $hp;
		$this->strength = $strength;
		$this->defense = $defense;
  }

  public function __get($name) {
    switch($name) {
			case 'placeId': return $this->placeId;
      case 'x':		return $this->posX;
      case 'y':		return $this->posY;
      case 'name':	return $this->name;
      case 'dialogue':	return $this->dialogue;
      case 'image':	return $this->image;
			case 'title':	return $this->title;
			
			case 'hp': return $this->hp;
			case 'strength': return $this->strength;
			case 'defense': return $this->defense;
			
			default: throw new Exception('Object of class NPC has no member '.$name);
    }
  }
	
  public function __set($name, $value) {
    switch($name) {
			case 'placeId': $this->placeId = $value;break;
      case 'x':		$this->posX = $value;break;
      case 'y':		$this->posY = $value;break;
      case 'name':	$this->name = $value;break;
      case 'dialogue':	$this->dialogue = $value;break;
      case 'image':	$this->image = $value;break;
			case 'title':	$this->title = $value;break;
			
			case 'hp': $this->hp = $value;break;
			case 'strength': $this->strength = $value;break;
			case 'defense': $this->defense = $value;break;
			default: throw new Exception('Object of class NPC has no member '.$name);
    }
  }

	public function save() { 
		$query = '
	      UPDATE non_playing_characters SET
					npc_place = ?, npc_position_x=?, npc_position_y=?, npc_title=?, npc_dialogue=?, npc_image=?, npc_hp=?, npc_strength=?, npc_defense=?
	      WHERE npc_name = ?';
    $stmt = MySQLConnection::getInstance()->prepare($query);

		$stmt->bind_param('iiisiiiiis', $this->placeId, $this->posX, $this->posY, $this->title, $this->dialogue, $this->image, $this->hp, $this->strength, $this->defense, $this->name);
		$stmt->execute();
		$stmt->close();		
	}

  public static function getNPCs($placeId) {
    $query = '
	      SELECT npc_name, npc_place, npc_position_x, npc_position_y, npc_title, npc_dialogue, npc_image, npc_hp, npc_strength, npc_defense FROM non_playing_characters 
	      WHERE npc_place = ?';
    $stmt = MySQLConnection::getInstance()->prepare($query);

    $stmt->bind_param('i', $placeId);
    $stmt->execute();
    $stmt->bind_result($name, $placeid, $x, $y, $title, $dialogue, $image, $hp, $strength, $defense);
    $NPCs = array();

    while($stmt->fetch()) {
      $NPCs[] = new NPC($name, $placeId, $x, $y, $title, $dialogue, $image, $hp, $strength, $defense);
    }

    $stmt->free_result();
    return $NPCs;
  }
	
	public static function getAllNPCs() {
    $query = 'SELECT npc_name, npc_place, npc_position_x, npc_position_y, npc_title, npc_dialogue, npc_image, npc_hp, npc_strength, npc_defense FROM non_playing_characters ';
    $stmt = MySQLConnection::getInstance()->prepare($query);
    $stmt->execute();
    $stmt->bind_result($name, $placeId, $x, $y, $title, $dialogue, $image, $hp, $strength, $defense);
    $NPCs = array();
    while($stmt->fetch()) {
      $NPCs[] = new NPC($name, $placeId, $x, $y, $title, $dialogue, $image, $hp, $strength, $defense);
    }
    $stmt->free_result();
    return $NPCs;
  }
	
	

  public static function getNPC($name) {
    $query = '
	      SELECT npc_name, npc_place, npc_position_x, npc_position_y, npc_title, npc_dialogue, npc_image, npc_hp, npc_strength, npc_defense FROM non_playing_characters 
	      WHERE npc_name = ?';
    $stmt = MySQLConnection::getInstance()->prepare($query);

    $stmt->bind_param('s', $name);

    $stmt->execute();

    $stmt->bind_result($name, $placeId, $x, $y, $title, $dialogue, $image, $hp, $strength, $defense);

    $stmt->fetch();
    $stmt->free_result();

    return new NPC($name, $placeId, $x, $y, $title, $dialogue, $image, $hp, $strength, $defense);
  }

	public static function newNPC($name, $placeId, $posX, $posY, $title, $dialogue, $hp, $strength, $defense) {
    $query = 'INSERT INTO non_playing_characters SET npc_name=?, npc_place=?, npc_position_x=?, npc_position_y=?, npc_title=?, npc_dialogue=?, npc_image=0, npc_hp=?, npc_strength=?, npc_defense=?';
    $stmt = MySQLConnection::getInstance()->prepare($query);
    $stmt->bind_param('siiisiiii',$name, $placeId, $posX, $posY, $title, $dialogue, $hp, $strength, $defense);
    $stmt->execute();
    $stmt->close();
  }





}


?>