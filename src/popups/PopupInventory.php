<?php
		session_start();

require_once '../security.php';
require_once '../classes/class_external_script.php';

require_once '../classes/Inventory.php';


class InventoryPage
{
	
	private $player;
	private $inventory;
	private $sql;
	
	
	
	function Display() {
    
		echo '<div style="text-align:left">';
		if ($this->player->gender == 'female') {
			echo '<img src="'.URL.'images/inventory/play_inventory_figure_female.gif" alt="Inventár" />';
		} else {
			echo '<img src="'.URL.'images/inventory/play_inventory_figure_male.gif" alt="Inventár" />';
		}
		echo '</div>';
		
		$sel = '';
			if (isset($_GET['id']) && is_numeric($_GET['id'])) {
				$sel = '/item/'.$_GET['id'];
			}
	
	
			
			$item = UsersItem::getEquippedItem($this->player->id, 'helmet');
			if($item) $itemClass = $item->getItem();
			echo '<div style="top: 10px; left: 190px;" class="inventory_helmet"><a href="#" onclick="changeWin(\''.URL.'inventory/slot/helmet'.$sel.'\')" title="'.($item?$itemClass->name:'').'">';
      if ($item) echo '<img src="'.URL.'images/items/'.$itemClass->image.'">';
      else echo '<img src="'.URL.'images/items/empty_slot/helmet.gif">';
      echo '</a></div>';
			
			$item = UsersItem::getEquippedItem($this->player->id, 'amulet');
			if($item) $itemClass = $item->getItem();
			echo '<div style="top: 88px; left: 190px;" class="inventory_amulet"><a href="#" onclick="changeWin(\''.URL.'inventory/slot/amulet'.$sel.'\')" title="'.($item?$itemClass->name:'').'">';
			if ($item) echo '<img src="'.URL.'images/items/'.$itemClass->image.'">';
			else echo '<img src="'.URL.'images/items/empty_slot/amulet.gif">';
			echo '</a></div>';
			
			$item = UsersItem::getEquippedItem($this->player->id, 'armor');
			if($item) $itemClass = $item->getItem();
			echo '<div style="top: 131px; left: 190px;" class="inventory_armor"><a href="#" onclick="changeWin(\''.URL.'inventory/slot/armor'.$sel.'\')" title="'.($item?$itemClass->name:'').'">';
			if ($item) echo '<img src="'.URL.'images/items/'.$itemClass->image.'">';
			else echo '<img src="'.URL.'images/items/empty_slot/body_armor.gif">';
			echo '</a></div>';

			$item = UsersItem::getEquippedItem($this->player->id, 'weapon1');
			if($item) $itemClass = $item->getItem();
			echo '<div style="top: 254px; left: 190px;" class="inventory_weapon"><a href="#" onclick="changeWin(\''.URL.'inventory/slot/weapon1'.$sel.'\')" title="'.($item?$itemClass->name:'').'">';
			if ($item) echo '<img src="'.URL.'images/items/'.$itemClass->image.'">';
			else echo '<img src="'.URL.'images/items/empty_slot/weapon.gif">';
			echo '</a></div>';

			$item = UsersItem::getEquippedItem($this->player->id, 'weapon2');
			if($item) $itemClass = $item->getItem();
			echo '<div style="top: 332px; left: 190px;" class="inventory_weapon"><a href="#" onclick="changeWin(\''.URL.'inventory/slot/weapon2'.$sel.'\')" title="'.($item?$itemClass->name:'').'">';
			if ($item) echo '<img src="'.URL.'images/items/'.$itemClass->image.'">';
			else echo '<img src="'.URL.'images/items/empty_slot/weapon.gif">';
			echo '</a></div>';
			 


			// detaily vybraneho itemu
			echo '<div class="inventory_item_details">';
			echo $this->SelectedItem();
			echo '</div>';
			
			// zoznam itemov
			echo '<div class="inventory_item_list">'; 
      
      $this->ItemsList(); 
      
      echo '</div>';
			
			// udaje o hracovi
			echo '<div class="inventory_stats">'; echo $this->Stats(); echo '</div>';
			
	}

