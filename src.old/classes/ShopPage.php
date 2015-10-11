<?php


class ShopPage extends PlayPage {
	private $actualTab;
	private $items;
	
	public function __construct() {
		parent::__construct();
		Styles::addStyle('shop');
		
		if (isset($_GET['tab']) && ($_GET['tab']=='sell')) {
			$this->actualTab = 'sell';
			
			
			if (isset($_GET['id'])) {
				$usersItem = UsersItem::getUsersItem($this->player->id, $_GET['id']);
				
				if ($usersItem != null) {
					$cost = $usersItem->cost * sellValue($this->player->totalMerchant);
					$this->player->gold = $this->player->gold + $cost;
					$this->player->save();
					$usersItem->delete();
				}
				
				
			}
			
			$this->items = UsersItem::getUsersItems($this->player->id);
			
			
		} else {
			$this->actualTab = 'buy';
			
			if (isset($_GET['id'])) {
				$shopItem = ShopItem::getShopItem($_GET['id']);
				
				if ($shopItem != null) {
					$cost = $shopItem->cost * buyValue($this->player->totalMerchant);
					
					if ($cost <= $this->player->gold) {
						$item = UsersItem::newUsersItem($this->player->id, 0, 0, $shopItem->class, $shopItem->sockets, $shopItem->runes, $shopItem->upgrades, $shopItem->minDamage, $shopItem->maxDamage, $shopItem->armor, $shopItem->APcost, $shopItem->minStrength, $shopItem->minLevel, $shopItem->quantity, $shopItem->spotreba, 0, 0, $shopItem->weight, $shopItem->leftModifier, $shopItem->rightModifier);
						$this->player->gold = $this->player->gold - $cost;
						$this->player->save();
					}
				}
				
			}

			
			
			$this->items = ShopItem::getShopItems($this->player->shop);
		}
		
	}
	
	public function displayContent() {
	}
	
	public function popup() {
		$npc = NPC::getNPC($this->player->shop);
		
		
		
		
		
		
		
		echo '
        <div id="popupbackground" style="display:block"></div>
        <div id="popupcontainer" style="display:block">
					<div id="shop" style="" class="rounded">

						<div style="float:left;" >
';
					
						
					if (is_file('images/creatures/'.$npc->name.'.png')) {
						echo  '<img src="'.URL.'images/creatures/'.$npc->name.'.png" /><br/>';
					}
					
					
					
					
					echo $npc->title.'

							<a href="'.URL.'shop/buy" class="changetab'.(($this->actualTab=='buy')?' changetabactive':'').'">Nákup</a>
							<a href="'.URL.'shop/sell" class="changetab'.(($this->actualTab=='sell')?' changetabactive':'').'">Predaj</a>
							<a href="'.URL.'shop/exit" class="changetab">Ukončiť</a>
						</div>
						<div id="buy">';


					foreach($this->items as $item) {
						$itemClass = ItemClass::getItemClass($item->class);
						//echo $itemClass->printName;
						
						if ($this->actualTab=='sell') {
							$cost = $item->cost * sellValue($this->player->totalMerchant);
						} else {
							$cost = $item->cost * buyValue($this->player->totalMerchant);
						}
						
						if ($item->leftModifier != '') {
							$leftModifier = ItemModifier::getModifier($item->leftModifier);
							if ($leftModifier == null) {
								$left = '';
							} else {
								$left = $leftModifier->name;
							}
						} else $left = '';
						
						
						if($item->rightModifier != '') {
							$rightModifier = ItemModifier::getModifier($item->rightModifier);
							if ($rightModifier == null) {
								$right = '';
							} else {
								$right = $rightModifier->name;
							}
						} else $right = '';
						
						
						echo '
							<a href="'.URL.'shop/'.$this->actualTab.'/'.$item->id.'" class="item">
							<div class="item">
								<img src="'.URL.'images/items/'.$itemClass->image.'" alt="">
								<div class="title">';
         
         
								if (($left != '') || ($right != '')) {
									echo '<span class="magic">'.$left.' '.$itemClass->printName.' '.$right.'</span>';
								} else {
									echo $left.' '.$itemClass->printName.' '.$right;
								}
								
								echo ' - cena: '.$cost.' zlatých';
			
								if ($item->runes != '') $ret .= ' (<span class="runes">'.$usersItem->runes.'</span>)';
								if ($item->quantity > 0) $ret .= ' (x'.$usersItem->quantity.')';
								if ($item->upgrades > 0) $ret .= ' (mark '.($usersItem->upgrades+1).'.)';
								echo '
								</div>
								</div>
							</a>';
			
					}
					
					echo '</div>
						<div class="gold">Máš '.$this->player->gold.' zlatých, kapacitu: '.$this->player->totalCapacity.'</div>

					</div>
	
					

				</div>';
  }
	
}



?>