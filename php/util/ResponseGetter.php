<?php
abstract class ResponseGetter extends Login{
  public static $RESPONSE_MESSAGE='{"status":%s,"code":%s,"message":"%s"}';
  public function get($pageId){
    $loginResult=null;
    $request=new Data();
    if(isset($_COOKIE[TOKEN])){
      $request->access_token=$_COOKIE[TOKEN];
      $loginResult=json_decode($this->login($request));
      if(!$loginResult->status){
        return self::createResponse("false",E_NO_LOGIN,'AccessDenied.');
      }
    }else{
      return self::createResponse("false",E_NO_LOGIN,'AccessDenied.');
    }
    $request->user_name=$loginResult->user_name;
    $request->role=$loginResult->role;
    return $this->buildResponse($pageId,$request);

  }
  public abstract function buildResponse($pageId,$request);
  public static function createResponse($status,$code,$message){
    return sprintf(self::$RESPONSE_MESSAGE,$status,$code,$message);
  }

}
?>
