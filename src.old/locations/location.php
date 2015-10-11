<?php





abstract class Location  {
  private $img_path = '';
  private $description = '';


  protected function setImagePath($path) {
    $this->img_path = $path;
  }

  protected function setDescription($description) {
    $this->description = $description;
  }

  


  public function __construct(){
  }
  


	
}

?>