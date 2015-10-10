<?php

require_once('attributes.php');
require_once('Inventory.php');

require_once('UsersItem.php');

require_once('Quest.php');
require_once('Trait.php');

require_once('Perk.php');


class PlayerOverloadedException extends Exception {}


class Player {
	private static $actualPlayer = null;
	
	private $id;
	private $username;
	private $name;
	private $gender;
	private $race;
	private $x;
	private $y;
	private $hp;
	private $hp_max;
	private $xp;
	private $turns_played;
	private $turns_remaining;
	private $last_action;
	private $critical_chance;
	private $perk_multiplier;
	private $base_armor;
	private $base_action_points;
	private $base_nosnost;
	private $base_replenish_life;
	private $mod_chance_day;
	private $mod_chance_night;
	private $mod_skillpoints_per_level;
	
	private $strength;
	private $endurance;
	private $intelligence;
	private $speed;
	private $charisma;
	private $luck;
	
	private $melee;
	private $ranged;
	private $alien;
	private $magic_attack;
	private $magic_defense;
	private $merchant;
	private $thief;
	private $gambling;
	private $healing;
	private $alchemy;
	
	private $free_abilities;
	private $free_perks;
	private $rank;
	private $dialogueNPC;
	private $registerState;
	private $email;
	
	private $gold;
	
	private $shop;
	
	
	
	private $sql = null;
	private $db = null;
	

  
  
  
  private $attributes = null;
  private $inventory = null;
  private $quests = null;
  
	
	public function __clone() {
		trigger_error('Clone is not allowed.', E_USER_ERROR);
	}
	
	
	//========================================================================================================
	
	// nastavenie momentalneho stavu HP
	function SetHP($value){
		mysql_query("UPDATE players SET hp='$value' WHERE id='".$this->id."';",DATABASE);
	}
	
	// maximalny pocet hit-pointov
	
// zakladny armor hraca
	function getTotalArmor(){
		/*$res = $this->sql->query('SELECT base_armor FROM players WHERE id=\''.$this->id.'\'');
		$line = $res->fetch_assoc();
		$ret = $line['base_armor'];*/
		
		$sum = $this->baseArmor;
		$item = UsersItem::getEquippedItem($this->id, 'armor');

		
		if ($item != null) {
			$sum += $item->armor;
		}
		
		//if (isset($GLOBALS['modifiers']['base_armor'])) $ret += $GLOBALS['modifiers']['base_armor'];
		return $sum;
	}
	
	// celkovy armor hraca
	
	

	
	
	
	// momentalny level
	function GetLevel(){
		$res = $this->sql->query('SELECT lvl FROM level_values WHERE exp<=\''.$this->xp.'\' ORDER BY lvl DESC LIMIT 1');
		$line = $res->fetch_assoc();
		$ret = $line['lvl'];
		return $ret;
	}
	
	// XP potrebne na dalsi level
	function GetXPNextLevel(){
		$res = $this->sql->query('SELECT exp FROM level_values WHERE exp>\''.$this->xp.'\' ORDER BY lvl ASC LIMIT 1');
		$line = $res->fetch_assoc();
		$ret = $line['exp'];
		return $ret;
	}
	
	// XP ktore bolo potrebne na tento level
	function GetXPThisLevel(){
		
		$res = MySQLConnection::getInstance()->query('SELECT exp FROM level_values WHERE exp<=\''.$this->xp.'\' ORDER BY lvl DESC LIMIT 1');
		$line = $res->fetch_assoc();
		$ret = $line['exp'];
		return $ret;
	}
	
	// pridaj XP
	public function addXP($count){
		
		
		
		
		if (($this->xp + $count) >= $this->GetXPNextLevel() ) {
			
			$this->skillPoints = $this->skillPoints + $this->skillPointsPerLevel;
			//throw new exception;
		}
		
		$this->xp = $this->xp + $count;
		
		$this->save();
		

	}
	
	// pohlavie hraca
	function getGender(){
		return $this->gender;
	}
	
