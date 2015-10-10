<?php
/*

1 - v tabulke 'items' ma svoj jeden unikatny zaznam, ktory obsahuje jeho zakladne statistiky (meno, vaha, damage, armor, AP_cost atd...)
2 - v dalsej tabulke 'item_modifiers' sa nachadza pre dany predmet niekolko modifikatorov (obdoba predppon a pripon z Diabla 2), ktore odkazuju na jednotlive triedy podifikatorov z tabulky mod_classes
3 - tabulka mod_classes obsahuje pre kazdu priponu, alebo predponu sadu modifikacnych premennych, ktore ked nie su nulove, tak sa
    prenasaju do pola $GLOBALS['modifiers'], ktore potom citaju rozne ostatne funkcie (napr. player->GetMaxHP)
4 - tabulka item_classes sa pouziva pri vytvoreni noveho itemu do hry alebo pri jeho zobrazovani v inventari - popisuje item classy (obsahuje napriklad cestu k obrazku, alebo rozsah damage, aky moze dany item class mat)

Itemy ovplyvnuju hru dvoma sposobmi:
1 - vzdy, ked sa vytvori novy objekt ITEM, tak sa hned zavola funkcia Wear, ktora nejakym sposobom ovplyvni stav hraca - robi to tak, ze prida nejake hodnoty do pola $GLOBALS['modifiers'], 
     a tie sa potom prejavia pri volani metod triedy PLAYER (napr $player->GetMaxHP berie udaj z databazy a prirata k nemu hodnotu z $GLOBALS['modifiers']['hp_max'])
2 - druhym sposobom ovplyvnenia hry je pouzitie predmetu volanim funkcie UseOn() (napriklad v boji sa vola UseOn($target) zbrane, kde $target je nepriatel ) 

*/
class Item
{
	private $class;
	private $type;
	private $attack_type;
	private $print_name;
	private $image;
	private $description;
	private $rarity;
	private $ammo;
	private $base_weight;
	private $base_ap_cost;
	private $base_dmg_min;
	private $base_dmg_max;
	private $base_armor;
	private $base_min_str;
	private $base_min_lvl;
	private $base_spotreba;
	
	private function __construct($class, $type, $attack_type, $print_name, $image, $description, $rarity, $ammo, $base_weight, $base_ap_cost, $base_dmg_min, $base_dmg_max, $base_armor, $base_min_str, $base_min_lvl, $base_spotreba) {
		$this->class = $class;
		$this->type = $type;
		$this->attack_type = $attack_type;
		$this->print_name = $print_name;
		$this->image = $image;
		$this->description = $description;
		$this->rarity = $rarity;
		$this->ammo = $ammo;
		$this->base_weight = $base_weight;
		$this->base_ap_cost = $base_ap_cost;
		$this->base_dmg_min = $base_dmg_min;
		$this->base_dmg_max = $base_dmg_max;
		$this->base_armor = $base_armor;
		$this->base_min_str = $base_min_str;
		$this->base_min_lvl = $base_min_lvl;
		$this->base_spotreba = $base_spotreba;
	}
	
  
  public function __get($name) {

    switch($name) {
      case 'class': return $this->class;
			
			case 'type': return $this->type;
			
			case 'attackType': return $this->attack_type;
			case 'name': return $this->print_name;
			case 'image': return $this->image;
			case 'description': return $this->description;
			case 'rarity': return $this->rarity;
			case 'ammo': return $this->ammo;
			case 'baseWeight': return $this->base_weight;
			case 'APcost': return $this->base_ap_cost;
			case 'minDamage': return $this->base_dmg_min;
			case 'maxDamage': return $this->base_dmg_max;
			case 'baseArmor': return $this->base_armor;
			case 'minStrength': return $this->base_min_str;
			case 'minLevel': return $this->base_min_lvl;
			case '': return $this->base_spotreba;
      default: throw new Exception('Property `'.$name.'` does not exist in class_item');
    }
  }

  /*function __construct($item_id){
    $this->item_id = $item_id;
    $this->Wear();
  }*/
  
  private function populate() {
    
  }
	
	// vola sa hned pri vytvoreni predmetu objektom Player
	function Wear(){
		if ($this->equipped) {
			$res = mysql_query("SELECT hp_max,ap_max,base_armor,gender,critical_chance,nosnost,obnova_zdravia,mod_sanca_den,mod_sanca_noc FROM item_modifiers,mod_classes WHERE item_id='".$this->id."' AND item_modifiers.class=mod_classes.class;",DATABASE);
			// vyberie vsetky modifikacne vlastnosti z tabulky mod_classes a tam, kde su nenulove hodnoty, priradi ich do pola $GLOBALS['modifiers']
			while ($line = mysql_fetch_assoc($res)) {
				foreach ($line as $key => $value) {
					if (($value != '') && ($value != '0') && ($value != 'no change')) {
						if (is_numeric($GLOBALS['modifiers'][$key])) {
							$GLOBALS['modifiers'][$key] += $value;
						} else {
							$GLOBALS['modifiers'][$key] = $value;
						}
					}
				}
			}
		}
	}

