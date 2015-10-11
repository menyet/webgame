<?php

class ItemModifier {
	
	private $className;
	private $printName;
	private $printStats;
	private $position;
	private $maxHP;
	private $maxAP;
	private $baseArmor;
	private $gender;
	private $criticalChance;
	private $capacity;
	private $replenishHP;
	private $chanceDay;
	private $chanceNight;
	private $rarity;
	
	private function __construct($className, $printName, $printStats, $position, $maxHP, $maxAP, $baseArmor, $gender, $criticalChance, $capacity, $replenishHP, $chanceDay, $chanceNight, $rarity) {
		$this->className = $className;
		$this->printName = $printName;
		$this->printStats = $printStats;
		$this->positions = $position;
		$this->maxHP = $maxHP;
		$this->maxAP = $maxAP;
		$this->baseArmor = $baseArmor;
		$this->gender = $gender;
		$this->criticalChance = $criticalChance;
		$this->capacity = $capacity;
		$this->replenishHP = $replenishHP;
		$this->chanceDay = $chanceDay;
		$this->chancNight = $chanceNight;
		$this->rarity = $rarity;
	}
	
	public function __get($name) {
		switch($name) {
			case 'className': return $this->className;
			case 'name': return $this->printName;
			case 'printStats': return $this->printStats;
			case 'position': return $this->position;
			case 'maxHP': return $this->maxHP;
			case 'maxAP': return $this->maxAP;
			case 'baseArmor': return $this->baseArmor;
			case 'gender': return $this->gender;
			case 'criticalChance': return $this->criticalChance;
			case 'capacity': return $this->capacity;
			case 'replenishHP': return $this->replenishHP;
			case 'chanceDay': return $this->chanceDay;
			case 'chanceNight': return $this->chanceNight;
			case 'rarity': return $this->rarity;
			default: throw new Exception('Object of class ItemModifier has no member '.$name);
		}
		
	}
	
	public function __set($name, $value) {
		switch($name) {
			case 'className': $this->className;
			case 'name': $this->printName;
			case 'printStats': $this->printStats;
			case 'position': $this->position;
			case 'maxHP': $this->maxHP;
			case 'maxAP': $this->maxAP;
			case 'baseArmor': $this->baseArmor;
			case 'gender': $this->gender;
			case 'criticalChance': $this->criticalChance;
			case 'capacity': $this->capacity;
			case 'replenishHP': $this->replenishHP;
			case 'chanceDay': $this->chanceDay;
			case 'chanceNight': $this->chanceNight;
			case 'rarity': $this->rarity;
		}
		
	}

	
	public static function getModifier($className) {
		$query = 'SELECT class, print_name, print_stats, position, hp_max, ap_max, base_armor, gender, critical_chance, nosnost, obnova_zdravia, mod_sanca_den, mod_sanca_noc, rarity FROM mod_classes WHERE class=?';
		$stmt = MySQLConnection::getInstance()->prepare($query);
		$stmt->bind_param('s',$className);
		$stmt->execute();
		$stmt->store_result();
		if ($stmt->num_rows == 0) return null;
		
		$stmt->bind_result($className, $printName, $printStats, $position, $maxHP, $maxAP, $baseArmor, $gender, $criticalChance, $capacity, $replenishHP, $chanceDay, $chanceNight, $rarity);
		$stmt->fetch();
		return new self($className, $printName, $printStats, $position, $maxHP, $maxAP, $baseArmor, $gender, $criticalChance, $capacity, $replenishHP, $chanceDay, $chanceNight, $rarity);
	}
}

?>