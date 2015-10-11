<?php


class Perk {
		public static function getAllPerks() {
			$query = "SELECT id, name, description, level FROM perks";
			$stmt = MySQLConnection::getInstance()->prepare($query);
			$stmt->execute();
			$stmt->bind_result($row[0], $row[1], $row[2], $row[3]);
		
			while ($stmt->fetch()) {
				$perk = new PERK();
				$perk->id = $row[0];
				$perk->name = $row[1];
				$perks[] = $perk;
			}
			return $perks;
		}
	
	public static function checkPerk($perk_id, $playerId) {
		$query = 'SELECT * FROM players_perks WHERE player_id = ? AND perk_id = ?';
		
		$stmt = MySQLConnection::getInstance()->prepare($query);
		
		$stmt->bind_param('ii',$playerId, $perk_id);
		
		$stmt->execute();
		
		if ($stmt->num_rows == 1) {
			return true;
		}
		
		return false;
	}
	
	function addPerk($perk_id) {
		$query = 'INSERT INTO players_perks (player_id, perk_id) VALUES ( ?, ?)';
		$stmt = $this->sql->prepare($query);
		
		$stmt->bind_param('ii', $this->id, $perk_id);
		$stmt->execute();
		
		
		$this->volne_perky = $this->volne_perky-1;
		$this->save_zrucnosti();
	}

	
}

?>