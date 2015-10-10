<?php

require_once('classes/Item.php');

$items = Item::getItems();

echo '
<div class="admin_table">
<div>
	<div>Class</div>
	<div>Type</div>
	<div>Attack type</div>
	<div>Name</div>
	<div>Image</div>
	<div>Description</div>
	<div>Rarity</div>
	<div>Ammo</div>
	<div>Weight</div>
	<div>AP cost</div>
	<div>Min damage</div>
	<div>Max damage</div>
	<div>Armor</div>
	<div>Min strength</div>
	<div>Min level</div>
	<div>Spotreba</div>
</div>
';

foreach($items as $item) {
echo '
<div>
	<div>'.$item->class.'</div>
	<div>'.$item->type.'</div>
	<div>'.$item->attackType.'</div>
	<div>'.$item->name.'</div>
	<div>image</div>
	<div>'.$item->description.'</div>
	<div>'.$item->rarity.'</div>
	<div>'.$item->ammo.'</div>
	<div>'.$item->baseWeight.'</div>
	<div>'.$item->APcost.'</div>
	<div>'.$item->minDamage.'</div>
	<div>'.$item->maxDamage.'</div>
	<div>'.$item->baseArmor.'</div>
	<div>'.$item->minStrength.'</div>
	<div>'.$item->minLevel.'</div>
	<div>0</div>
</div>
';

	
}



echo '
</div>';


?>