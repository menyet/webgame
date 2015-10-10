<?php

require_once('Player.php');

class playerManager {
  private $actualPlayer = null;
  private static $instance = null;

  private function __construct() {
    $this->sql = MySQLConnection::getInstance();
  }

  public function login($name, $password) {
    $query = 'SELECT id FROM players WHERE UPPER(username) = UPPER(?) AND password = MD5(?)';
    $stmt = $this->sql->prepare($query);
			
    $stmt->bind_param('ss', $name, $password);
			
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows != 1) {
      session_unset();
      return;
    }

    $stmt->bind_result($id);
    $stmt->fetch();

    $_SESSION['id'] = $id;
  }

  public function loggedin() {
    return isset($_SESSION['id']);
  }
  
  
  


}


?>