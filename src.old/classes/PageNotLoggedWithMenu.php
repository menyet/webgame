<?php

require_once('class_not_logged_page.php');

abstract class PageNotLoggedWithMenu extends NOT_LOGGED_PAGE {




  function DisplayRightPanel() {
	echo '
        <div class="right">
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
		
		<hr/>
		
		<ul>
			<li>
				<a href="'.URL.'register">Registrácia</a>
			</li>
			<li>
				<a href="'.URL.'screenshots">Screenshoty</a>
			</li>
			<li>
				<a href="#">Dražba</a>
			</li>
			<li>
				<a href="'.URL.'story">Príbeh</a>
			</li>
			<li>
				<a href="'.URL.'topheroes">Top hrdinovia</a>
			</li>
			<li>
				<a href="#">Fan Fiction</a>
			</li>
		</ul>
		
		<hr/>
		
			<div class="popis">
				WASTELAND je online RPG hra, odohrávajúca sa v post-apokalyptickom fantasy svete plnom elfov, trpaslíkov, mágie a zbraní. Na hranie nemusíte nič inštalovať, stačí váš internetový prehliadač.
			</div>
		
		<hr/>
			
			<div class="screenshot">';
				
				$screen_number = rand(1,2);
				echo '<a href="'.URL.'images/title_page/screenshots/screenshot'.$screen_number.'.png"><img src="'.URL.'images/title_page/screenshots/screenshot'.$screen_number.'.png" alt="Screenshot z hry Wasteland" title="Screenshot z hry Wasteland" /></a>';
				
				echo '
			</div>
			
	</div>';
  }
}

?>