<?php
require_once 'DAO.php';
class BaseDAO extends DAO{
  private $tableName;
  function __construct($tableName) {
    $this->tableName = $tableName;
  }
  public function getTableName(){
    return $this->tableName;
  }
  public function getAll($host=null,$userName=null,$password=null,$database=null){
    return $this->getListQuery('SELECT * FROM '.$this->tableName,$host,$userName,$password,$database);
  }
  public function getOnceWhere($whereClause,$host=null,$userName=null,$password=null,$database=null){
    return $this->getRowQuery('SELECT * FROM '.$this->tableName.' WHERE '.$whereClause,$host,$userName,$password,$database);
  }
  public function getAllWhere($whereClause,$host=null,$userName=null,$password=null,$database=null){
    return $this->getListQuery('SELECT * FROM '.$this->tableName.' WHERE '.$whereClause,$host,$userName,$password,$database);
  }
  public function getAllQuery($query,$host=null,$userName=null,$password=null,$database=null){
    return $this->getListQuery($query,$host,$userName,$password,$database);
  }
  public function getOnceQuery($query,$host=null,$userName=null,$password=null,$database=null){
    return $this->getRowQuery($query,$host,$userName,$password,$database);
  }
}
?>
