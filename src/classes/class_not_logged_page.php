<?php
abstract class NOT_LOGGED_PAGE extends PAGE{

	// konstruktor triedy
	function NOT_LOGGED_PAGE(){
	}
	
	
	final function DisplayBody() {
	   echo '
      <div class="center">
        <h1>Wasteland</h1>
        <a href="'.URL.'index.php" title="Wasteland - Online fantasy RPG hra"><img src="'.URL.'images/title_page/title.gif" alt="Wasteland" class="title_image" /></a>
        <h2>[ online fantasy RPG hra ]</h2>

				<p>
					<a href="'.URL.'">Prihlásenie</a> 
					<a href="'.URL.'register">Registrácia</a>
					<a href="'.URL.'story">Príbeh</a>
					<a href="'.URL.'">Sieň slávy</a>
				</p>

        <div class="not_logged_content">
      ';

    $this->DisplayContent();
    echo '
        </div>';
    
    
    $this->DisplayRightPanel();
    echo '
      </div>';
  }
  
  abstract function DisplayContent();
	
	function DisplayRightPanel() {

	
  }

}


?>
