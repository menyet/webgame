<?php

	echo '<hr/>
	<h2>Reponses</h2>
	';



	$position = DialoguePosition::getPosition($_GET['dialogue'], $_GET['position']);

	if (isset($_POST['createresponse']) && (strlen($_POST['responsetext']) > 0)) {
		DialogueResponse::newResponse($_GET['dialogue'], $_GET['position'], $_POST['responsetext']);
	}


	if (isset($_POST['updateresponse'])) {
		$response = DialogueResponse::getResponse($_GET['dialogue'], $_GET['position'], $_POST['updateresponse']);
		$response->text = $_POST['responsetext'];
		$response->jumpTo = $_POST['jump_to'.$response->id];
		$response->save();
	}


	$responses = $position->getResponses();
	$positions = DialoguePosition::getPositions($_GET['dialogue']);

	echo '<div class="admin_table">
				<div>
					<div>Id</div>
					<div>Text</div>
					<div>Jump to</div>
					<div>Save</div>
				</div>
	';

	foreach($responses as $response) {
		echo '<form action="admin.php?page=dialogues&amp;dialogue='.$_GET['dialogue'].'&amp;position='.$_GET['position'].'" method="post">
						<div><a href="admin.php?page=dialogues&amp;dialogue='.$_GET['dialogue'].'&amp;position='.$_GET['position'].'&amp;response='.$response->id.'">'.$response->id.'</a></div>
						<div><input type="text" style="width:1000px;" name="responsetext" value="'.htmlspecialchars($response->text).'" /></div>
						<div>
							<select name="jump_to'.$response->id.'">';
								foreach($positions as $position) {
									option($position->id, $response->jumpTo, substr($position->text,0,50));
								}
		echo '
							</select>
						</div>
						<div><button type="submit" name="updateresponse" value="'.$response->id.'">Update</button></div>
					</form>';
	}

	echo '


	<form action="admin.php?page=dialogues&amp;dialogue='.$_GET['dialogue'].'&amp;position='.$_GET['position'].'" method="post">
		<div>&nbsp;</div>
		<div>
			<input type="text" style="width:1000px;" name="responsetext" />
		</div>
		<div>
			<select name="jump_to">';
				foreach($positions as $position) {
					option($position->id, 0, $position->id);
				}
		echo '
							</select>
						</div>
		<div>
			<input type="submit" name="createresponse" value="Create response" />
		</div>
	</form>


	</div>

	';


?>