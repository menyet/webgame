<?php

class Styles {
  private static $styles = array();
  
  public static function addStyle($file) {
    self::$styles[] = $file;
  }
  
  public static function printStyle() {
    foreach(self::$styles as $style) {
      echo '<link href="'.URL.'css/'.$style.'.css" rel="stylesheet" type="text/css"/>';
    }
  }
  
}


?>