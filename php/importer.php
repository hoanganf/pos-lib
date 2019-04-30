<?php
// print result
	define('CONTROLLER_DIR','src/controllers/');
	define('MODEL_DIR','src/models/');
	define('VIEW_DIR','src/views/');

//have to import for login
	include_once 'jwt/BeforeValidException.php';
	include_once 'jwt/ExpiredException.php';
	include_once 'jwt/SignatureInvalidException.php';
	include_once 'jwt/JWT.php';
	include_once 'util/Login.php';
	include_once 'error_handling.php';
?>
