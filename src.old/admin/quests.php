<?php

if (isset($_POST['newquest'])) {
	Quest::newQuest($_POST['name'], $_POST['title']);
	
}


$quests = Quest::getAllQuests();


echo '<div class="admin_table">
			<div>
				<div>Id</div>
				<div>Name</div>
				<div>Title</div>
				<div>Update</div>
			</div>';

foreach($quests as $quest) {
	echo '
			<div>
				<div>'.$quest->id.'</div>
				<div><a href="admin.php?page=quests&amp;quest='.$quest->name.'">'.$quest->name.'</a></div>
				<div>'.$quest->title.'</div>
				<div><button type="submit">Update</button></div>
			</div>';
	
}

echo '
	<form action="admin.php?page=quests" method="post">
		<div>&nbsp;</div>
		<div><input type="text" name="name" /></div>
		<div><input type="text" name="title" /></div>
		<div><button type="submit" name="newquest" value="newquest">Create quest</button></div>
	</form>


</div>';


if (!isset($_GET['quest'])) {
	exit();
}

echo '<hr/>';


$quest = Quest::getQuest($_GET['quest']);

if (isset($_POST['newtask'])) {
	echo 'new task';
	Task::newTask($quest->name, $_POST['title'], $_POST['description'], 0, '', '', '');
	
}










$tasks = $quest->getTasks();


echo '<h2>Quest: '.$quest->name.'</h2>';


echo '<div class="admin_table">
			<div>
					<div>Id</div>
					<div>Title</div>
					<div>Description</div>
					<div>Update</div>
				</div>
';

foreach ($tasks as $task) {
	echo '<div>
					<div>'.$task->id.'</div>
					<div><a href="admin.php?page=quests&amp;quest='.$_GET['quest'].'&amp;task='.$task->id.'">'.$task->title.'</a></div>
					<div>'.$task->description.'</div>
					<div></div>
				</div>';
}

echo '
				<form action="admin.php?page=quests&amp;quest='.$_GET['quest'].'" method="post">
					<div>&nbsp;</div>
					<div><input style="width:300px;" type="text" name="title" /></div>
					<div><input style="width:700px;" type="text" name="description" /></div>
					<div><button type="submit" name="newtask" value="newtask">Create task</button></div>
				</form>
			</div>';





if (!isset($_GET['task'])) {
	exit();
}


echo '<hr/>';

$task = Task::getTask($quest->name, $_GET['task']);



if (isset($_POST['savetask'])) {
	$task->title = $_POST['title'];
	$task->description = $_POST['description'];
	$task->type = $_POST['type'];
	$task->param1 = $_POST['param1'];
	$task->param2 = $_POST['param2'];
	$task->param3 = $_POST['param3'];

	$task->save();
}



echo '<h2>Task: '.$task->title.'</h2>';


echo '
<form action="admin.php?page=quests&amp;quest='.$_GET['quest'].'&amp;task='.$_GET['task'].'" method="post">
<table border="1">
<tr>
	<td>Title</td>
	<td><input type="text" name="title" value="'.$task->title.'" /></td>
</tr>

<tr>
	<td>Description</td>
	<td><textarea name="description">'.$task->description.'</textarea></td>
</tr>

<tr>
	<td>Type</td>
	<td>
		<select name="type">
			<option value="0" '.(($task->type==Task::TYPE_GENERAL)?'selected="selected"':'').'>GENERAL</option>
			<option value="'.Task::TYPE_GENERAL.'" '.(($task->type==Task::TYPE_GENERAL)?'selected="selected"':'').'>GENERAL</option>
			<option value="'.Task::TYPE_KILL.'" '.(($task->type==Task::TYPE_KILL)?'selected="selected"':'').'>Kill</option>
		</select>

	</td>
</tr>';



if ($task->type == Task::TYPE_KILL) {
	$NPCs = Npc::getAllNPCs();

	echo '
		<tr>
			<td>X</td>
			<td><input type="text" name="param1" value="'.$task->param1.'" /></td>

		</tr>

		<tr>
			<td>Y</td>
			<td><input type="text" name="param2" value="'.$task->param2.'" /></td>
		</tr>

		<tr>
			<td>NPC to kill</td>
			<td>
				<select name="param3">';
				
				foreach($NPCs as $NPC) {
				echo '<option value="'.$NPC->name.'" '.(($NPC->name == $task->param3)?'selected="selected"':'').'>'.$NPC->title.'</option>';
				}


echo '
				</select>
			</td>
		</tr>
';
}

else {
		echo '


		<tr>
			<td>Param 1</td>
			<td><input type="text" name="param1" value="'.$task->param1.'" /></td>
		</tr>

		<tr>
			<td>Param 2</td>
			<td><input type="text" name="param2" value="'.$task->param2.'" /></td>
		</tr>

		<tr>
			<td>Param 3</td>
			<td><input type="text" name="param3" value="'.$task->param3.'" /></td>
		</tr>';
}

echo '






</table>

<button type="submit" name="savetask" value="savetask">Save task</button>
</form>
';



?>