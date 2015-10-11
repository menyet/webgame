<?php

function option($value, $default, $title) {
	echo '
				<option value="'.$value.'" '.(($value==$default)?'selected="selected"':'').'>'.$title.'</option>';
}


function show() {
	
	
echo '<a href="admin.php?page=dialogues">Dialogues</a>';
if (isset($_GET['dialogue'])) {
	echo ' -&gt; <a href="admin.php?page=dialogues&amp;dialogue='.$_GET['dialogue'].'">'.$_GET['dialogue'].'</a>';
	
} else {
	include('dialogues/showDialogues.php');
	return;
}

if (isset($_GET['position'])) {
	$position = DialoguePosition::getPosition($_GET['dialogue'], $_GET['position']);
	echo ' -&gt; <a href="admin.php?page=dialogues&amp;dialogue='.$_GET['dialogue'].'&amp;position='.$_GET['position'].'">'.$position->text.'</a>';
} else {
	include('dialogues/showPositions.php');
	return;
} 


if (isset($_GET['response'])) {
	$response = DialogueResponse::getResponse($_GET['dialogue'], $_GET['position'], $_GET['response']);
	echo ' -&gt; <a href="admin.php?page=dialogues&amp;dialogue='.$_GET['dialogue'].'&amp;position='.$_GET['position'].'&amp;response='.$_GET['response'].'">'.$response->text.'</a>';

} else {
	include('dialogues/showResponses.php');
	return;
}


echo '<hr/>';


$response = DialogueResponse::getResponse($_GET['dialogue'], $_GET['position'], $_GET['response']);

echo '<h2>'.$response->text.'</h2>';




include('dialogues/showActions.php');
include('dialogues/showConditions.php');






}




show();





?>