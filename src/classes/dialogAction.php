<?php




class DialogAction {
  const TYPE_EXIT_CONVERSATION = 1;
  const TYPE_ADD_QUEST = 2;
  
  private $type = 0;
  private $param1 = 0;
  private $param2 = 0;
  private $param3 = 0;
  
  public function __construct($type, $param1, $param2, $param3) {
    $this->type = $type;
    $this->param1 = $param1;
    $this->param2 = $param2;
    $this->param3 = $param3;
  }
  
  public function __get($name) {
    switch($name) {
      case 'type': return $this->type;
      case 'param1': return $this->param1;
      case 'param2': return $this->param2;
      case 'param3': return $this->param2;
      default: throw new Exception('Object of class `DialogAction` has no member `'.$name.'`');
    }
  }
  
  
  
  
  

}


?>
