<?php
define('RESPONSE_MESSAGE','{"status":%s,"code":%s,"message":"%s"}');
define('TOKEN','pos_access_token');
define('SUCCEED',200);
define('E_BAD_REQUEST',404);
define('E_NO_ORDER',300);
define('E_NO_NUMBER_ID',301);
define('E_TRANSACTION_FAILT',302);
define('E_MYSQL_CONNECTION_FAIL',303);
define('E_MYSQL_QUERY_FAIL',304);
define('E_DELETE_FAILT',305);
define('E_NO_LOGIN',306);
define('E_NO_PRODUCT',307);
define('E_NO_INGREDIENT',308);
define('E_NO_COMMENT',309);
define('E_NO_RESTAURANT',310);
define('E_NO_PRODUCT_RECIPE',311);
define('E_NO_TABLE',312);
/**FILE ***/
define('E_FILE_NOT_IMAGE',1001);
define('E_FILE_TOO_LARGE',1002);
define('E_FILE_ALREADY_EXITS',1003);
define('E_FILE_NOT_ALLOW',1004);
/**NI**/
define('E_NI',400);
function toLog($code,$err_message='',$errfile='', $errline='', $errcontext=''){
  $message = date("Y-m-d H:i:s - ");
  $message .= "Error: [".$code."], "."$err_message in $errfile on line $errline, ";
  $message .= "Variables:".print_r($errcontext,true)."\r\n";
  error_log($message, 3, LOG_DIR."error_log.log");
}
function toJson($status,$code,$message){
  return sprintf(RESPONSE_MESSAGE,$status,$code,$message);
}
function errorToJson($code,$err_message='',$errfile='', $errline='', $errcontext=''){
  toLog($code,$err_message,$errfile, $errline, $errcontext);
  switch ($code) {
    case 200:
      $status=true;
      break;
    case 300:
    case 301:
    case 302:
    case 305:
    default:
      $status=false;
      break;
  }
  die(sprintf(RESPONSE_MESSAGE,$status,$code,$err_message));
}
function errorRedirect($code,$err_message='',$errfile='', $errline='', $errcontext=''){
  toLog($code,$err_message,$errfile, $errline, $errcontext);
  switch ($code) {
    case 200:
      $status=STATUS_OK;
      break;
    case 300:
    case 301:
    case 302:
    case 305:
    default:
      break;
  }
  include LIB_DIR.'php/error.php';
  die();
}
function getApiDataCurl($url){
    $option = [
        CURLOPT_RETURNTRANSFER => true, //文字列として返す
        CURLOPT_TIMEOUT        => 3, // タイムアウト時間
        CURLOPT_COOKIE => TOKEN.'='.$_COOKIE[TOKEN]
    ];

    $ch = curl_init($url);
    curl_setopt_array($ch, $option);

    $json    = curl_exec($ch);
    $info    = curl_getinfo($ch);
    $errorNo = curl_errno($ch);

    // OK以外はエラーなので空白配列を返す
    if ($errorNo !== CURLE_OK) {
        // 詳しくエラーハンドリングしたい場合はerrorNoで確認
        // タイムアウトの場合はCURLE_OPERATION_TIMEDOUT
        return [];
    }

    // 200以外のステータスコードは失敗とみなし空配列を返す
    if ($info['http_code'] !== 200) {
        return [];
    }

    // 文字列から変換
    $jsonArray = json_decode($json, true);

    return $jsonArray;
}
?>
