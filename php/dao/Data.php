<?php
class Data{
  private $data;
  public function __construct(){
    $this->data = array();
  }
  public function __set($name, $value){
    $this->data[$name] = $value;
  }
  public function __get($name){
    //echo "Getting '$name'\n";
    if (array_key_exists($name, $this->data)) {
        return $this->data[$name];
    }
    /* TODO not useful
    $trace = debug_backtrace();
     trigger_error(
        'Undefined property via __get(): ' . $name .
        ' in ' . $trace[0]['file'] .
        ' on line ' . $trace[0]['line'],
        E_USER_NOTICE);*/
    return null;
  }
  /**  As of PHP 5.1.0  */
  public function __isset($name)
  {
      //echo "Is '$name' set?\n";
      return isset($this->data[$name]);
  }
  /**  As of PHP 5.1.0  */
  public function __unset($name)
  {
      //echo "Unsetting '$name'\n";
      unset($this->data[$name]);
  }
}
?>