	function __construct() {

		$this->sql= MySQLConnection::getInstance();
		
		$this->player = Player::actualPlayer();
    $this->inventory = new Inventory($this->player->id);

		
		// vlozenie runy do predmetu
		if (isset($_POST['submit_rune']) && isset($_POST['rune_id']) && isset($_POST['item_id']) && is_numeric($_POST['rune_id']) && is_numeric($_POST['item_id'])) {
			if ((
				$item = $this->sql->query("SELECT id,sockets,runes,ap_cost,damage_min,damage_max,armor,min_str,min_lvl,weight,spotreba FROM items WHERE id='".$_POST['item_id']."' AND player_id='".$_SESSION['id']."' AND on_ground='0' AND sockets > 0;")->fetch_assoc()
				) && (
				$rune = $this->sql->query("SELECT id,class FROM items WHERE id='".$_POST['rune_id']."' AND player_id='".$_SESSION['id']."' AND on_ground='0' AND (class LIKE 'rune_%');")->fetch_assoc()
				)) {
				// upravenie stringu 'runes' a odobratie jedneho socketu
				$this->sql->query("UPDATE items SET sockets='".($item['sockets']-1)."', runes='".($item['runes'].strtoupper(substr($rune['class'],5,1)).strtolower(substr($rune['class'],6,1)))."' WHERE id='".$item['id']."';");
				
				// pridanie magickych vlastnosti predmetu
					
					// nastavenie classu runy
					$l = $this->sql->query("SELECT image FROM item_classes WHERE class='".$rune['class']."' LIMIT 1;");
					$rune_class = 'rune_'.substr($l['image'],strlen($l['image'])-5,1);
					if ($rune_class == 'rune_s') $rune_class = 'rune_spoluhlaska';
					
					/* A-RUNA */
					if ($rune_class == 'rune_a') {
						// damage
						$this->sql->query("UPDATE items,item_classes SET damage_min='".($item['damage_min'] + 5)."', damage_max='".($item['damage_max'] + 5)."' WHERE id='".$item['id']."' AND type LIKE 'weapon%' AND item_classes.class=items.class;",DATABASE);
						// armor
						$this->sql->query("UPDATE items,item_classes SET armor='".($item['armor'] + 5)."' WHERE id='".$item['id']."' AND ((type='body_armor') OR (type='helm')) AND item_classes.class=items.class;",DATABASE);
						// min_str
						$this->sql->query("UPDATE items SET min_str='".max(0,($item['min_str']-1))."' WHERE id='".$item['id']."';",DATABASE);
						// weight
						$this->sql->query("UPDATE items SET weight='".ceil($item['weight'] * 0.75)."' WHERE id='".$item['id']."';",DATABASE);
					
					/* E-RUNA */
					} elseif ($rune_class == 'rune_e') {
						// damage
						mysql_query("UPDATE items,item_classes SET damage_max='".($item['damage_max'] + 15)."' WHERE id='".$item['id']."' AND type LIKE 'weapon%' AND item_classes.class=items.class;",DATABASE);
						// min_lvl
						mysql_query("UPDATE items SET min_lvl='".max(0,($item['min_lvl']-1))."' WHERE id='".$item['id']."';",DATABASE);
						
					/* I-RUNA */
					} elseif ($rune_class == 'rune_i') {
						// armor
						mysql_query("UPDATE items,item_classes SET armor='".($item['armor'] + 20)."' WHERE id='".$item['id']."' AND ((type='body_armor') OR (type='helm')) AND item_classes.class=items.class;",DATABASE);
						// spotreba
						mysql_query("UPDATE items SET spotreba='".ceil($item['spotreba'] * 0.75)."' WHERE id='".$item['id']."';",DATABASE);
					
					/* O-RUNA */
					} elseif ($rune_class == 'rune_o') {
					
					/* U-RUNA */
					} elseif ($rune_class == 'rune_u') {
						
					/* SPOLUHLASKOVA-RUNA */
					} elseif ($rune_class == 'rune_spoluhlaska') {
						mysql_query("UPDATE items SET ap_cost='".max(0,($item['ap_cost']-1))."' WHERE id='".$item['id']."';",DATABASE);
					}
					
					// pridanie item_modifikatora
					mysql_query("INSERT INTO item_modifiers (`item_id`, `class`) VALUES ('".$item['id']."', '$rune_class');",DATABASE);
					
				// znicenie runy
				$this->sql->query('DELETE FROM items WHERE id=\''.$rune['id'].'\'');
				
				// ak pred pridanim bol 1 volny socket, skontrolujeme ci nevznikol runeword
				if ($item['sockets'] == 1) {
					$word = strtolower($item['runes'].substr($rune['class'],5,1).substr($rune['class'],6,1));
					if (mysql_num_rows(mysql_query("SELECT class FROM mod_classes WHERE class='runeword_$word';",DATABASE)) > 0) {
						$this->sql->query("INSERT INTO item_modifiers (`item_id`, `class`) VALUES ('".$item['id']."','runeword_$word')");
						echo '<script language="Javascript">
									<!--
									alert ("Vytvoril si runové slovo! Cítiš ako mágia okolo tvojho predmetu zosilnela.")
									//-->
									</script>';
					}
				}
	      			
				$this->player->RefreshItemsInventory();
				$this->player->RefreshItemsEquipped();
			}
			
		}
		
		// kliknutie na tlacidlo "polozit na zem"
		if (isset($_GET['action']) && ($_GET['action'] == 'drop_item') && isset($_GET['did']) && is_numeric($_GET['did'])) {
			$item = UsersItem::getUsersItem($this->player->id, $_GET['did']);
			
			if ($item != null) {
				$item->equipped = 0;
				$item->onGround = 1;
				
				if ($l = mysql_fetch_assoc(mysql_query("SELECT id FROM items WHERE id='".$_GET['did']."' AND player_id='".$_SESSION['id']."' AND equipped='0' AND on_ground='0';",DATABASE))) {
					mysql_query("UPDATE items SET on_ground='1', map_x='".$this->player->GetX()."', map_y='".$this->player->GetY()."' WHERE id='".$_GET['did']."';",DATABASE);
					$this->player->RefreshItemsInventory();
					$this->player->RefreshItemsEquipped();
				}
			}
		}
		
		// kliknutie na slot na inventari
		if (isset($_GET['slot']) && (($_GET['slot'] == 'helmet') || ($_GET['slot'] == 'amulet') || ($_GET['slot'] == 'armor') || ($_GET['slot'] == 'weapon1') || ($_GET['slot'] == 'weapon2'))) {
			$this->ClickSlot($_GET['slot']);
		}
		
		// zobrazenie
		$this->Display();

	}
	
