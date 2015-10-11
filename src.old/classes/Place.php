<?php

class Place {
	private $id;
	private $x;
	private $y;
	private $name;
	private $quest;
	private $image;
	private $description;

  private function __construct($id,$x,$y,$name,$quest,$image,$description) {
    $this->id = $id;
    $this->x = $x;
    $this->y = $y;
    $this->name = $name;
    $this->quest = $quest;
    $this->image = $image;
    $this->description = $description;
  }


  public function __get($name) {
      switch($name) {
			case 'id': return $this->id;
			case 'x': return $this->x;
			case 'y': return $this->y;
			case 'name': return $this->name;
			case 'quest': return $this->quest;
			case 'image': return $this->image;
			case 'description': return $this->description;
			default: throw new Exception('Object of class Place has no member '.$name);
		}
  }
	
  public function __set($name, $value) {
		switch($name) {
			case 'x': $this->x = $value;break;
			case 'y': $this->y = $value;break;
			case 'name': $this->name = $value;break;
			case 'quest': $this->quest = $value;break;
			case 'image': $this->image = $value;break;
			case 'description': $this->description = $value;break;
			default: throw new Exception('Object of class Place has no member '.$name);
		}
  }
	
	public function save() {
		$db = MySQLConnection::getInstance();
    $query = 'UPDATE places SET place_x=?, place_y=?, place_name=?, place_quest=0, place_image=0, place_description=? WHERE place_id=?';
    $stmt = $db->prepare($query);
		$stmt->bind_param('iissi', $this->x, $this->y, $this->name, $this->description, $this->id);
    $stmt->execute();
	}
	
	public function delete() {
		$db = MySQLConnection::getInstance();
    $query = 'DELETE FROM places WHERE place_id=?';
    $stmt = $db->prepare($query);
		$stmt->bind_param('i', $this->id);
    $stmt->execute();
	}
	
	
	public static function newPlace($x,$y,$name,$quest,$image,$description) {
		$db = MySQLConnection::getInstance();
    $query = 'INSERT INTO places SET place_x=?, place_y=?, place_name=?, place_quest=0, place_image=0, place_description=?';
    $stmt = $db->prepare($query);
		$stmt->bind_param('iiss', $x, $y, $name, $description);
    $stmt->execute();
    
		
	}
	
	
  public static function getPlace($id) {
    $db = MySQLConnection::getInstance();
    $query = 'SELECT place_id, place_x, place_y, place_name, place_quest, place_image, place_description FROM places WHERE place_id = ?';
    $stmt = $db->prepare($query);
		$stmt->bind_param('i', $id);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($id,$x,$y,$name,$quest,$image,$description);
    $stmt->fetch();
    $place = new self($id,$x,$y,$name,$quest,$image,$description);
    return $place;
  }
	
  public static function getPlaceOnCoords($x, $y) {
    $db = MySQLConnection::getInstance();
    $query = 'SELECT place_id, place_x, place_y, place_name, place_quest, place_image, place_description FROM places WHERE place_x = ? AND place_y = ?';
    $stmt = $db->prepare($query);
		$stmt->bind_param('ii', $x, $y);
    $stmt->execute();
    $stmt->store_result();
		if ($stmt->num_rows == 0) return null;
    $stmt->bind_result($id,$x,$y,$name,$quest,$image,$description);
    $stmt->fetch();
    $place = new self($id,$x,$y,$name,$quest,$image,$description);
    return $place;
  }

	
	
  public static function getPlaces() {
    $db = MySQLConnection::getInstance();
    $query = 'SELECT place_id, place_x, place_y, place_name, place_quest, place_image, place_description FROM places';
    $stmt = $db->prepare($query);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($id,$x,$y,$name,$quest,$image,$description);
    $places = array();
    while($stmt->fetch()) {
      $places[] = new self($id,$x,$y,$name,$quest,$image,$description);
    }
    return $places;
  }

	

}

?>