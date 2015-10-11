<?php




if (isset($_POST['savenpc'])) {
	$npc = Npc::getNpc($_POST['savenpc']);
	
	$npc->title = $_POST['npctitle'];
	
	$npc->placeId = $_POST['place'];
	$npc->x = $_POST['npcx'];
	$npc->y = $_POST['npcy'];
	
	$npc->hp = $_POST['maxhp'];
	$npc->strength = $_POST['strength'];
	$npc->defense = $_POST['defense'];
	
	$npc->save();
}


if (isset($_POST['newnpc'])) {
	$name = $_POST['npcname'];
	$posX = $_POST['npcx'];
	$posY = $_POST['npcy'];
	$title = $_POST['npctitle'];
	$dialogue = $_POST['npcdialogue'];
	
	
	
	$hp = 0;
	$strength = 0;
	$defense = 0;
	
	$npc = Npc::newNPC($name, 0, $posX, $posY, $title, $dialogue, $hp, $strength, $defense);
	
	
}

$NPCs = Npc::getAllNPCs();
$places = Place::getPlaces();

echo '
<div class="admin_table">
<div>
					<div>Name</div>
					<div>Title</div>
					<div>Place</div>
					<div>X</div>
					<div>Y</div>
					<div>Dialogue</div>

					<div>Max HP</div>
					<div>Strength</div>
					<div>Defense</div>

					<div>Save</div>
				</div>
';

foreach($NPCs as $NPC) {
	echo '<form action="admin.php?page=npc" method="post">
					<div>'.$NPC->name.'</div>
					<div><input type="text" name="npctitle" value="'.$NPC->title.'" /></div>
					<div>
						<select name="place">
							<option value="0" '.(($NPC->placeId == 0)?'selected="selected"':'').'>Nowhere</option>';
	foreach($places as $place) {
		echo '
							<option value="'.$place->id.'" '.(($NPC->placeId == $place->id)?'selected="selected"':'').'>'.$place->name.'</option>';
		
	}
						
						
	echo '
						</select>
					</div>
					<div><input type="text" name="npcx" value="'.$NPC->x.'" /></div>
					<div><input type="text" name="npcy" value="'.$NPC->y.'" /></div>
					<div><input type="text" name="npcdialogue" value="'.$NPC->dialogue.'" /></div>

					<div><input type="text" name="maxhp" value="'.$NPC->hp.'" /></div>
					<div><input type="text" name="strength" value="'.$NPC->strength.'" /></div>
					<div><input type="text" name="defense" value="'.$NPC->defense.'" /></div>

					<div><button type="submit" name="savenpc" value="'.$NPC->name.'">Save</button></div>
				</form>';
}

echo '

				<form action="admin.php?page=npc" method="post">
					<div><input type="text" name="npcname" /></div>
					<div><input type="text" name="npctitle" /></div>
					<div><input type="text" name="npcx" /></div>
					<div><input type="text" name="npcy" /></div>
					<div><input type="text" name="npcdialogue" /></div>
					<div><button type="submit" name="newnpc" value="newnpc">Create</button></div>
				</form>
';



?>