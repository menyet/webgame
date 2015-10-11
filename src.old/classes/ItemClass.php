<?php

class ItemClass {
	
	private $className;
	private $type;
	private $attackType;
	private $printName;
	private $image;
	private $description;
	private $rarity;
	private $ammo;
	private $baseWeight;
	private $baseAPCost;
	private $baseMinDamage;
	private $baseMaxDamage;
	private $baseArmor;
	private $baseMinStrength;
	private $baseMinLevel;
	private $baseSpotreba;
	
	public function __get($name) {

		switch($name) {
			case 'printName': return $this->printName;
			case 'image': return $this->image;
		}
		
	}
	
	public function __construct($className, $type, $attack_type, $print_name, $image, $description, $rarity, $ammo, $base_weight, $base_ap_cost, $base_dmg_min, $base_dmg_max, $base_armor, $base_min_str, $base_min_lvl, $base_spotreba) {
		$this->className = $className;
		$this->type = $type;
		$this->attackType = $attack_type;
		$this->printName = $print_name;
		$this->image = $image;
		$this->description = $description;
		$this->rarity = $rarity;
		$this->ammo = $ammo;
		$this->baseWeight = $base_weight;
		$this->baseAPCost = $base_ap_cost;
		$this->baseMinDamage = $base_dmg_min;
		$this->baseMaxDamage = $base_dmg_max;
		$this->baseArmor = $base_armor;
		$this->baseMinStrength = $base_min_str;
		$this->baseMinLevel= $base_min_lvl;
		$this->baseSpotreba = $base_spotreba;
	}
	
	public static function getItemClass($className) {
		$query = 'SELECT class, type, attack_type, print_name, image, description, rarity, ammo, base_weight, base_ap_cost, base_dmg_min, base_dmg_max, base_armor, base_min_str, base_min_lvl, base_spotreba
							FROM item_classes WHERE class=?';
		$stmt = MySQLConnection::getInstance()->prepare($query);
		$stmt->bind_param('s',$className);
		$stmt->execute();
		$stmt->store_result();
		if ($stmt->num_rows == 0) return null;
		$stmt->bind_result($className, $type, $attack_type, $print_name, $image, $description, $rarity, $ammo, $base_weight, $base_ap_cost, $base_dmg_min, $base_dmg_max, $base_armor, $base_min_str, $base_min_lvl, $base_spotreba);
		$stmt->fetch();
		return new self($className, $type, $attack_type, $print_name, $image, $description, $rarity, $ammo, $base_weight, $base_ap_cost, $base_dmg_min, $base_dmg_max, $base_armor, $base_min_str, $base_min_lvl, $base_spotreba);
	}
}


?>