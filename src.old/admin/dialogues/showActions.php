<?php


if (isset($_POST['newaction'])) {
	DialogueAction::newAction($_GET['dialogue'], $_GET['position'], $_GET['response'], $_POST['action_type'], '', '', '');
}

if (isset($_POST['updateaction'])) {
	$action = DialogueAction::getAction($_GET['dialogue'], $_GET['position'], $_GET['response'], $_POST['updateaction']);
	
	$action->type = $_POST['action_type'];
	$action->actionParam1 = $_POST['action_param1'];
	$action->actionParam2 = $_POST['action_param2'];
	$action->actionParam3 = $_POST['action_param3'];
	$action->save();
}

if (isset($_POST['deleteaction'])) {
	$action = DialogueAction::getAction($_GET['dialogue'], $_GET['position'], $_GET['response'], $_POST['deleteaction']);
	$action->delete();
}


$actions = DialogueAction::getActions($_GET['dialogue'], $_GET['position'], $_GET['response']);


echo '<hr/>';

echo '<h2>Actions</h2>';


echo '
	<div class="admin_table">
		<div>
			<div>Id</div>
			<div>Type</div>
			<div>Parameter 1</div>
			<div>Parameter 2</div>
			<div>Parameter 3</div>
			<div>Update</div>
			<div>Delete</div>
		</div>';


foreach($actions as $action) {
	
	
	echo '
		<form action="admin.php?page=dialogues&amp;dialogue='.$_GET['dialogue'].'&amp;position='.$_GET['position'].'&amp;response='.$_GET['response'].'" method="post">
			<div>'.$action->actionId.'</div>
			<div>
				<select name="action_type">';
				
				option(DialogueAction::TYPE_ADDQUEST, $action->actionType, 'Add quest');
				option(DialogueAction::TYPE_FAILQUEST, $action->actionType, 'Failed quest');
				option(DialogueAction::TYPE_FINISH_TASK, $action->actionType, 'Finish quest task');
				
				option(DialogueAction::TYPE_ADDITEM, $action->actionType, 'Add item');
				option(DialogueAction::TYPE_REMOVEITEM, $action->actionType, 'Remove item');
				
				option(DialogueAction::TYPE_EXIT_DIALOGUE, $action->actionType, 'Exit dialogue');
				option(DialogueAction::TYPE_ADD_XP, $action->actionType, 'Add experience');
				option(DialogueAction::TYPE_ADD_GOLD, $action->actionType, 'Add gold');
				option(DialogueAction::TYPE_ENTER_SHOP, $action->actionType, 'Enter shop');
echo '				
				</select>
			</div>
			<div>';
			
			if (
						($action->actionType == DialogueAction::TYPE_ADDQUEST) || 
						($action->actionType == DialogueAction::TYPE_FAILQUEST) ||
						($action->actionType == DialogueAction::TYPE_FINISH_TASK))
						{
				echo '
					<select name="action_param1">';
					$quests = Quest::getAllQuests();
					
					foreach($quests as $quest) {
						option($quest->name, $action->actionParam1, $quest->title);
					}
					
					
					
				echo '</select>';
			} elseif ($action->actionType == DialogueAction::TYPE_ADDITEM) {
				require_once('classes/Item.php');
				$items = Item::getItems();
				echo '
					<select name="action_param1">';
					
					
					foreach($items as $item) {
						option($item->class, $action->actionParam1, $item->name);
					}
					
					
					
				echo '</select>';
			
			} else {
				echo '<input type="text" name="action_param1" value="'.$action->actionParam1.'" />';
			}
			
			echo '</div>
			<div><input type="text" name="action_param2" value="'.$action->actionParam2.'" /></div>
			<div><input type="text" name="action_param3" value="'.$action->actionParam3.'" /></div>
			<div><button type="submit" name="updateaction" value="'.$action->actionId.'">Update</button></div>
			<div><button type="submit" name="deleteaction" value="'.$action->actionId.'">Delete</button></div>
		</form>';
}

echo '</div>';


echo '
<form action="admin.php?page=dialogues&amp;dialogue='.$_GET['dialogue'].'&amp;position='.$_GET['position'].'&amp;response='.$_GET['response'].'" method="post">
	<select name="action_type">';
	option(DialogueAction::TYPE_ADDQUEST, 0, 'Add quest');
	option(DialogueAction::TYPE_FAILQUEST, 0, 'Failed quest');
	option(DialogueAction::TYPE_FINISH_TASK, 0, 'Finish quest task');

	option(DialogueAction::TYPE_ADDITEM, 0, 'Add item');
	option(DialogueAction::TYPE_REMOVEITEM, 0, 'Remove item');
	
	option(DialogueAction::TYPE_EXIT_DIALOGUE, 0, 'Exit dialogue');
	option(DialogueAction::TYPE_ADD_XP, 0, 'Add experience');
	option(DialogueAction::TYPE_ADD_GOLD, 0, 'Add gold');
	option(DialogueAction::TYPE_ENTER_SHOP, 0, 'Enter shop');
echo '</select>
	<button type="submit" name="newaction" value="newaction">Create action</button>
</form>
';













?>