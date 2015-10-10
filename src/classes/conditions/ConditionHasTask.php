<?php


class ConditionHasTask extends DialogueResponseCondition {
	public function check() {
		$player = Player::actualPlayer();
		$quest = UsersQuest::getUsersQuest($player->id, $this->param1);
		if ($quest == null) {
			
			return false;
		}
		
		/*print_r($this);
		print_r($quest);		
		echo $quest->actualTask.' == '.$this->param1;*/
		
		if ($quest->actualTask == $this->param2) {
			return true;
		}
		
		
		
		return false;
	}
	
}





?>