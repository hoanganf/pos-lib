<?php
class DAO{
  protected $connection=null;
  function connect(){
    if($this->connection==null){
      $args = func_get_args();
      $argCount = func_num_args();
      switch ($argCount) {
        case 4:
          //echo 'case 4 <br/>';
          if($args[0] && $args[1] && $args[2] && $args[3]){
          //  echo 'case 4-OK <br/>';
            $this->connection=mysqli_connect($args[0],$args[1],$args[2],$args[3]);
            break;
          }
        case 1:
          //echo 'case 1 <br/>';
          if($args[0]){
          //  echo $args[0];
            $this->connection=mysqli_connect(HOST,USER_NAME,PASSWORD,$args[0]);
            break;
          }
        default:
        //  echo 'case default <br/>';
          $this->connection=mysqli_connect(HOST,USER_NAME,PASSWORD,DATABASE_NAME);
      }
      // Check connection
      if (!$this->connection) {
        trigger_error("Connection failed: ".mysqli_connect_error(), E_WARNING);
      }
      //set UTF
      mysqli_query($this->connection,"SET NAMES 'utf8'");
    }
    //echo 'end';
    return $this->connection;
  }
  public function __call($functionName, $args){
    //echo $functionName;
    //var_dump($args);
    switch (count($args)) {
      case 5:
        $this->connect($args[1],$args[2],$args[3],$args[4]);
        break;
      case 2:
        $this->connect($args[1]);
        break;
      default:
        $this->connect();
    }
    if(isset($args[0])){
    	$queryResult=mysqli_query($this->connection,$args[0]);
      if(!$queryResult){
        trigger_error("[SQL]:".$args[0]."[Query Error description]: " . mysqli_error($this->connection));
      }
      switch ($functionName) {
        case 'query':
        case "delete":
        case "update":
        case "multiQuery":
          $this->close();
          return $queryResult;
        case "insert":
          $queryResult=$this->getLastInsertId();
          $this->close();
          return $queryResult;
        case "queryNotAutoClose":
          return $queryResult;
        default:
          //DO NOTHING;
      }
    }else{
      trigger_error("[Query Error description]: No SQL query");
    }
  }
  public function getLastInsertId(){
    return mysqli_insert_id($this->connection);
  }

  public static function log($content){
    file_put_contents("log.xml", $content, FILE_APPEND | LOCK_EX);
  }

  public function close(){
  	if($this->connection!==null){
      mysqli_close($this->connection);
      $this->connection=null;
    }
  }

  public function commit(){
    if($this->connection!==null) mysqli_commit($this->connection);
  }

  public function rollBack(){
  	if($this->connection!==null) mysqli_rollback($this->connection);
  }

  public function setAutoCommit($status=TRUE){
  	if($this->connection!==null) mysqli_autocommit($this->connection,$status);
  }

  public function fetchArray($queryResult){
  	return mysqli_fetch_array($queryResult,MYSQLI_ASSOC);
  }

  public function convertQueryResultToList($queryResult){
    $list=array();
    while($row = $this->fetchArray($queryResult)){
      //array_push($list,$this->convertArrayToData($row));
      array_push($list,$row);
  	}
    return $list;
  }
  /*private function convertArrayToData($array){
    $data=new Data();
    foreach($array as $key => $value) {
      $data->$key=$value;
    }
    return $data;
  }*/

  public function getListQuery($sql,$host=null,$userName=null,$password=null,$database=null){
    $result=array();
    $queryResult=$this->query($sql,$host,$userName,$password,$database);
    return $this->convertQueryResultToList($queryResult);
  }

  public function getRowQuery($sql,$host=null,$userName=null,$password=null,$database=null){
    $queryResult=$this->query($sql,$host,$userName,$password,$database);
    if($row = $this->fetchArray($queryResult)){
  		//return $this->convertArrayToData($row);
      return $row;
  	}else{
      return null;
    }
  }
}
?>
