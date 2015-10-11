<?php

require_once('Item.php');

class Inventory {
  private $playerId = 0;
  
  private $db = null;
  
	var $items_inventory = Array();	// id cisla veci, ktore ma hrac v inventari
	var $items_equipped = Array();	// pole objektov "ITEM", ktore ma hrac oblecene/drzi v rukach

  
  public function __construct($playerId) {
    $this->playerId = $playerId;
    $this->db = MySQLConnection::getInstance();
		

    
    
    $res = $this->db->query('SELECT id, equipped FROM items WHERE player_id=\''.$this->playerId.'\' AND on_ground=\'0\' ORDER BY id DESC;');
		// naplnenie poli $items_inventory a $items_equipped
		
		$items = UsersItem::getUsersItems($this->playerId);
		
		foreach($items as $item) {
			if($item->equipped == 0) {
				array_push($this->items_inventory, $item); 
			} else {
				$this->items_equipped[$item->equipped] = $item;
			}
		}
  }
  
  	// vrati pole id-ciek predmetov, ktore ma hrac v inventari (ale nie oblecene)
	function GetItemsInventory() {
	  //print_r($this->items_inventory);

		return $this->items_inventory;

	}
	
	
	
	public function addItem($class) {
		$item = Item::getItem($class);
		
		$query = 'INSERT INTO items SET player_id=?, on_ground=?, equipped=?, class=?, sockets=?, runes=?, upgrades=?, damage_min=?, damage_max=?, armor=?, ap_cost=?, min_str=?, min_lvl=?, quantity=?, spotreba=?, map_x=?, map_y=?, weight=?';
		$stmt = MySQLConnection::getInstance()->prepare($query);
		
		$stmt->bind_param();
		
		
		
	}
	
	
	
	
		// zisti, ci je obleceny predmet s modifikatorom triedy $class
	function IsEquipped($class) {
		$ret = false;
		$query = 'SELECT id FROM items,item_modifiers WHERE player_id=? AND on_ground=0 AND equipped != 0 AND item_id=id AND item_modifiers.class=?'; 
    $stmt = $this->db->prepare($query);
    $stmt->bind_param('is',$this->playerId, $class);
    $stmt->execute();
    $stmt->store_result();
    		
		if ($stmt->num_rows > 0) return true;
		return false;
	}
	
	// refreshne zoznam veci v inventari, ktore nema hrac oblecene (na spravne zobrazenie inventara)
	
  
  function RefreshItemsInventory() {
  


  
	  $query = 'SELECT id FROM items WHERE player_id=? AND on_ground="0" AND equipped="0" ORDER BY id DESC;';
    $stmt = $this->db->prepare($query);
    $stmt->bind_param('i',$this->playerId);
	  $stmt->execute();
	  $stmt->bind_result($id);

	  $this->items_inventory = Array();

          while ($stmt->fetch()) {
            array_push($this->items_inventory,$id);
          }
	}
	
	// vrati pole predmetov (ako objektov), ktore ma hrac na sebe
	function GetItemsEquipped() {
		return $this->items_equipped;
	}
	
	// refreshne zoznam veci v inventari, ktore ma hrac oblecene (na spravne zobrazenie inventara)
	function RefreshItemsEquipped() {
	  $query = 'SELECT id FROM items WHERE player_id=? AND on_ground=0 AND equipped != 0;';
	  $stmt = $this->db->prepare($query);
    $stmt->bind_param('i',$this->playerId);
	  $stmt->execute();
    $stmt->bind_result($id);

    $GLOBALS['modifiers'] = Array();
	  $this->items_equipped = Array();

    while ($stmt->fetch()) array_push($this->items_equipped, new ITEM($id));
	}
  
  public function getEquippedItem($position) {
		if (isset($this->items_equipped[$position])) {
			return $this->items_equipped[$position];
		}
		
		return null;
		
  }
  
  
  public function __get($name) {
    switch($name) {
      case 'helmet': 
      case 'amulet': 
      case 'armor': 
      case 'weapon1': 
      case 'weapon2': return $this->getEquippedItem($name);
      
      default: throw new Exception('Inventory has no member `'.$name.'`');
    }
  }


  public function putItemInSlot($itemId, $slot) {
		$player = Player::actualPlayer();
		
		if ($item = UsersItem::getUsersItem($this->playerId, $itemId)) {
			$itemClass = $item->getItemClass();
			$type = $itemClass->type;
			
			if (
						(($slot == 'helmet') && ($type == 'helm')) || 
						(($slot == 'amulet') && ($type == 'amulet')) || 
						(($slot == 'armor') && ($type == 'body_armor')) || 
						((substr($slot,0,6) == 'weapon') && (substr($type,0,6) == 'weapon')) 
				) {
		
				if (  ($item->minStrength <= $player->totalStrength) && ($item->minLevel <= $player->level)) {
					$item->equipped = $slot;
					$item->save();
				}
			}
		}
	}
		
		/*
    $query = 'SELECT type,min_str,min_lvl FROM items,item_classes WHERE id=? AND player_id=? AND items.class=item_classes.class;';
		$stmt = $this->db->prepare($query);
    
    $stmt->bind_param('ii', $_GET['id'], $this->playerId);
		$stmt->execute();
		$stmt->bind_result($type,$min_str,$min_lvl);
		$stmt->fetch();
		$stmt->close();

  	if ((($slot == 'helmet') && ($type == 'helm')) || 
				(($slot == 'amulet') && ($type == 'amulet')) || 
				(($slot == 'armor') && ($type == 'body_armor')) || 
				((substr($slot,0,6) == 'weapon') && (substr($type,0,6) == 'weapon'))
				) {
					// ak su splnene poziadavky na silu a level
					///*if (($min_str <= $this->player->strength) && ($min_lvl <= $this->player->GetLevel())) {
					$this->db->query("UPDATE items SET equipped='$slot' WHERE id='".$_GET['id']."'");
					$this->RefreshItemsInventory();
					$this->RefreshItemsEquipped();
					//}
				}*/
	//}


}


?>