	// nastavenie terajsej pozicie, resp. nazvu skriptu lokacie
	function SetCurrentLocation($filename){
		$this->sql->query('UPDATE `players` SET `current_location` = \''.$filename.'\' WHERE `id` =\''.$this->id.'\' LIMIT 1;');
	}
	

	
	// maximalny pocet action pointov
	function GetAPMax() {
		$query = 'SELECT base_action_points FROM players WHERE id=? LIMIT 1;';
		
		$stmt = $this->sql->prepare($query);
		
		$stmt->bind_param('i',$this->id);
		
		$stmt->execute();
		$ret=0;
		$stmt->bind_result($ret);
		
		if (isset($GLOBALS['modifiers']['ap_max'])) $ret += $GLOBALS['modifiers']['ap_max'];
		return $ret;
	}
	
	// sanca na kriticky zasah
	function GetCriticalChance() {
		$line = mysql_fetch_assoc(mysql_query('SELECT critical_chance FROM players WHERE id=\''.$this->id.'\' LIMIT 1;',DATABASE));
		$ret = $line['critical_chance'];
		if (isset($GLOBALS['modifiers']['critical_chance'])) $ret += $GLOBALS['modifiers']['critical_chance'];
		return $ret;
	}
	
	// vrati nosnost hraca
	function getTotalCapacity() {
		$ret = $this->baseCapacity;
		if (isset($GLOBALS['modifiers']['nosnost'])) $ret += $GLOBALS['modifiers']['nosnost'];
		return $ret;
	}
	
	// vrati modifikator hraca na zasah cez den
	function GetModSancaDen() {
		$line = mysql_fetch_assoc(mysql_query('SELECT mod_sanca_den FROM players WHERE id=\''.$this->id.'\' LIMIT 1;',DATABASE));
		$ret = $line['mod_sanca_den'];
		if (isset($GLOBALS['modifiers']['mod_sanca_den'])) $ret += $GLOBALS['modifiers']['mod_sanca_den'];
		return $ret;
	}
	
	// vrati modifikator hraca na zasah cez noc
	function GetModSancaNoc() {
		$line = mysql_fetch_assoc(mysql_query('SELECT mod_sanca_noc FROM players WHERE id=\''.$this->id.'\' LIMIT 1;',DATABASE));
		$ret = $line['mod_sanca_noc'];
		if (isset($GLOBALS['modifiers']['mod_sanca_noc'])) $ret += $GLOBALS['modifiers']['mod_sanca_noc'];
		return $ret;
	}
	

	
	
	// suma hnostnosti vsetkych hracovych predmetov
	function GetItemsWeight() {
		$ret = 0;
		$r = $this->sql->query("SELECT weight FROM items,item_classes WHERE player_id='".$this->id."' AND on_ground='0' AND items.class=item_classes.class;");

		while ($l = $r->fetch_assoc()) {
			$ret += $l['weight'];
		}
		return $ret;
	}
	
	// funkcia ktora urci, ci je hrac pretazeny
	function Overloaded() {
    
		if ($this->GetItemsWeight() <= $this->totalCapacity) {
			return false;
		} else {
			return true;
		}
	}
	
	//========================================================================================================
	
	private function __construct() {
		$this->sql = MySQLConnection::getInstance();
        $this->db = MySQLConnection::getInstance();
		
		// vyplni id podla session
		$this->id = $this->sql->real_escape_string($_SESSION['id']);
		
    $this->attributes = new PlayerAttributes($this->id);
    $this->inventory  = new Inventory($this->id);
    //$this->quests     = new QuestManager($this->id);
		
		
		$query = '
		SELECT 	id, username, name, gender, race, x, y, hp, hp_max, xp, turns_played, turns_remaining, last_action, critical_chance, perk_multiplier, 
						base_armor, base_action_points, base_nosnost, base_obnova_zdravia, mod_sanca_den, mod_sanca_noc, mod_skillpointy_za_level, 
						atribut_sila, atribut_vydrz, atribut_inteligencia, atribut_rychlost, atribut_charizma, atribut_stastie, 
						zrucnost_chladne_zbrane, zrucnost_strelne_zbrane, zrucnost_cudzinecke_zbrane, zrucnost_utocna_magia, zrucnost_obranna_magia, 
						zrucnost_obchodovanie, zrucnost_kradnutie, zrucnost_gamblovanie, zrucnost_liecenie, zrucnost_vyroba_lektvarov, 
						volne_zrucnosti, volne_perky, rank, player_dialogue, register_state, email, gold, shop
		FROM players
		WHERE id = ?'; 
		
		$stmt = $this->sql->prepare($query);
		$stmt->bind_param('i',$this->id);
		$stmt->execute();
		
		
		$stmt->store_result();
		$stmt->bind_result($this->id, $this->username, $this->name, $this->gender, $this->race, $this->x, $this->y, $this->hp, $this->hp_max, $this->xp, 
						$this->turns_played, $this->turns_remaining, $this->last_action, $this->critical_chance, $this->perk_multiplier, 
						$this->base_armor, $this->base_action_points, $this->base_nosnost, $this->base_replenish_life, $this->mod_chance_day, $this->mod_chance_night, $this->mod_skillpoints_per_level, 
						$this->strength, $this->endurance, $this->intelligence, $this->speed, $this->charisma, $this->luck, 
						$this->melee, $this->ranged, $this->alien, $this->magic_attack, $this->magic_defense, 
						$this->merchant, $this->thief, $this->gambling, $this->healing, $this->alchemy, 
						$this->free_abilities, $this->free_perks, $this->rank, $this->dialogueNPC, $this->registerState, $this->email, $this->gold, $this->shop);
						
		$stmt->fetch();
		
		//$this->finalizeRegistration();
	}
	
