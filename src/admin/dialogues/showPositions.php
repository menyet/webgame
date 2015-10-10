<?php


	echo '<hr/>';

	if (isset($_POST['createposition']) && (strlen($_POST['createposition']) > 0)) {
		DialoguePosition::newPosition($_GET['dialogue'], $_POST['positiontext']);
	}

	echo '<div class="admin_table">
	<div>
		<div>Id</div>
		<div>Text</div>
		<div>Jump to</div>
		<div>Update</div>
	</div>';


	if (isset($_POST['updateposition'])) {
		$position = DialoguePosition::getPosition($_GET['dialogue'], $_POST['updateposition']);
		
		$position->jumpTo = $_POST['position_jump_to'.$position->id];
		$position->text = $_POST['position_text'];
		$position->save();
		
	}



	$positions = DialoguePosition::getPositions($_GET['dialogue']);
	foreach($positions as $position) {
		echo '<form action="admin.php?page=dialogues&dialogue='.$_GET['dialogue'].'" method="post">
						<div><a href="admin.php?page=dialogues&amp;dialogue='.$_GET['dialogue'].'&amp;position='.$position->id.'">'.$position->id.'</a></div>
						<div><input type="text" name="position_text" style="width:1000px;" value="'.$position->text.'" /></div>
						<div>
							<select name="position_jump_to'.$position->id.'">
								
	';
								option(0, $position->jumpTo, "Exit dialogue");
								option($position->id, $position->jumpTo, "Nowhere");
								
							
								$positions2 = DialoguePosition::getPositions($_GET['dialogue']);
								foreach($positions2 as $position2) {
									if ($position2->id != $position->id) {
										option($position2->id, $position->jumpTo, $position2->id);
									}
								}
		echo '
							</select>
						</div>

						<div><button type="submit" name="updateposition" value="'.$position->id.'">Update position</button></div>
					</form>';
	}


	echo '
	<form action="admin.php?page=dialogues&amp;dialogue='.$_GET['dialogue'].'" method="post">
		<div>&nbsp;</div>
		<div>
			<input style="width:1000px;" type="text" name="positiontext" rows="8" cols="100"></textarea>
		</div>
		<div>
			<select name="position_jump_to_new">';
								$positions2 = DialoguePosition::getPositions($_GET['dialogue']);
								option(0, $position->jumpTo, "Select");
								foreach($positions2 as $position2) {
									option($position2->id, $position->jumpTo, $position->id);
								}
		echo '
							</select>
		
		</div>

		<div>
			<input type="submit" name="createposition" value="Create new position" />
		</div>
	</form>
	';

	echo '</div>';
	


?>