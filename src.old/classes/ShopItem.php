<?php


class ShopItem {
	private $id;
 	private $npcName;
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
 	private $spotreba;
 	private $weight;
	
	private $leftModifier;
	private $rightModifier;
	
	private function __construct($id, $npcName, $class, $sockets, $runes, $upgrades, $damage_min, $damage_max, $armor, $ap_cost, $min_str, $min_lvl, $quantity, $spotreba, $weight, $leftModifier, $rightModifier) {
		$this->id = $id;
		$this->npcName = $npcName;
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
		$this->weight = $weight;
		
		$this->leftModifier = $leftModifier;
		$this->rightModifier = $rightModifier;
	}
	
	public function computeCost() {
		return 10;
		
	}
	
	
	public function __get($name) {
		switch($name) {
			case 'id': return $this->id;
			case 'npcName': return $this->npcName;
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
			case 'spotreba': return $this->ammoUsage;
			case 'weight': return $this->weight;
			
			case 'leftModifier': return $this->leftModifier;
			case 'rightModifier': return $this->rightModifier;
			
			case 'cost': return $this->computeCost();
			
			default: throw new Exception('Object of class UsersItem has no member '.$name);
		}
	}
	
	public function getItem() {
		return Item::getItem($this->class);
		
	}
	
	
	public static function getShopItem($itemId) {
		$query = 'SELECT id, npc_name, class, sockets, runes, upgrades, damage_min, damage_max, armor, ap_cost, min_str, min_lvl, quantity, spotreba, weight, modifier_left, modifier_right
							FROM shop_items WHERE id = ?';
		$stmt = MySQLConnection::getInstance()->prepare($query);
		$stmt->bind_param('i',$itemId);
		$stmt->execute();
		$stmt->store_result();
		
		if ($stmt->num_rows == 0) return null;
		
		//'iiiisisiiiiiiiiiiii', 
		$stmt->bind_result($id, $npcName, $class, $sockets, $runes, $upgrades, $damage_min, $damage_max, $armor, $ap_cost, $min_str, $min_lvl, $quantity, $spotreba, $weight, $leftModifier, $rightModifier);
		$stmt->fetch();
		$item = new self($id, $npcName, $class, $sockets, $runes, $upgrades, $damage_min, $damage_max, $armor, $ap_cost, $min_str, $min_lvl, $quantity, $spotreba, $weight, $leftModifier, $rightModifier);
		$stmt->close();
		return $item;
	}

	
	
	public static function getShopItems($npcName) {
		$query = 'SELECT id, npc_name, class, sockets, runes, upgrades, damage_min, damage_max, armor, ap_cost, min_str, min_lvl, quantity, spotreba, weight, modifier_left, modifier_right
							FROM shop_items WHERE npc_name = ?';
		$stmt = MySQLConnection::getInstance()->prepare($query);
		$stmt->bind_param('s',$npcName);
		$stmt->execute();
		$stmt->store_result();
		//'iiiisisiiiiiiiiiiii', 
		$stmt->bind_result($id, $npcName, $class, $sockets, $runes, $upgrades, $damage_min, $damage_max, $armor, $ap_cost, $min_str, $min_lvl, $quantity, $spotreba, $weight, $leftModifier, $rightModifier);
		$items = array();
		while ($stmt->fetch()) {
			$items[] = new self($id, $npcName, $class, $sockets, $runes, $upgrades, $damage_min, $damage_max, $armor, $ap_cost, $min_str, $min_lvl, $quantity, $spotreba, $weight, $leftModifier, $rightModifier);
		}
		return $items;
	}
	
}






?>