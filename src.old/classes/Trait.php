<?php


class PlayerTrait {
	
	
		public function addTraits($traits) {
		$query='DELETE FROM traits WHERE player_id=?';
		$stmt = $this->db->prepare($query);
		$stmt->bind_param('i',$this->id);
		$stmt->execute();
		$stmt->close();
		//print_r($traits);
		
		foreach($traits as $trait) {
			$query='INSERT INTO traits SET trait_id=?, player_id=?';
			$stmt = $this->db->prepare($query);
			$name = $trait->name;
			
			$stmt->bind_param('si',$name,$this->id);
			$stmt->execute();
			$stmt->close();
		}
		
	}
  
  public static function checkTrait($playerId, $trait) {
    $query = 'SELECT * FROM traits WHERE trait_id=? AND player_id=?';
    $stmt = MySQLConnection::getInstance()->prepare($query);
    $stmt->bind_param('si',$trait, $playerId);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows > 0) {
      $stmt->close();
      return true;
    }
    
      $stmt->close();
      return false;
    
    
    
    
  } 

	
	
	
	
	
	
	
	
	
	
}



?>