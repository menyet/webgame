<?php

class ItemModifier {
  private $id = 0;
  
  private $data = null;

  private function populate() {
    $db = MySQLConnection::getInstance();

    $query = 'SELECT c.print_name FROM item_modifiers i,mod_classes c WHERE i.item_id=? AND i.class=c.class AND position="predpona" LIMIT 1;";


   


  }


}

?>