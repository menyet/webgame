<?php


class UsersItem {
	private $id;
 	private $playerId;
 	private $onGround;
 	private $equipped;
 	private $class;
 	private $sockets;
 	private $runes;
 	private $upgrades;
 	private $minDamage;
 	private $maxDamage;
 	private $armor;
 	private $APcost;
 	private $minStrength;
 	private $minLevel;
 	private $quantity;
 	private $ammoUsage;
 	private $x;
 	private $y;
 	private $weight;
	
	private $leftModifier;
	private $rightModifier;
	
	private function computeCost() {
		
		return 10;
	}
	
	private function __construct($id, $player_id, $on_ground, $equipped, $class, $sockets, $runes, $upgrades, $damage_min, $damage_max, $armor, $ap_cost, $min_str, $min_lvl, $quantity, $spotreba, $map_x, $map_y, $weight, $leftModifier, $rightModifier) {
		$this->id = $id;
		$this->playerId = $player_id;
		$this->onGround = $on_ground;
		$this->equipped = $equipped;
		$this->class = $class;
		$this->sockets = $sockets;
		$this->runes = $runes;
		$this->upgrades = $upgrades;
		$this->minDamage = $damage_min;
		$this->maxDamage = $damage_max;
		$this->armor = $armor;
		$this->APcost = $ap_cost;
		$this->minStrength = $min_str;
		$this->minLevel = $min_lvl;
		$this->quantity = $quantity;
		$this->ammoUsage = $spotreba;
		$this->x = $map_x;
		$this->y = $map_y;
		$this->weight = $weight;
		
		$this->leftModifier = $leftModifier;
		$this->rightModifier = $rightModifier;
	}
	
	public function __get($name) {
		switch($name) {
			case 'id': return $this->id;
			case 'player_id': return $this->playerId;
			case 'onGround': return $this->onGround;
			case 'equipped': return $this->equipped;
			case 'class': return $this->class;
			case 'sockets': return $this->sockets;
			case 'runes': return $this->runes;
			case 'upgrades': return $this->upgrades;
			case 'minDamage': return $this->minDamage;
			case 'maxDamage': return $this->maxDamage;
			case 'armor': return $this->armor;
			case 'APcost': return $this->APcost;
			case 'minStrength': return $this->minStrength;
			case 'minLevel': return $this->minLevel;
			case 'quantity': return $this->quantity;
			case 'ammoUsage': return $this->ammoUsage;
			case 'x': return $this->x;
			case 'y': return $this->y;
			case 'weight': return $this->weight;
			case 'leftModifier': return $this->leftModifier;
			case 'rightModifier': return $this->rightModifier;
			case 'cost': return $this->computeCost();
			default: throw new Exception('Object of class UsersItem has no member '.$name);
		}
	}
	
	public function __set($name, $value) {
		switch($name) {
			case 'id': return $this->id;
			case 'onGround': return $this->onGround = $value;
			case 'equipped': return $this->equipped = $value;
			case 'sockets': return $this->sockets;
			case 'runes': return $this->runes;
			case 'upgrades': return $this->upgrades;
			case 'minDamage': return $this->minDamage;
			case 'maxDamage': return $this->maxDamage;
			case 'armor': return $this->armor;
			case 'APcost': return $this->APcost;
			case 'minStrength': return $this->minStrength;
			case 'minLevel': return $this->minLevel;
			case 'quantity': return $this->quantity;
			case 'spotreba': return $this->spotreba;
			case 'x': return $this->x;
			case 'y': return $this->y;
			case 'weight': return $this->weight;
			
			case 'leftModifier': return $this->leftModifier;
			case 'rightModifier': return $this->rightModifier;
			
			case 'cost': return $this->computeCost();
			
			
			default: throw new Exception('Object of class UsersItem has no member '.$name);
		}
	}

	
	public function getItemClass() {
		return Item::getItem($this->class);
	}
	
	public function getItem() {
		return Item::getItem($this->class);
	}
	
	
	public function delete() {
		$query = 'DELETE FROM items WHERE id=?';
		$stmt = MySQLConnection::getInstance()->prepare($query);
		$stmt->bind_param('i',$this->id);
		$stmt->execute();
		$stmt->close();
		
	}
	
