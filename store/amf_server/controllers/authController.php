<?php
class authController extends dataBase {
	
	public function authRoot($rootName, $rootPassword) {
		
		$rootUser = new rootUsers();
	    $authAdapter = new Zend_Auth_Adapter_DbTable($rootUser->getAdapter());	
	    $authAdapter->setTableName('rootUsers');
	    $authAdapter->setIdentityColumn('rootName');
	    $authAdapter->setCredentialColumn('rootPassword');
	   // $authAdapter->setCredentialTreatment('MD5(?)');
	   
	    $authAdapter->setIdentity($rootName)->setCredential(md5($rootPassword));
	   
	    $auth = Zend_Auth::getInstance();
	    $result = $auth->authenticate($authAdapter);
	   
	    if($result->isValid()) {
	   	   $storage = new Zend_Auth_Storage_Session();
           $storage->write($authAdapter->getResultRowObject());
           $authType = new Zend_Session_Namespace('authtype');
           $authType->auth = "root";
           return true;
	    } else {
	   	  return false;  
	    }
	}
	
	public function checkAuth() {
		$storage = new Zend_Auth_Storage_Session();
        $data = $storage->read();
        if(!$data){
            return false;
        } else {
        	return true;
        }
	}
	
	public function logOut() {
		Zend_Auth::getInstance()->clearIdentity();
		return true;
	}
}
?>