	// aktualizuje pocet volnych tahov (v databaze, aj v premennej $this->turns_remaining)
	function refresh_turns($last_action,$turns_remaining){
		$now = time();
		$add = (($now - ($now % TURN_LENGTH)) - ($last_action - ($last_action % TURN_LENGTH))) / TURN_LENGTH;
		$this->sql->query('UPDATE players SET last_action = \''.time().'\', turns_remaining=\''.min(($turns_remaining+$add),MAX_TURNS).'\' WHERE id='.$this->id.';');
		$this->turns_remaining = min(($turns_remaining+$add),MAX_TURNS);
	}
	
	// ak ma hrac volne tahy, tak pocet $count volnych odrata a prirata odohrate
	// ak nema dost volnych, vrati false, inak vrati true
	function play_turns($count){
		if ($this->turns_remaining < $count) {
			return 0;
		} else {
			mysql_query('UPDATE players SET turns_played = \''.($this->turns_played+$count).'\', turns_remaining = \''.($this->turns_remaining-$count).'\' WHERE id='.$this->id.';',DATABASE);
			return 1;
		}
	}
	
	
	private function GetArmor() {
		$ret = $this->GetBaseArmor();
		foreach ($this->inventory->items_equipped as $key => $value) {
			$ret += $value->armor;
		}
		return $ret;
	}
  