	function RunesDialog($id) {
		$ret = '';
		if ($runes_line = mysql_fetch_assoc(mysql_query("SELECT runes,sockets FROM items WHERE id='$id';",DATABASE))) {
			if ($runes_line['runes'] != '') {
				$ret .= '<p>Zatiaľ máš v predmete runy: <span class="runes">'.$runes_line['runes'].'</span></p>';
			} else {
				$ret .= '<p>Zatiaľ nemáš v predmete žiadne runy.</p>';
			}
			$ret .= '<p>Voľných socketov máš: <span class="sockets">'.$runes_line['sockets'].'</span></p>';
			$ret .= '<form action="./inventory.php" method="post">';
			$ret .= '<p style="padding: 15px;">';
			$ret .= '<input type="hidden" name="item_id" class="hidden" value="'.$id.'">';
			$ret .= '<select name="rune_id">';
			$r = mysql_query("SELECT id,print_name FROM items,item_classes WHERE player_id='".$_SESSION['id']."' AND on_ground='0' AND item_classes.class=items.class AND type='rune';",DATABASE);
			while ($l = mysql_fetch_assoc($r)) $ret .= '<option value="'.$l['id'].'">'.$l['print_name'].'</option>';
			$ret .= '</select>';
			$ret .= '<input type="submit" name="submit_rune" value="Pridaj runu" style="margin-left: 10px;">';
			$ret .= '</p>';
			$ret .= '</form>';
		}
		return $ret;
	}
	
