<?php
abstract class ResponseGetter extends Login{
  public static $RESPONSE_MESSAGE='{"status":%s,"code":%s,"message":"%s"}';
  public function get($pageId){
    $loginResult=null;
    $request=new Data();
    if(isset($_COOKIE['pos_access_token'])){
      $request->access_token=$_COOKIE['pos_access_token'];
      $loginResult=json_decode($this->login($request));
      if(!$loginResult->status){
        echo self::createResponse("false",E_NO_LOGIN,'AccessDenied.');
        return;
      }
    }else{
      echo self::createResponse("false",E_NO_LOGIN,'AccessDenied.');
      return;
    }
    $request->user_name=$loginResult->user_name;
    $request->role=$loginResult->role;
    $this->buildResponse($pageId,$request);

  }
  public abstract function buildResponse($pageId,$request);
  public static function createResponse($status,$code,$message){
    return sprintf(self::$RESPONSE_MESSAGE,$status,$code,$message);
  }

}
?>