  private function GetDayHalf() {
    $hour = $this->hour;
    if (($hour >= 20) || ($hour < 8)) {
			return 'noc';
		} else {
			return 'deň';
		} 
  }
	
	
	
	
	
	
	public function __get($name) {
				switch($name) {
						case 'id': return $this->id;
						case 'userName': return $this->username;
						case 'name': return $this->name;
						case 'gender': return $this->getGender();
						case 'race': return $this->race;
						case 'x': return $this->x;
						case 'y': return $this->y;
						case 'hp': return $this->hp;
						case 'maxHP': return $this->hp_max;
						case 'xp': return $this->xp;
						case 'turnsPlayed': return $this->turns_played;
						case 'turnsRemaining': return $this->turns_remaining;
						case 'lastAction': return $this->last_action;
						case 'criticalChance': return $this->critical_chance;
						case 'perkMultiplier': return $this->perk_multiplier;
						case 'baseArmor': return $this->base_armor;
						case 'baseAP': return $this->base_action_points;
						case 'baseCapacity': return $this->base_nosnost;
						case 'baseReplenishLife': return $this->base_replenish_life;
						case '': return $this->mod_chance_day;
						case '': return $this->mod_chance_night;
						case 'skillPointsPerLevel': return $this->mod_skillpoints_per_level;
						
						case 'baseStrength': return $this->strength;
						case 'baseEndurance': return $this->endurance;
						case 'baseIntelligence': return $this->intelligence;
						case 'baseSpeed': return $this->speed;
						case 'baseCharisma': return $this->charisma;
						case 'baseLuck': return $this->luck;
						
						case 'baseMelee': return $this->melee;
						case 'baseRanged': return $this->ranged;
						case 'baseAlien': return $this->alien;
						case 'baseAttackMagic': return $this->magic_attack;
						case 'baseDefenseMagic': return $this->magic_defense;
						case 'baseMerchant': return $this->merchant;
						case 'baseThief': return $this->thief;
						case 'baseGambling': return $this->gambling;
						case 'baseHealing': return $this->healing;
						case 'baseAlchemy': return $this->alchemy;
						
						
						case 'baseArmor': return $this->base_armor;
						case 'totalArmor': return $this->getTotalArmor();
						
						
						case 'baseAP': return $this->base_action_points;
						
						case 'baseCapacity': return $this->base_nosnost;
						case 'totalCapacity': return $this->getTotalCapacity();
						
						case 'baseReplenishLife': return $this->base_replenish_life;
						case '': return $this->mod_chance_day;
						case '': return $this->mod_chance_night;
						
						case 'totalStrength': return $this->strength;
						case 'totalEndurance': return $this->endurance;
						case 'totalIntelligence': return $this->intelligence;
						case 'totalSpeed': return $this->speed;
						case 'totalCharisma': return $this->charisma;
						case 'totalLuck': return $this->luck;
						case 'totalMelee': return $this->melee;
						case 'totalRanged': return $this->ranged;
						case 'totalAlien': return $this->alien;
						case 'totalAttackMagic': return $this->magic_attack;
						case 'totalDefenseMagic': return $this->magic_defense;
						case 'totalMerchant': return $this->merchant;
						case 'totalThief': return $this->thief;
						case 'totalGambling': return $this->gambling;
						case 'totalHealing': return $this->healing;
						case 'totalAlchemy': return $this->alchemy;
						
						
						
						case 'skillPoints': return $this->free_abilities;
						case 'freePerks': return $this->free_perks;
						case 'rank': return $this->rank;
						case 'dialogueNPC': return $this->dialogueNPC;
						case 'registerState': return $this->registerState;
						case 'email': return $this->email;
						
						case 'gold': return $this->gold;
						case 'shop': return $this->shop;
						
          case 'attributes': return $this->attributes;
					
          case 'age': return (20 + floor($this->turns_played / 8760));	// 20 rokov + 1 hodina za kazdy odohraty tah - zaokruhlene nadol
          case 'hour': return ($this->turns_played % 24);
          case 'day': return ((($this->turns_played / (24)) % 30) + 1);
          case 'month': return ((($this->turns_played / (24*30)) % 13) + 1);
          case 'dayhalf': return $this->GetDayHalf();
          case 'datetime': return $this->day.'.'.$this->month.'. - '.$this->hour.' h. ('.$this->dayhalf.')';
          
          case 'registerState': return $this->registerState;
					
			/**********************************************/
			/*	Computed attributes, read-only            */
			/**********************************************/
			
			case 'level': return $this->getLevel();

          
          default: throw new Exception('Object of class Player has no attribute '.$name);

				}
		
    }
	
