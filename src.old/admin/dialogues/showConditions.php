<?php


if (isset($_POST['newcondition'])) { 
	DialogueResponseCondition::newCondition($_GET['dialogue'], $_GET['position'], $_GET['response'], $_POST['condition_type'], '', '', '');
}

if (isset($_POST['updatecondition'])) {
	$condition = DialogueResponseCondition::getCondition($_GET['dialogue'], $_GET['position'], $_GET['response'], $_POST['updatecondition']);
	
	$condition->type = $_POST['condition_type'];
	$condition->conditionParam1 = $_POST['condition_param1'];
	$condition->conditionParam2 = $_POST['condition_param2'];
	$condition->conditionParam3 = $_POST['condition_param3'];
	$condition->save();
}

$conditions = DialogueResponseCondition::getConditions($_GET['dialogue'], $_GET['position'], $_GET['response']);


echo '<hr/>';

echo '<h2>Conditions</h2>';


echo '
	<div class="admin_table">
		<div>
			<div>Id</div>
			<div>Type</div>
			<div>Parameter 1</div>
			<div>Parameter 2</div>
			<div>Parameter 3</div>
			<div>Update</div>
		</div>';


foreach($conditions as $condition) {
	
	
	echo '
		<form condition="admin.php?page=dialogues&amp;dialogue='.$_GET['dialogue'].'&amp;position='.$_GET['position'].'&amp;response='.$_GET['response'].'" method="post">
			<div>'.$condition->conditionId.'</div>
			<div>
				<select name="condition_type">';
				
	option(DialogueResponseCondition::TYPE_HAS_QUEST, $condition->conditionType, 'Has quest');
	option(DialogueResponseCondition::TYPE_HAS_TASK, $condition->conditionType, 'Has actual task');
	option(DialogueResponseCondition::TYPE_HAS_FINISHED_TASK, $condition->conditionType, 'Has finished task');

	option(DialogueResponseCondition::TYPE_MINIMAL_ATTRIBUTE, $condition->type, 'Has minimal atribute');
	option(DialogueResponseCondition::TYPE_MINIMAL_LEVEL, $condition->type, 'Has minimal level');
	
	option(DialogueResponseCondition::TYPE_HAS_RACE, $condition->type, 'Has race');
echo '				
				</select>
			</div>
			<div>';
			
			if (
						($condition->conditionType == DialogueResponseCondition::TYPE_HAS_QUEST) || 
						($condition->conditionType == DialogueResponseCondition::TYPE_HAS_TASK) ||
						($condition->conditionType == DialogueResponseCondition::TYPE_HAS_FINISHED_TASK))
						{
				echo '
				<select name="condition_param1">';
					$quests = Quest::getAllQuests();
					option(0, $condition->conditionParam1, 'Select quest');
					foreach($quests as $quest) {
						option($quest->name, $condition->conditionParam1, $quest->title);
					}
					
					
					
				echo '
				</select>';
			}
			
			echo '
			</div>
			<div>';
			
			
						if (
						($condition->conditionType == DialogueResponseCondition::TYPE_HAS_TASK) ||
						($condition->conditionType == DialogueResponseCondition::TYPE_HAS_FINISHED_TASK))
						{
				echo '
				<select name="condition_param2">';
					$tasks = Task::getTasks($condition->param1);
					
					
					option(0, $condition->conditionParam2, 'Select task');
					
					foreach($tasks as $task) {
						option($task->id, $condition->conditionParam2, $task->title);
					}
					
					
					
				echo '
				</select>';
			} else {
				echo '
				<input type="text" name="condition_param2" value="'.$condition->conditionParam2.'" />';
			}
			
			echo '
			</div>
			<div><input type="text" name="condition_param3" value="'.$condition->conditionParam3.'" /></div>
			<div><button type="submit" name="updatecondition" value="'.$condition->conditionId.'">Update</button></div>
		</form>';
}

echo '</div>';


echo '
<form condition="admin.php?page=dialogues&amp;dialogue='.$_GET['dialogue'].'&amp;position='.$_GET['position'].'&amp;response='.$_GET['response'].'" method="post">
	<select name="condition_type">';

	option(DialogueResponseCondition::TYPE_HAS_QUEST, 0, 'Has quest');
	option(DialogueResponseCondition::TYPE_HAS_TASK, 0, 'Has actual task');
	option(DialogueResponseCondition::TYPE_HAS_FINISHED_TASK, 0, 'Has finished task');

	option(DialogueResponseCondition::TYPE_MINIMAL_ATTRIBUTE, 0, 'Has minimal atribute');
	option(DialogueResponseCondition::TYPE_MINIMAL_LEVEL, 0, 'Has minimal level');
	
	option(DialogueResponseCondition::TYPE_HAS_RACE, 0, 'Has race');
echo '</select>
	<button type="submit" name="newcondition" value="newcondition">Add condition</button>
</form>

';













?>