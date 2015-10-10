<?php

require_once ('class_logged_page.php');
require_once('functions/functions_display_play.php');
require_once('functions/functions_math_play.php');

abstract class PlayPage extends LOGGED_PAGE
{
  protected $player;
		
	public abstract function DisplayContent();
	
  public function DisplayBody() {
			

		$player = Player::actualPlayer();
		world_map($player->x,$player->y);
		player_info($player);
		controls();
			
     echo '<div id="play_screen_content">';
      
		$this->displayContent();
      
     echo '</div>';
	}

	function __construct() {
		Styles::addStyle('play_page');
		Styles::addStyle('common');
	  $this->player = Player::actualPlayer();
	}
	

}

?>