	function SelectedItem() {
		
		if (isset($_GET['action']) && isset($_GET['rid']) && ($_GET['action'] == 'runes') && is_numeric($_GET['rid'])) {
			$ret .= $this->RunesDialog($_GET['rid']);
			return $ret;
		}
		
		if (!isset($_GET['id'])) {
			return '';
		}
		
		$usersItem = UsersItem::getUsersItem($this->player->id, $_GET['id']);
		if ($usersItem == null) {
			return;
		}
		
		$item = $usersItem->getItem();
		
		//if ($line = $this->sql->query("SELECT print_name,image,description,type,damage_min,damage_max,attack_type,sockets,runes,ammo,weight,ap_cost,quantity,min_str,min_lvl,armor,spotreba FROM items,item_classes WHERE player_id='".."' AND items.class=item_classes.class;")->fetch_assoc()) {
		
		if (($item->type == 'weapon_alien') || ($item->type == 'weapon_melee') || ($item->type == 'weapon_ranged')) {
			$type = 'zbran';
		} elseif ($item->type == 'body_armor') {
			$type = 'armor';
		} elseif ($item->type == 'helm') {
			$type = 'helma';
		} elseif ($item->type == 'amulet') {
			$type = 'amulet';
		} elseif ($item->type == 'ammo') {
			$type = 'naboje';
		} elseif ($item->type == 'other') {
			$type = 'ine';
		} elseif ($item->type == 'rune') {
			$type = 'runa';
		} elseif ($item->type == 'potion') {
			$type = 'potion';
		}
			
			// obrazok
		echo '<img src="'.URL.'images/items/'.$item->image.'" alt="" class="'.$type.'">';
			
			// tlacidlo "polozit na zem"
		echo '<a href="#" onclick="changeWin(\''.URL.'inventory/item/'.$usersItem->id.'/drop" class="drop_item" title="Položiť na zem"><img src="../images/inventory/drop.gif" alt="položiť"></a>';
		
			// tlacidlo "runy"
		if ($usersItem->sockets > 0) {
			echo '<a href="./inventory.php?action=runes&amp;rid='.$usersItem->id.'" class="runes" title="Pridať runy"><img src="../images/inventory/runes.gif" alt="pridať runy"></a>';
		}
			
			// premenna $stats, ktora zobrazuje magicke vlastnosti
		$stats = '';
		$res2 = $this->sql->query("SELECT print_name,print_stats FROM item_modifiers,mod_classes WHERE item_id='".$_GET['id']."' AND item_modifiers.class=mod_classes.class AND position='predpona' LIMIT 1;");
		if ($line2 = $res2->fetch_assoc()) {
			$left = $line2['print_name'];
			$stats .= $line2['print_stats']."\n";
		} else {
			$left = '';
		}
		$res2 = $this->sql->query("SELECT print_name,print_stats FROM item_modifiers,mod_classes WHERE item_id='".$_GET['id']."' AND item_modifiers.class=mod_classes.class AND position='pripona' LIMIT 1;");
		if ($line2 = $res2->fetch_assoc()) {
			$right = $line2['print_name'];
			$stats .= $line2['print_stats'];
		} else {
			$right = '';
		}
			
		// premenna $rune_stats, ktora zobrazuje vlastnosti dane runami
		$rune_stats = '';
		$res2 = $this->sql->query("SELECT print_name,print_stats FROM item_modifiers,mod_classes WHERE item_id='".$_GET['id']."' AND item_modifiers.class=mod_classes.class AND position='runa';");
		while ($line2 = $res2->fetch_assoc()) {
			$rune_stats .= "<span style=\"color: gray; font-style: italic;\">".trim($line2['print_name']).":</span>\n".$line2['print_stats']."\n";
		}
			
			// premenna $runeword, ktora zobrazuje vlastnosti dane runovym slovom
			$res2 = $this->sql->query("SELECT print_name,print_stats FROM item_modifiers,mod_classes WHERE item_id='".$_GET['id']."' AND item_modifiers.class=mod_classes.class AND position='runeword';");
			if ($line2 = $res2->fetch_assoc()) {
				$runeword .= "<span style=\"color: orange; font-style: italic;\">".trim($line2['print_name']).":\n".$line2['print_stats']."</span>\n";
			} else {
        $runeword = '';
      }

			
			echo '<div class="description">';
			
			// nadpis / nazov predmetu
			if (($left != '') || ($right != '')) {
				echo '<p><strong><span class="magic">'.$left.' '.$line['print_name'].' '.$right.'</span></strong></p>';
			} else {
				echo '<p><strong>'.$left.' '.$item->name.' '.$right.'</strong></p>';
			}
			
			if (substr($item->type,0,6) == 'weapon') {
			// zbrane
				if (substr($item->type,7) == 'alien') {
					$zrucnost = 'cudzinecké zbrane';
				} elseif (substr($item->type,7) == 'melee') {
					$zrucnost = 'chladné zbrane';
				} else {
					$zrucnost = 'strelné zbrane';
				}
				if ($item->attackType == 'single') {
					$typ_utoku = 'jeden cieľ';
				} elseif ($item->attackType == 'splash2') {
					$typ_utoku = '1-2 ciele';
				} elseif ($item->attackType == 'splash3') {
					$typ_utoku = '1-3 ciele';
				} elseif ($item->attackType == 'splash4') {
					$typ_utoku = '1-4 ciele';
				}
				
				if ($item->ammo != 'no') {
					if ($item->ammo == '9mm') {
						$ammo = '(9mm náboje)';
					} elseif ($item->ammo == '223cal') {
						$ammo = '(.223 náboje)';
					} elseif ($item->ammo == 'benzin') {
						$ammo = '(benzín)';
					} elseif ($item->ammo == 'brokove_naboje') {
						$ammo = '(brokové náboje)';
					} elseif ($item->ammo == 'rakety') {
						$ammo = '(rakety)';
					} elseif ($item->ammo == 'sipky') {
						$ammo = '(šípky)';
					} elseif ($item->ammo == 'sipy') {
						$ammo = '(šípy)';
					}
				} else {
				$ammo = '';
				}
				
				// zrucnost pouzivana pri boji
				echo '<p>zručnosť: <span class="attack_type">'.$zrucnost.'</span></p>';
				
				// damage
				echo '<p>poškodenie: <span class="damage">'.$usersItem->minDamage.'-'.$usersItem->maxDamage.' '.$ammo.'</span></p>';
				
				// typ utoku a AP cost
				echo '<p>typ útoku: <span class="attack_type">'.$typ_utoku.', '.$usersItem->APcost.' AP</span></p>';
				
				// spotreba
				if ($item->ammo != 'no') echo '<p>spotreba: <span class="spotreba">'.$usersItem->ammoUsage.'</span></p>';
				
				
				echo '<p>požiadavky: <span style="color: '.(($usersItem->minStrength <= $this->player->totalStrength)?'green':'red').';">sila '.$usersItem->minStrength.'</span>, 
<span style="color: '.(($usersItem->minLevel <= $this->player->GetLevel())?'green':'red').';">level '.$usersItem->minLevel.'</span></p>';
			
			} elseif (($item->type == 'body_armor') || ($item->type == 'helm')) {
			// brnenia / helmy
				
				// armor
				echo '<p>obrana: <span class="obrana">'.$item->baseArmor.'</span></p>';
				
				// poziadavky na silu a level
				if ($item->minStrength <= $this->player->totalStrength) {
					$col_sila = 'green';
				} else {
					$col_sila = 'red';
				}
				if ($item->minLevel <= $this->player->level) {
					$col_level = 'green';
				} else {
					$col_level = 'red';
				}
				echo '<p>požiadavky: <span style="color: '.$col_sila.';">sila '.$item->minStrength.'</span>, <span style="color: '.$col_level.';">level '.$item->minLevel.'</span></p>';
				
			} elseif ($item->type == 'amulet') {
			// amulety
			
			} elseif ($item->type == 'ammo') {
			// naboje
				echo '<p>počet: <span class="quantity">'.$line['quantity'].'</span></p>';
			} elseif ($item->type == 'potion') {
			// lektvary
			
			} elseif ($item->type == 'rune') {
			// runy
				if (stripos($line['description'],'typu A') != false) {
					if ($l = mysql_fetch_assoc(mysql_query("SELECT print_stats FROM mod_classes WHERE class='rune_a';",DATABASE))) echo '<hr><p><span class="magic">'.nl2br($l['print_stats']).'</span></p><hr>';
				} elseif (stripos($line['description'],'typu E') != false) {
					if ($l = mysql_fetch_assoc(mysql_query("SELECT print_stats FROM mod_classes WHERE class='rune_e';",DATABASE))) echo '<hr><p><span class="magic">'.nl2br($l['print_stats']).'</span></p><hr>';
				} elseif (stripos($line['description'],'typu I') != false) {
					if ($l = mysql_fetch_assoc(mysql_query("SELECT print_stats FROM mod_classes WHERE class='rune_i';",DATABASE))) echo '<hr><p><span class="magic">'.nl2br($l['print_stats']).'</span></p><hr>';
				} elseif (stripos($line['description'],'typu O') != false) {
					if ($l = mysql_fetch_assoc(mysql_query("SELECT print_stats FROM mod_classes WHERE class='rune_o';",DATABASE))) echo '<hr><p><span class="magic">'.nl2br($l['print_stats']).'</span></p><hr>';
				} elseif (stripos($line['description'],'typu U') != false) {
					if ($l = mysql_fetch_assoc(mysql_query("SELECT print_stats FROM mod_classes WHERE class='rune_u';",DATABASE))) echo '<hr><p><span class="magic">'.nl2br($l['print_stats']).'</span></p><hr>';
				} elseif (stripos($line['description'],'spoluhl') != false) {
					if ($l = mysql_fetch_assoc(mysql_query("SELECT print_stats FROM mod_classes WHERE class='rune_spoluhlaska';",DATABASE))) echo '<hr><p><span class="magic">'.nl2br($l['print_stats']).'</span></p><hr>';
				}
			} else {
			// ostatne
			
			}
			
			// sockety
			if ($usersItem->sockets > 0) {
				echo '<p>voľné sockety: <span class="sockets">'.$usersItem->sockets.'</span></p>';
			}
			
			// runy
			if ($usersItem->runes != '') {
				echo '<p>runy: <span class="runes">'.$usersItem->runes.'</span></p>';
			}
			
			// hmotnost
			echo '<p>hmotnosť: <span class="weight">'.$usersItem->weight.'kg</span></p>';
			
			// magicke modifikatory
			if ($stats != '') {
				echo '<hr>';
				echo '<p><span class="magic">'.nl2br($stats).'</span></p>';
			}
			
			// runeword
			if ($runeword != '') {
				echo '<hr>';
				echo '<p><span class="runes">'.nl2br($runeword).'</span></p>';
			}
			
			// modifikatory dane runami
			if ($rune_stats != '') {
				echo '<hr>';
				echo '<p><span class="runes">'.nl2br($rune_stats).'</span></p>';
			}
			
			// popis
			echo  '
        <hr>
        <p>'.$item->description.'</p>
      </div>';
			
		}
	
	
	function ItemsList() {

    $this->inventory->RefreshItemsInventory();
    
    $items = UsersItem::getUsersItems($this->player->id);
		
		foreach ($items as $usersItem) {
			$item = $usersItem->getItem();
			if ($usersItem->equipped != '0') continue;
			
			
			
			//$leftModifier = ItemModifier::getModifier($usersItem->leftModifier);
			
			$res = MySQLConnection::getInstance()->query("SELECT print_name FROM item_modifiers,mod_classes WHERE item_id='".$usersItem->id."' AND item_modifiers.class=mod_classes.class AND position='predpona' LIMIT 1;");
			if ($line = $res->fetch_assoc()) {
				$left = $line['print_name'];
			} else {
				$left = '';
			}

			$res = MySQLConnection::getInstance()->query("SELECT print_name FROM item_modifiers,mod_classes WHERE item_id='".$usersItem->id."' AND item_modifiers.class=mod_classes.class AND position='pripona' LIMIT 1;");
			if ($line = $res->fetch_assoc()) {
				$right = $line['print_name'];
			} else {
				$right = '';
			}
			
			echo '
        <a href="#" onclick="changeWin(\''.URL.'inventory/item/'.$usersItem->id.'\')" class="item"><div class="item">
			   <img src="'.URL.'/images/items/'.$item->image.'" alt="">
         <div class="title">';
         
         
			if (($left != '') || ($right != '')) {
				echo '<span class="magic">'.$left.' '.$item->name.' '.$right.'</span>';
			} else {
				echo $left.' '.$item->name.' '.$right;
			}
			
			if ($usersItem->runes != '') $ret .= ' (<span class="runes">'.$usersItem->runes.'</span>)';
			if ($usersItem->quantity > 0) $ret .= ' (x'.$usersItem->quantity.')';
			if ($usersItem->upgrades > 0) $ret .= ' (mark '.($usersItem->upgrades+1).'.)';
			echo '
          </div>
			   </div>
        </a>';
			
		}
		
	}
	
