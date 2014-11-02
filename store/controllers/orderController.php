<?php
class orderController extends dataBase {
	public function addOrder($data, $prod_data) {
		$db = Zend_Registry::get('db');
		$db->insert('af_orders', $data); 
        $id = $db->lastInsertId();
        for($i = 0; $i<sizeof($prod_data); $i++) {
          $tmp = array(
              'parentID' => $id,
              'name' => $prod_data[$i]['name'],
              'qty' => $prod_data[$i]['qty']
          );
          $db->insert('af_prod', $tmp); 	
        }
	}
	
	public function trackOrder($email, $orderid) {
		$db = Zend_Registry::get('db');
		$trackOrder = new trackOrder();
	    $authAdapter = new Zend_Auth_Adapter_DbTable($trackOrder->getAdapter());	
	    $authAdapter->setTableName('af_orders');
	    $authAdapter->setIdentityColumn('email');
	    $authAdapter->setCredentialColumn('transactionId');
	   
	    $authAdapter->setIdentity($email)->setCredential($orderid);
	   
	    $auth = Zend_Auth::getInstance();
	    $result = $auth->authenticate($authAdapter);
	    
	    if($result->isValid()) {
	      $query = $db->select(); 
	      $query->from('af_orders', array('ID', 'amt', 'currentStatus', 'info'));
	      $query->where('transactionId = ?', $orderid);
	      $dbresult1 = $db->fetchAll($query); 
	      $query = $db->select(); 
	      $query->from('af_prod');
	      $query->where('parentID = ?', $dbresult1[0]['ID']);
	      $dbresult2 = $db->fetchAll($query); 
	      $tmp2 = array();
	      foreach($dbresult2 as $row) { 
	      	 array_push($tmp2, array('name' => $row['name'], 'qty' => $row['qty'])); 
          }
	      
	      $tmp = array(
	         'currentStatus' => $dbresult1[0]['currentStatus'],
	         'info' => $dbresult1[0]['info'],
	         'amt' => $dbresult1[0]['amt'],
	         'products' => $tmp2        
	      );
          return $tmp;
	    } else {
	   	  return false;  
	    }
	}
}
?>