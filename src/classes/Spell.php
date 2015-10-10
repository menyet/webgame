<?php


class Spell {
	
	
	private $name;
	private $title;
	private $description;
	private $APcost;
	private $defenseBonus;
	private $attackBonus;
	private $instantAttack;
	private $heal;
	
	private function __construct($name, $title, $description, $APcost, $defenseBonus, $attackBonus, $instantAttack, $heal) {
		$this->name = $name;
		$this->title = $title;
		$this->description = $description;
		$this->APcost = $APcost;
		$this->defenseBonus = $defenseBonus;
		$this->attackBonus = $attackBonus;
		$this->instantAttack = $instantAttack;
		$this->heal = $heal;
	}
	
	public function __get($name) {
		switch($name) {
			case 'name': return $this->name;
			case 'title': return $this->title;
			
			case 'APcost': return $this->APcost;
			case 'heal': return $this->heal;
		}
	}
	
	public static function getPlayersSpells($userId) {
		$query = 'SELECT s.spell_name, s.spell_title, s.spell_description, s.spell_ap_cost, s.spell_defense_bonus, s.spell_attack_bonus, s.spell_instant_attack, s.spell_heal FROM spells s, players_spells p WHERE s.spell_name = p.spell_name AND p.player_id = ?';
		$stmt = MySQLConnection::getInstance()->prepare($query);
		$stmt->bind_param('i', $userId);
		$stmt->execute();
		$stmt->store_result();
		$stmt->bind_result($name, $title, $description, $APcost, $defenseBonus, $attackBonus, $instantAttack, $heal);
		$spells = array();
		while($stmt->fetch()) {
			$spells[] = new self($name, $title, $description, $APcost, $defenseBonus, $attackBonus, $instantAttack, $heal);
		}
		return $spells;
	}
	
	public static function getPlayersSpell($userId, $spellName) {
		$query = 'SELECT s.spell_name, s.spell_title, s.spell_description, s.spell_ap_cost, s.spell_defense_bonus, s.spell_attack_bonus, s.spell_instant_attack, s.spell_heal FROM spells s, players_spells p WHERE s.spell_name = p.spell_name AND p.player_id = ? AND s.spell_name = ?';
		$stmt = MySQLConnection::getInstance()->prepare($query);
		$stmt->bind_param('is', $userId, $spellName);
		$stmt->execute();
		$stmt->store_result();
		if ($stmt->num_rows == 0) return null;
		$stmt->bind_result($name, $title, $description, $APcost, $defenseBonus, $attackBonus, $instantAttack, $heal);
		$stmt->fetch();
		$spell = new self($name, $title, $description, $APcost, $defenseBonus, $attackBonus, $instantAttack, $heal);
		$stmt->close();
		return $spell;
	}

	
}







?>