	public function __set($name, $value) {
		switch($name) {

			case 'id': $this->id = $value;break;
			case 'userName': $this->username = $value;break;
			case 'name': $this->name = $value;break;
			case 'gender': $this->gender = $value;break;
			case 'race': $this->race = $value;break;
			case 'x': $this->x = $value;break;
			case 'y': $this->y = $value;break;
			case 'hp': $this->hp = $value;break;
			case 'maxHP': $this->hp_max = $value;break;
			case 'xp': 								$this->xp = $value = $value;break;
			case 'turnsPlayed': $this->turns_played = $value;break;
			case 'turnsRemaining': $this->turns_remaining = $value;break;
			case 'lastAction': $this->last_action = $value;break;
			case 'criticalChance': $this->critical_chance = $value;break;
			case 'perkMultiplier': $this->perk_multiplier = $value;break;
			case 'baseArmor': $this->base_armor = $value;break;
			case 'baseAP': $this->base_action_points = $value;break;
			case 'baseCapacity': $this->base_nosnost = $value;break;
			case 'baseReplenishLife': $this->base_replenish_life = $value;break;
			case '': $this->mod_chance_day = $value;break;
			case '': $this->mod_chance_night = $value;break;
			case '': $this->mod_skillpoints_per_level = $value;break;

			case 'baseStrength': $this->strength = $value;break;
			case 'baseEndurance': $this->endurance = $value;break;
			case 'baseIntelligence': $this->intelligence = $value;break;
			case 'baseSpeed': $this->speed = $value;break;
			case 'baseCharisma': $this->charisma = $value;break;
			case 'baseLuck': $this->luck = $value;break;

			case 'baseMelee': $this->melee = $value;break;
			case 'baseRanged': $this->ranged = $value;break;
			case 'baseAlien': $this->alien = $value;break;
			case 'baseAttackMagic': $this->magic_attack = $value;break;
			case 'baseDefenseMagic': $this->magic_defense = $value;break;
			case 'baseMerchant': $this->merchant = $value;break;
			case 'baseThief': $this->thief = $value;break;
			case 'baseGambling': $this->gambling = $value;break;
			case 'baseHealing': $this->healing = $value;break;
			case 'baseAlchemy': $this->alchemy = $value;break;

			case 'totalArmor': $this->base_armor = $value;break;
			case 'baseAP': $this->base_action_points = $value;break;
			case 'baseCapacity': $this->base_nosnost = $value;break;
			case 'baseReplenishLife': $this->base_replenish_life = $value;break;
			case '': $this->mod_chance_day = $value;break;
			case '': $this->mod_chance_night = $value;break;
			case '': $this->mod_skillpoints_per_level = $value;break;
			case 'totalStrength': $this->strength = $value;break;
			case 'totalEndurance': $this->endurance = $value;break;
			case 'totalIntelligence': $this->intelligence = $value;break;
			case 'totalSpeed': $this->speed = $value;break;
			case 'totalCharisma': $this->charisma = $value;break;
			case 'totalLuck': $this->luck = $value;break;
			case 'totalMelee': $this->melee = $value;break;
			case 'totalRanged': $this->ranged = $value;break;
			case 'totalAlien': $this->alien = $value;break;
			case 'totalAttackMagic': $this->magic_attack = $value;break;
			case 'totalDefenseMagic': $this->magic_defense = $value;break;
			case 'totalMerchant': $this->merchant = $value;break;
			case 'totalThief': $this->thief = $value;break;
			case 'totalGambling': $this->gambling = $value;break;
			case 'totalHealing': $this->healing = $value;break;
			case 'totalAlchemy': $this->alchemy = $value;break;

			case 'skillPoints': $this->free_abilities = $value;break;
			case 'freePerks': $this->free_perks = $value;break;
			case 'rank': $this->rank = $value;break;
			
			case 'dialogueNPC': $this->dialogueNPC = $value;break;
			
			case 'registerState': $this->registerState = $value;break;
			case 'email': $this->email = $value;break;
			
			case 'gold': $this->gold = $value;break;
			
			case 'shop': $this->shop = $value;break;
			
						
      case 'attributes': $this->attributes = $value;break;
					
      case 'registerState': $this->registerState = $value;break;
			
			
			
			
			
          
      default: throw new Exception('Object of class Player has no attribute '.$name);
		}
		
  }
		
		



	public function checkHealthPoints() {
	  if ($this->hp > $this->hp_max) $this->hp = $this->max_hp;
	}


	public function move($x,$y) {
		if ($this->Overloaded()) {
			//throw new PlayerOverloadedException();
		}

		$db = MySQLConnection::getInstance();

		$nx = $this->x + $x;
		$ny = $this->y + $y;

		if ($db->query('SELECT * FROM map WHERE x="'.$nx.'" AND y="'.$ny.'"')->num_rows > 0){

			$this->x = $nx;
      $this->y = $ny;
      $this->turns_remaining = $this->turns_remaining - 1;
      $this->turns_played = $this->turns_played + 1;
			$this->save();
		}
  }