	function ClickSlot($slot) {
		
		// odstranenie zo slotu
		/*if ($item = $this->inventory->getEquippedItem($slot)) {
		  $item->unEquip();
		  $this->inventory->RefreshItemsInventory();
		  $this->inventory->RefreshItemsEquipped();
		} elseif (isset($_GET['id']) && is_numeric($_GET['id'])) {
		    $this->inventory->putItemInSlot($_GET['id'], $slot);
		}*/
		
		if ($item = UsersItem::getEquippedItem($this->player->id, $slot)) {
		  $item->equipped = '0';
			$item->save();
		} elseif (isset($_GET['id']) && is_numeric($_GET['id'])) {
			$this->inventory->putItemInSlot($_GET['id'], $slot);
		}
		
	}
	
	function Stats() {
		
		$nosnost = $this->player->totalCapacity;
		$suma_vahy = $this->player->GetItemsWeight();
		if ($suma_vahy > $nosnost) $suma_vahy = '<span style="color: red;">'.$suma_vahy.'</span>';
		
		if ($zbran1 = $this->sql->query("SELECT damage_min,damage_max FROM items WHERE player_id='".$_SESSION['id']."' AND equipped='weapon1';")->fetch_Assoc()) {
		} else {
			$zbran1 = 0;
		}
		if ($zbran2 = $this->sql->query("SELECT damage_min,damage_max FROM items WHERE player_id='".$_SESSION['id']."' AND equipped='weapon2';")->fetch_assoc()) {
		} else {
			$zbran2 = 0;
		}
		
		$ret = '<ul>';
		$ret .= '<li>Hit-pointy: <span class="hitpointy">'.$this->player->hp.'/'.$this->player->maxHP.'</span></li>';
		$ret .= '<li>Action-pointy: <span class="actionpointy">'.$this->player->GetAPMax().'</span></li>';
		$ret .= '<li>Armor: <span class="obrana">'.$this->player->baseArmor.'('.$this->player->totalArmor.')</span></li>';
		if ($zbran1 != 0) $ret .= '<li>Zbraň 1: <span class="damage">'.$zbran1['damage_min'].'-'.$zbran1['damage_max'].'</span></li>';
		if ($zbran2 != 0) $ret .= '<li>Zbraň 2: <span class="damage">'.$zbran2['damage_min'].'-'.$zbran2['damage_max'].'</span></li>';
		$ret .= '<li>Nosnosť: <span class="weight">'.$suma_vahy.'/'.$nosnost.'</span></li>';
		$ret .= '</ul>';
		return $ret;
	}
	

}

	// vytvorenie objektu skriptu
	$page = new InventoryPage();

?>