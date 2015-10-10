<?php

require_once('../security.php');
require_once('../classes/class_external_script.php');
require_once('../classes/Quest.php');
require_once('../classes/UsersQuest.php');

class QuestWindow extends ExternalScript
{
	private $questMgr = null;
  private $player = null;
	
	
	private function listTasks($usersQuest) {
		$tasks = $usersQuest->quest->getTasks();
		foreach($tasks as $task) {
			if (($task->id <= $usersQuest->actualTask) || ($usersQuest->state == 1)) {
				echo '<h3>'.$task->title.'</h3>
					<p>'.$task->description.'</p>
				';
			}
		}
		
	}

	function Display() {
	
    if (isset($_GET['quest'])) {
      $questToShow = UsersQuest::getQuestOfPlayer($_GET['quest'], $this->player->id);
    }	else {
      $questToShow = null;
    }

	  $activeQuests = UsersQuest::getActiveQuests($_SESSION['id']);
	  $finishedQuests = UsersQuest::getFinishedQuests($_SESSION['id']);
	  $failedQuests = UsersQuest::getFailedQuests($_SESSION['id']);
  
    echo '<div id="questlist">';
  
	  foreach ($activeQuests as $quest) {
	    echo '<a class="pending" href="#" onclick="showWin(\''.URL.'popups/PopupQuests.php?quest='.$quest->questName.'\', \'Questy a úlohy\')">'.$quest->quest->title.'</a>';
	  }

	  foreach ($finishedQuests as $quest) {
	    echo '<a class="finished" href="#" onclick="showWin(\''.URL.'popups/PopupQuests.php?quest='.$quest->questName.'\', \'Questy a úlohy\')">'.$quest->quest->title.'</a>';
	  }
	  
	  foreach ($failedQuests as $quest) {
	    echo '<a class="failed" href="#" onclick="showWin(\''.URL.'popups/PopupQuests.php?quest='.$quest->questName.'\', \'Questy a úlohy\')">'.$quest->quest->title.'</a>';
	  }

	  
	  echo '
    </div>
    <div id="questinfo">';
      if ($questToShow) {
        $this->listTasks($questToShow);
      } elseif (count($activeQuests)>0) {
        $this->listTasks($activeQuests[0]);
      } elseif (count($finishedQuests)>0) {
        $this->listTasks($finishedQuests[0]);
      } else {
        echo 'Nemáš žiadne úlohy.';
      }
    echo '
    </div>
    ';
	
	}

	function __construct() {
		session_start();

		$this->player = Player::actualPlayer();


	}

}

	// vytvorenie objektu skriptu
	$page = new QuestWindow();
	$page->display();

?>
