<?php

	if (isset($_POST['createdialogue'])) {
		$npc = Npc::getNPC($_POST['npcname']);
		$npc->dialogue = 1;
		$npc->save();
	}


	$NPCs = Npc::getAllNPCs();


	echo '<form action="admin.php?page=dialogues" method="post">
	<div>
	<h2>New dialogue</h2>
	<select name="npcname">';

	foreach($NPCs as $npc) {
		if ($npc->dialogue == 0) {
			echo '<option value="'.$npc->name.'">'.$npc->name.' - '.$npc->title.'</option>';
		}
	}

	echo '
	</select>
	<input type="submit" name="createdialogue" value="Create dialogue" />
	</div>
	</form>';


	foreach($NPCs as $npc) {
		if ($npc->dialogue >0) {
			echo '<a href="admin.php?page=dialogues&amp;dialogue='.$npc->name.'">'.$npc->name.'</a><br/>';
		}
	}
	
	


?>