	public function save() {
		$query = 'UPDATE items SET player_id=?, on_ground=?, equipped=?, class=?, sockets=?, runes=?, upgrades=?, damage_min=?, damage_max=?, armor=?, ap_cost=?, min_str=?, min_lvl=?, quantity=?, spotreba=?, map_x=?, map_y=?, weight=?, modifier_left=?, modifier_right=? WHERE id=?';
		$stmt = MySQLConnection::getInstance()->prepare($query);
		$stmt->bind_param('iissisiiiiiiiiiiiissi',$this->playerId, $this->onGround, $this->equipped, $this->class, $this->sockets, $this->runes, $this->upgrades, $this->minDamage, $this->maxDamage, $this->armor, $this->APcost, $this->minStrength, $this->minLevel, $this->quantity, $this->ammoUsage, $this->x, $this->y, $this->weight, $this->leftModifier, $this->rightModifier, $this->id);
		$stmt->execute();
		
		
	}
	
	
	public static function getUsersItems($playerId) {
		$query = 'SELECT id, player_id, on_ground, equipped, class, sockets, runes, upgrades, damage_min, damage_max, armor, ap_cost, min_str, min_lvl, quantity, spotreba, map_x, map_y, 	weight, modifier_left, modifier_right FROM items WHERE player_id = ?';
		$stmt = MySQLConnection::getInstance()->prepare($query);
		$stmt->bind_param('i',$playerId);
		$stmt->execute();
		$stmt->store_result();
		//'iiiisisiiiiiiiiiiii', 
		$stmt->bind_result($id, $player_id, $on_ground, $equipped, $class, $sockets, $runes, $upgrades, $damage_min, $damage_max, $armor, $ap_cost, $min_str, $min_lvl, $quantity, $spotreba, $map_x, $map_y, $weight, $leftModifier, $rightModifier);
		$items = array();
		while ($stmt->fetch()) {
			$items[] = new self($id, $player_id, $on_ground, $equipped, $class, $sockets, $runes, $upgrades, $damage_min, $damage_max, $armor, $ap_cost, $min_str, $min_lvl, $quantity, $spotreba, $map_x, $map_y, $weight, $leftModifier, $rightModifier);
		}
		return $items;
	}
	
	public static function getUsersItem($playerId, $itemId) {
		$query = 'SELECT id, player_id, on_ground, equipped, class, sockets, runes, upgrades, damage_min, damage_max, armor, ap_cost, min_str, min_lvl, quantity, spotreba, map_x, map_y, 	weight, modifier_left, modifier_right FROM items WHERE player_id = ? AND id= ?';
		$stmt = MySQLConnection::getInstance()->prepare($query);
		$stmt->bind_param('ii',$playerId, $itemId);
		$stmt->execute();
		$stmt->store_result();
		if ($stmt->num_rows == 0) {
			return null;
		}
		//'iiiisisiiiiiiiiiiii', 
		$stmt->bind_result($id, $player_id, $on_ground, $equipped, $class, $sockets, $runes, $upgrades, $damage_min, $damage_max, $armor, $ap_cost, $min_str, $min_lvl, $quantity, $spotreba, $map_x, $map_y, $weight, $leftModifier, $rightModifier);
		$stmt->fetch();
		$item = new self($id, $player_id, $on_ground, $equipped, $class, $sockets, $runes, $upgrades, $damage_min, $damage_max, $armor, $ap_cost, $min_str, $min_lvl, $quantity, $spotreba, $map_x, $map_y, $weight, $leftModifier, $rightModifier);
		return $item;
	}
	
	public static function newUsersItem($player_id, $on_ground, $equipped, $class, $sockets, $runes, $upgrades, $damage_min, $damage_max, $armor, $ap_cost, $min_str, $min_lvl, $quantity, $spotreba, $map_x, $map_y, $weight, $leftModifier, $rightModifier) {
		$query = 'INSERT INTO items SET player_id=?, on_ground=?, equipped=?, class=?, sockets=?, runes=?, upgrades=?, damage_min=?, damage_max=?, armor=?, ap_cost=?, min_str=?, min_lvl=?, quantity=?, spotreba=?, map_x=?, map_y=?, weight=?, modifier_left=?, modifier_right=?';
		$stmt = MySQLConnection::getInstance()->prepare($query);
		$stmt->bind_param('iissisiiiiiiiiiiiiss',$player_id, $on_ground, $equipped, $class, $sockets, $runes, $upgrades, $damage_min, $damage_max, $armor, $ap_cost, $min_str, $min_lvl, $quantity, $spotreba, $map_x, $map_y, $weight, $leftModifier, $rightModifier);
		
		$stmt->execute();
		$stmt->store_result();
		$id = $stmt->insert_id;
		
		return new self($id, $player_id, $on_ground, $equipped, $class, $sockets, $runes, $upgrades, $damage_min, $damage_max, $armor, $ap_cost, $min_str, $min_lvl, $quantity, $spotreba, $map_x, $map_y, $weight, $leftModifier, $rightModifier);
		
	}
	
	public static function getEquippedItem($playerId, $equipped) {
		$query = 'SELECT id, player_id, on_ground, equipped, class, sockets, runes, upgrades, damage_min, damage_max, armor, ap_cost, min_str, min_lvl, quantity, spotreba, map_x, map_y, 	weight, modifier_left, modifier_right FROM items WHERE player_id = ? AND equipped=?';
		$stmt = MySQLConnection::getInstance()->prepare($query);
		$stmt->bind_param('is',$playerId, $equipped);
		$stmt->execute();
		$stmt->store_result();
		if ($stmt->num_rows == 0) {
			return null;
		}
		//'iiiisisiiiiiiiiiiii', 
		$stmt->bind_result($id, $player_id, $on_ground, $equipped, $class, $sockets, $runes, $upgrades, $damage_min, $damage_max, $armor, $ap_cost, $min_str, $min_lvl, $quantity, $spotreba, $map_x, $map_y, $weight, $leftModifier, $rightModifier);
		$stmt->fetch();
		$item = new self($id, $player_id, $on_ground, $equipped, $class, $sockets, $runes, $upgrades, $damage_min, $damage_max, $armor, $ap_cost, $min_str, $min_lvl, $quantity, $spotreba, $map_x, $map_y, $weight, $leftModifier, $rightModifier);
		return $item;
	}

	
}







?>