  public function unEquip() {
      $stmt = $this->db->prepare('UPDATE items SET equipped=0 WHERE id=?');
      $id = $this->id;
      $stmt->bind_param('i',$id);
      $stmt->execute();      
  }
	
	// vola sa napr. pri zbraniach v boji -> target je cielovy objekt
	function UseOn($target){
		
	}
	
	// vymazanie predmetu z tabuliek databazy
	function delete(){
		mysql_query('DELETE FROM item_modifiers WHERE item_id=\''.$this->id.'\'');
		mysql_query('DELETE FROM items WHERE id=\''.$this->id.'\'');
	}
	
	// pridanie runy
	function AddRune($str){
		$line = mysql_fetch_assoc(mysql_query("SELECT runes,sockets FROM items WHERE id='".$this->id."';",DATABASE));
		if ($line['sockets'] > 0) {
			mysql_query("UPDATE `items` SET `runes` = '".$line['runes'].$str."', sockets='".($line['sockets']-1)."' WHERE `player_id` ='".$this->id."';");
		}
	}
	
	
	// typ predmetu ( weapon_melee / weapon_ranged / weapon_alien / body_armor / helm / amulet /  ammo / other / potion / rune)
	function GetType(){
		$line = mysql_fetch_assoc(mysql_query("SELECT type FROM item_classes,items WHERE items.id='".$this->id."' AND items.class=item_classes.class;",DATABASE));
		return $line['type'];
	}
	
	// nazov classu predmetu
	function GetClass(){
		$res = mysql_query('SELECT class FROM items WHERE id=\''.$this->id.'\'',DATABASE);
		$line = mysql_fetch_assoc($res); 
		return $line['class'];
	}
	
	// nazov predmetu
	function GetName(){
		$res = mysql_query('SELECT print_name FROM item_classes,items WHERE id=\''.$this->id.'\' AND item_classes.class=items.class',DATABASE);
		$line = mysql_fetch_assoc($res);
		$middle = $line['print_name'];
		
		$res = mysql_query("SELECT mod_print_name FROM item_modifiers WHERE item_id='".$this->id."' AND position='predpona' LIMIT 1;",DATABASE);
		if ($line = mysql_fetch_assoc($res)) $left = $line['mod_print_name'];
		$res = mysql_query("SELECT mod_print_name FROM item_modifiers WHERE item_id='".$this->id."' AND position='pripona' LIMIT 1;",DATABASE);
		if ($line = mysql_fetch_assoc($res)) $right = $line['mod_print_name'];
		
		return $left.' '.$middle.' '.$right;
	}
	
	// runy, ktore sa v predmete nachadzaju
	function GetRunes(){
		$line = mysql_fetch_assoc(mysql_query("SELECT runes FROM items WHERE id='".$this->id."';",DATABASE));
		return $line['runes'];
	}
	function SetRunes($str){
		mysql_query("UPDATE `items` SET `runes` = '$str' WHERE `player_id` ='".$this->id."';");
	}
	
	// pocet volnych socketov na runy
	function GetSockets(){
		$line = mysql_fetch_assoc(mysql_query("SELECT sockets FROM items WHERE id='".$this->id."';",DATABASE));
		return $line['sockets'];
	}
	function SetSockets($int){
		mysql_query("UPDATE `items` SET `sockets` = '$int' WHERE `player_id` ='".$this->id."';");
	}

	// cislo upgradu predmetu
	function GetUpgrades(){
		$line = mysql_fetch_assoc(mysql_query("SELECT upgrades FROM items WHERE id='".$this->id."';",DATABASE));
		return $line['upgrades'];
	}
	function SetUpgrades(){
		mysql_query("UPDATE `items` SET `upgrades` = '$int' WHERE `player_id` ='".$this->id."';");
	}
	
	// max-damage predmetu
	function GetDamageMAX(){
		$line = mysql_fetch_assoc(mysql_query("SELECT damage_max FROM items WHERE id='".$this->id."';",DATABASE));
		return $line['damage_max'];
	}
	function SetDamageMAX($int){
		mysql_query("UPDATE `items` SET `damage_max` = '$int' WHERE `player_id` ='".$this->id."';");
	}
	
	// min-damage predmetu
	function GetDamageMIN(){
		$line = mysql_fetch_assoc(mysql_query("SELECT damage_min FROM items WHERE id='".$this->id."';",DATABASE));
		return $line['damage_min'];
	}
	function SetDamageMIN($int){
		mysql_query("UPDATE `items` SET `damage_min` = '$int' WHERE `player_id` ='".$this->id."';");
	}
	
