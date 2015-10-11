<?php

require_once('class_not_logged_page.php');

class TITLE_PAGE extends NOT_LOGGED_PAGE
{
	// konstruktor triedy
	function TITLE_PAGE(){
    Styles::addStyle('title_page');
		
		
	}
	
	function DisplayContent(){
		echo '
					<form action="'.URL.'" method="post">
  			    <p>
              <label for="login_name">meno</label>
              <input type="text" size="20" name="login_name" id="login_name" />
            </p>
            <p>
              <label for="login_password">heslo</label>
              <input type="password" size="20" name="login_password" id="login_password" />
            </p>
            <p>
              <input type="submit" name="login" value="vstúpiť" class="submit" />
            </p>
          </form>


';
		
		/*$title_images_count = 8;
		echo '
		<div class="left">
			<img src="'.URL.'images/title_page/illustration'.rand(1,$title_images_count).'.png" alt="">
		</div>';*/
		
	}
}

?>
