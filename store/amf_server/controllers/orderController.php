<?php
class orderController extends dataBase {
    public function getOrders() {
       $db = Zend_Registry::get('db');
       $ret_array = array();
       $query = $db->select(); 
       $query->from('af_orders', array('ID', 'transactionId', 'orderTime', 'paymentStatus', 'amt'));
       $dbresult = $db->fetchAll($query); 
       foreach($dbresult as $row) {
          $tmp = new orderVO();
          $tmp->ID = $row['ID'];
          $tmp->transactionId = $row['transactionId'];
          $tmp->orderTime = $row['orderTime'];
          $tmp->paymentStatus = $row['paymentStatus'];
          $tmp->amt = $row['amt'];
          $ret_array[] = $tmp;
        }
       return $ret_array;
    }
    
    public function getOrderDetails($id) {
         $db = Zend_Registry::get('db');
         $query = $db->select(); 
         $query->from('af_orders');
         $query->where('ID = ?', $id);
         $dbresult1 = $db->fetchAll($query); 
         $query = $db->select(); 
         $query->from('af_prod');
         $query->where('parentID = ?', $id);
         $dbresult2 = $db->fetchAll($query); 
         $tmp2 = array();
         foreach($dbresult2 as $row) { 
	      	 array_push($tmp2, array('name' => $row['name'], 'qty' => $row['qty'])); 
         }
	      
         $tmp = array(
	         'ID' => $dbresult1[0]['ID'],
	         'transactionId' => $dbresult1[0]['transactionId'],
	         'payerId' => $dbresult1[0]['payerId'],
             'orderTime' => $dbresult1[0]['orderTime'],
             'firstName' => $dbresult1[0]['firstName'],
             'middleName' => $dbresult1[0]['middleName'],
             'lastName' => $dbresult1[0]['lastName'],
             'suffix' => $dbresult1[0]['suffix'],
             'cntryCode' => $dbresult1[0]['cntryCode'],
             'business' => $dbresult1[0]['business'],
             'email' => $dbresult1[0]['email'],
             'shipToName' => $dbresult1[0]['shipToName'],
             'shipToStreet' => $dbresult1[0]['shipToStreet'],
             'shipToStreet2' => $dbresult1[0]['shipToStreet2'],
             'shipToCity' => $dbresult1[0]['shipToCity'],
             'shipToState' => $dbresult1[0]['shipToState'],
             'shipToCntryCode' => $dbresult1[0]['shipToCntryCode'],
             'shipToZip' => $dbresult1[0]['shipToZip'],
             'phonNumber' => $dbresult1[0]['phonNumber'],
             'paymentType' => $dbresult1[0]['paymentType'],
             'paymentStatus' => $dbresult1[0]['paymentStatus'],
             'pendingReason' => $dbresult1[0]['pendingReason'],
             'amt' => $dbresult1[0]['amt'],
             'feeAmt' => $dbresult1[0]['feeAmt'],
             'currentStatus' => $dbresult1[0]['currentStatus'],
             'info' => $dbresult1[0]['info'],
	         'products' => $tmp2        
         );
         return $tmp;
    }
    
    public function updateOrderStatus($id, $status, $info) {
        $db = Zend_Registry::get('db');
        $data = array(
           'currentStatus' => $status,
           'info' => $info
        );
        $where = $db->quoteInto('ID= ?', $id); 
        $db->update('af_orders', $data, $where);
        return true;
    }
}
?>