  public function save() {
    $query = '
		UPDATE players SET
						id=?, username=?, name=?, gender=?, race=?, x=?, y=?, hp=?, hp_max=?, xp=?, turns_played=?, turns_remaining=?, last_action=?, critical_chance=?, perk_multiplier=?, 
						base_armor=?, base_action_points=?, base_nosnost=?, base_obnova_zdravia=?, mod_sanca_den=?, mod_sanca_noc=?, mod_skillpointy_za_level=?, 
						atribut_sila=?, atribut_vydrz=?, atribut_inteligencia=?, atribut_rychlost=?, atribut_charizma=?, atribut_stastie=?, 
						zrucnost_chladne_zbrane=?, zrucnost_strelne_zbrane=?, zrucnost_cudzinecke_zbrane=?, zrucnost_utocna_magia=?, zrucnost_obranna_magia=?, 
						zrucnost_obchodovanie=?, zrucnost_kradnutie=?, zrucnost_gamblovanie=?, zrucnost_liecenie=?, zrucnost_vyroba_lektvarov=?, 
						volne_zrucnosti=?, volne_perky=?, rank=?, player_dialogue=?, register_state=?, email=?, gold=?, shop=?
		WHERE id = ?'; 
		
		$stmt = $this->sql->prepare($query);
		$stmt->bind_param('issssiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiisiiisi', $this->id, $this->username, $this->name, $this->gender, $this->race, $this->x, $this->y, $this->hp, $this->hp_max, $this->xp, 
						$this->turns_played, $this->turns_remaining, $this->last_action, $this->critical_chance, $this->perk_multiplier, 
						$this->base_armor, $this->base_action_points, $this->base_nosnost, $this->base_replenish_life, $this->mod_chance_day, $this->mod_chance_night, $this->mod_skillpoints_per_level, 
						$this->strength, $this->endurance, $this->intelligence, $this->speed, $this->charisma, $this->luck, 
						$this->melee, $this->ranged, $this->alien, $this->magic_attack, $this->magic_defense, 
						$this->merchant, $this->thief, $this->gambling, $this->healing, $this->alchemy, 
						$this->free_abilities, $this->free_perks, $this->rank, $this->dialogueNPC, $this->registerState, $this->email,$this->gold,$this->shop,
						$this->id);

		$stmt->execute();
		$stmt->store_result();
		$stmt->fetch();
		

  }
  
  
  
  public function exitDialogue() {
    $query = 'UPDATE players SET player_dialogue = "" WHERE id = ?';
    $stmt = $this->db->prepare($query);
    $stmt->bind_param('i',$this->id);
    $stmt->execute();
    $stmt->close();
    $this->dialogueNPC = '';
  }
  
	
	public function changeRace($newRace) {
		if ($this->registerState == 0) {
			$query = 'UPDATE players SET race =? WHERE id=?';
			$stmt = $this->db->prepare($query);
			//echo $this->db->error;
			$stmt->bind_param('si',$newRace, $this->id);
			$stmt->execute();
			$stmt->close();
			
		}
	}
	
	public function setRegisterState($newState) {
		$query = 'UPDATE players SET register_state=? WHERE id=?';
		$stmt = $this->db->prepare($query);
		//echo $this->db->error;
		$stmt->bind_param('ii',$newState, $this->id);
		$stmt->execute();
		$stmt->close();
	}
  
  public function finalizeRegistration() {

		
		
	}
	
  
  public function isInFight() {
    $query = 'SELECT COUNT(*) FROM fights WHERE fight_player =?';
    $stmt = $this->db->prepare($query);
    $stmt->bind_param('i',$this->id);
    $stmt->execute();
    $stmt->bind_result($count);
    $stmt->fetch();
    return ($count == 1);
  }
	
	
  public static function actualPlayer() {
    if (!isset($_SESSION['id'])) {
      return null;
    }
    
    if (self::$actualPlayer == null) {
      self::$actualPlayer = new Player();
    }


    return self::$actualPlayer;
  }
	
	
	public static function login($name, $password) {
    $query = 'SELECT id FROM players WHERE UPPER(username) = UPPER(?) AND password = MD5(?)';
    $stmt = MySQLCOnnection::getInstance()->prepare($query);
			
    $stmt->bind_param('ss', $name, $password);
			
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows != 1) {
      session_unset();
      return;
    }

    $stmt->bind_result($id);
    $stmt->fetch();

    $_SESSION['id'] = $id;
  }
	
	public static function newPlayer($nick, $characterName, $gender, $password, $email) {
    $query = 'INSERT INTO players SET username=?, name=?, gender=?, password=MD5(?), email=?';
    $stmt= MySQLConnection::getInstance()->prepare($query);
    $stmt->bind_param('sssss',$nick, $characterName, $gender, $password, $email);
    $stmt->execute();
    $stmt->store_result();
    $id = $stmt->insert_id;
    $stmt->close();
  }

  
	

}

?>
