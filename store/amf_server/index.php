<?php 
set_include_path('.' 
. PATH_SEPARATOR . '../library'
. PATH_SEPARATOR . '../../../config/'
. PATH_SEPARATOR . 'models/'
. PATH_SEPARATOR . 'controllers/' . PATH_SEPARATOR .
get_include_path());

defined('APPLICATION_ENV') || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'development'));

 require_once ("Zend/Loader/Autoloader.php");
 $autoloader = Zend_Loader_Autoloader::getInstance();
 $autoloader->setFallbackAutoloader(true);
 
 $config = new Zend_Config_Ini('config.ini', APPLICATION_ENV);
 
 $db = Zend_Db::factory('Pdo_Sqlite', array(
     'host' => $config->database->params->host,
     'username' => $config->database->params->username,
     'password' => $config->database->params->password,
     'dbname' => $config->database->params->dbname
 ));
 
 Zend_Db_Table_Abstract::setDefaultAdapter($db);
 Zend_Registry::set('db', $db);
 
 $authCheck = new authController();
 
 $server = new Zend_Amf_Server();
 $server->setProduction(false);
 $server->setClass("authController");
 
 $authType = new Zend_Session_Namespace('authType');
 if($authCheck->checkAuth() == true && $authType->auth == 'root') {
 	$server->setClass("orderController");
 } 

 echo $server->handle();
?>