	// armor predmetu
	/*function GetArmor(){
		$line = mysql_fetch_assoc(mysql_query("SELECT armor FROM items WHERE id='".$this->id."';",DATABASE));
		return $line['armor'];
	}*/
	function SetArmor($int){
		mysql_query("UPDATE `items` SET `armor` = '$int' WHERE `player_id` ='".$this->id."';");
	}
	
	// pocet potrebnych Action Pointov
	function GetAPCost(){
		$line = mysql_fetch_assoc(mysql_query("SELECT ap_cost FROM items WHERE id='".$this->id."';",DATABASE));
		return $line['ap_cost'];
	}
	function SetAPCost($int){
		mysql_query("UPDATE `items` SET `ap_cost` = '$int' WHERE `player_id` ='".$this->id."';");
	}
	
	// minimalna sila na predmet
	function GetMinSTR(){
		$line = mysql_fetch_assoc(mysql_query("SELECT min_str FROM items WHERE id='".$this->id."';",DATABASE));
		return $line['min_str'];
	}
	
	// minimalny level na predmet
	function GetMinLVL(){
		$line = mysql_fetch_assoc(mysql_query("SELECT min_lvl FROM items WHERE id='".$this->id."';",DATABASE));
		return $line['min_lvl'];
	}
	
	// vaha predmetu
	function GetWeight(){
		$line = mysql_fetch_assoc(mysql_query("SELECT weight FROM items WHERE id='".$this->id."';",DATABASE));
		return $line['weight'];
	}
	
	// urci, ci je predmet obleceny (1 ak ano, 0 ak nie)
	/*function GetEquipped() {
		$line = mysql_fetch_assoc(mysql_query("SELECT equipped FROM items WHERE id='".$this->id."';",DATABASE));
		if ($line['equipped'] == '0') {
			return '0';
		} else {
			return '1';
		}
	}*/
	
	// pocet - napr. pri nabojoch - inak 0
	function GetQuantity(){
		$line = mysql_fetch_assoc(mysql_query("SELECT quantity FROM items WHERE id='".$this->id."';",DATABASE));
		return $line['quantity'];
	}
	function SetQuantity($int){
		mysql_query("UPDATE `items` SET `quantity` = '$int' WHERE `player_id` ='".$this->id."';");
	}
	
	function GetSpotreba() {
		$l = mysql_fetch_assoc(mysql_query("SELECT spotreba FROM items WHERE id='".$this->id."';",DATABASE));
		return $l['spotreba'];
	}
	
	
	public static function getItem($class) {
		    $query = '
		    SELECT class, type, attack_type, print_name, image, description, rarity, ammo, base_weight, base_ap_cost, base_dmg_min, base_dmg_max, base_armor, base_min_str, base_min_lvl, base_spotreba
		    FROM item_classes WHERE class=?';

		$db = MySQLConnection::getInstance();
		$stmt = $db->prepare($query);
		$stmt->bind_param('s',$class);
		$stmt->execute();
    $stmt->store_result();
		
		$items = array();
		
		$stmt->bind_result($class, $type, $attack_type, $print_name, $image, $description, $rarity, $ammo, $base_weight, $base_ap_cost, $base_dmg_min, $base_dmg_max, $base_armor, $base_min_str, $base_min_lvl, $base_spotreba);
		
		$stmt->fetch();
		$item = new self($class, $type, $attack_type, $print_name, $image, $description, $rarity, $ammo, $base_weight, $base_ap_cost, $base_dmg_min, $base_dmg_max, $base_armor, $base_min_str, $base_min_lvl, $base_spotreba);
		return $item;

	}
	
	
	public static function getItems() {
		    $query = '
		    SELECT class, type, attack_type, print_name, image, description, rarity, ammo, base_weight, base_ap_cost, base_dmg_min, base_dmg_max, base_armor, base_min_str, base_min_lvl, base_spotreba
		    FROM item_classes';

		$db = MySQLConnection::getInstance();
		$stmt = $db->prepare($query);
		$stmt->execute();
    $stmt->store_result();
		
		$items = array();
		
		$stmt->bind_result($class, $type, $attack_type, $print_name, $image, $description, $rarity, $ammo, $base_weight, $base_ap_cost, $base_dmg_min, $base_dmg_max, $base_armor, $base_min_str, $base_min_lvl, $base_spotreba);
		
		while($stmt->fetch()) {
			$items[] = new self($class, $type, $attack_type, $print_name, $image, $description, $rarity, $ammo, $base_weight, $base_ap_cost, $base_dmg_min, $base_dmg_max, $base_armor, $base_min_str, $base_min_lvl, $base_spotreba);
		}
		
		return $items;

	}
	
}

?>