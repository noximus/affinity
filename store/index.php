<?php
set_include_path('.' 
. PATH_SEPARATOR . 'library/'
. PATH_SEPARATOR . '../../config/'
. PATH_SEPARATOR . 'models/'
. PATH_SEPARATOR . 'smarty_libs/'
. PATH_SEPARATOR . 'controllers/' . PATH_SEPARATOR .
get_include_path());

 defined('APPLICATION_ENV') || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'development'));

 require_once ("Zend/Loader/Autoloader.php");
 $autoloader = Zend_Loader_Autoloader::getInstance();
 $autoloader->setFallbackAutoloader(true);
 
 $config = new Zend_Config_Ini('config.ini', APPLICATION_ENV);
 $tpl = new smartyConf();
 $prodarr = new Products();
 $products = $prodarr->getProducts();
 $paypal = new paypalController();
 $paypal->setConfig($config->paypal->username, $config->paypal->password, $config->paypal->signature, $config->paypal->apiendpoint, $config->paypal->url);
 
 $db = Zend_Db::factory('Pdo_Sqlite', array(
     'host' => $config->database->params->host,
     'username' => $config->database->params->username,
     'password' => $config->database->params->password,
     'dbname' => $config->database->params->dbname
 ));
 
 Zend_Db_Table_Abstract::setDefaultAdapter($db);
 Zend_Registry::set('db', $db);
 Zend_Session::setOptions($config->session->toArray());
 Zend_Session::rememberMe();
 if(Zend_Session::sessionExists() == false){
    Zend_Session::start();
 }
 
 $order = new orderController();
 $userCart = new Zend_Session_Namespace('userCart');
 $paypalData = new Zend_Session_Namespace('paypalData');

 $_action = isset($_REQUEST['action']) ? $_REQUEST['action'] : 'index';
 $tpl_path = 'template/';

 switch ($_action) {
	case 'index':
	default:
	   $tpl->assign('path', $tpl_path);
	   $tpl->assign('template', 'home.tpl');
       $tpl->display('index.tpl');
	break;
	
	case 'products':
	   $tmp = $userCart->prod;
	   $total = 0;
	   if(sizeof($tmp) > 0) {
	   	 foreach ($userCart->prod as $row)  {
	   	   $total += $row['qty']*$products[$row['name']]['prize'];
	   	 }
	   }
	   
	   if(sizeof($tmp)>1 || sizeof($tmp) == 0) {
	     $tpl->assign('item', sizeof($tmp).' items');
	   } else {
	   	 $tpl->assign('item', sizeof($tmp).' item');
	   }
	   
	   $prd = '';
	   $i = 0;
	   foreach($products as $key) {
	   	if($i == 1) {
	   	 $i = -1;
	     $prd .= '<div id="product" style="margin-left:45px; margin-right:45px;">';
	   	} else {
	   	 $prd .= '<div id="product">';
	   	 $i++;	
	   	}
	     $prd .= '<h4>'.$key['name'].'</h4>';
	     $prd .= '<a href="index.php?action=frame&view='.$key['ident'].'&ar='.$key['arrident'].'"><img src="'.$tpl_path.'images/'.$key['ident'].'.jpg" width="190" height="110" /></a>';
	     $prd .= '<div class="buy"><a href="index.php?action=frame&view='.$key['ident'].'&ar='.$key['arrident'].'">Pre-order now</a></div>';
	     $prd .= '</div>';
	   }
	   
	   $tpl->assign('path', $tpl_path);
	   $tpl->assign('products', $prd);
	   $tpl->assign('total', '$ '.number_format($total, 2, '.', ','));
	   $tpl->assign('template', 'products.tpl');
	   $tpl->display('index.tpl');
	break;
	
	case 'frame':
       $tmp = $userCart->prod;
	   $total = 0;
	   if(sizeof($tmp) > 0) {
	   	 foreach ($userCart->prod as $row)  {
	   	   $total += $row['qty']*$products[$row['name']]['prize'];
	   	 }
	   }
	   
	   if(sizeof($tmp)>1 || sizeof($tmp) == 0) {
	     $tpl->assign('item', sizeof($tmp).' items');
	   } else {
	   	 $tpl->assign('item', sizeof($tmp).' item');
	   }
		
	   $tpl->assign('total', '$ '.number_format($total, 2, '.', ','));
	   $tpl->assign('path', $tpl_path);
	   $tpl->assign('ar', $_REQUEST['ar']);
	   $tpl->assign('view', $_REQUEST['view']);
	   $tpl->assign('template', 'frames/'.$_REQUEST['view'].'.tpl');
	   $tpl->display('index.tpl');
	break;
	
	case 'addtocart':
		$ex = false;
		if($_REQUEST['ilosc']>0){
		   if(sizeof($userCart->prod) == 0) {
		      $userCart->prod = array();
		   }
		   $tmp = $userCart->prod;
		   
		   for($c = 0; $c<sizeof($tmp); $c++) {
		   	  if($tmp[$c]['name'] == $_REQUEST['ar']) {
		   	  	$tmp[$c]['qty'] += $_REQUEST['ilosc'];
		   	  	$ex = true;
		   	  }
		   }
		   
		   if($ex == false) {
		      array_push($tmp, array('name' => $_REQUEST['ar'], 'qty' => $_REQUEST['ilosc']));
		   }
	       $userCart->prod = $tmp;
		}
	    header('Location: index.php?action=frame&view='.$_REQUEST['view'].'&ar='.$_REQUEST['ar']);
	break;
	
	case 'viewcart':	
		$total = 0;
		$tmp = $userCart->prod;
	    if(sizeof($tmp) > 0) {
	   	  foreach ($userCart->prod as $row)  {
	   	    $total += $row['qty']*$products[$row['name']]['prize'];
	   	  }
	    
	
		$table = '<table width="600" border="0" cellspacing="0" cellpadding="0">';
		$table .= '<tr><td width="150px" class="top">Product</td><td width="90px" class="top">Prize</td><td width="50px" class="top">Qty.</td><td width="70px" class="top">&nbsp;</td><td width="90px" class="top">&nbsp;</td><td class="top">Total</td></tr>';
		$i = 0;
 	    foreach ($userCart->prod as $row)  {
 	    	$table .= '<tr><td>'.$products[$row['name']]['name'].'</td><td>$ '.number_format($products[$row['name']]['prize'], 2, '.', ',').'</td><td>'.$row['qty'].'</td><td><a href=index.php?action=deleteitem&item='.$i.'>Delete</a></td><td>&nbsp;</td><td>$ '.number_format($row['qty']*$products[$row['name']]['prize'], 2, '.', ',').'</td></tr>';
			$i++;
		}
		$table .= '<tr><td colspan="4" class="none">&nbsp;</td><td class="sub">Sub-total:</td><td>$ '.number_format($total, 2, '.', ',').'</td></tr>';
		$table .= '</table>';
		
		$tpl->assign('table', $table);
		$tpl->assign('show', 1);
	    } else {
	    	$tpl->assign('show', 0);
	    }
	    $tpl->assign('path', $tpl_path);
		$tpl->assign('template', 'cart.tpl');
		$tpl->display('index.tpl');
	break;
	
	case 'deleteitem':
		$tmp = $userCart->prod;
		$newarr = array();
		for($a = 0; $a<sizeof($tmp); $a++) {
			if($a != $_REQUEST['item']){
				array_push($newarr, $tmp[$a]);
			}
		}
		$userCart->prod = $newarr;
		header('Location: index.php?action=viewcart');
	break;
	
	case 'checkout':
       $total = 0;
       $checkoutarr = array();
	   $tmp = $userCart->prod;
	   if(sizeof($tmp) > 0) {
	   	 foreach ($userCart->prod as $row)  {
	   	   array_push($checkoutarr, array('L_NAME' => $products[$row['name']]['name'], 'L_AMT'=>$products[$row['name']]['prize'], 'L_QTY' => $row['qty']));
	   	   $total += $row['qty']*$products[$row['name']]['prize'];
	   	 }
	   } 
	   $paypalData->list = $checkoutarr;
	   $paypalData->cartTotal = $total;
	   	
	   $resArray = $paypal->CallShortcutExpressCheckout($total, "USD", "Sale", 'http://affinitycycles.com/store/index.php?action=return', 'http://affinitycycles.com/store/index.php?action=cancel', $checkoutarr);
	   $ack = strtoupper($resArray["ACK"]);
       if($ack=="SUCCESS") {
	      $paypal->RedirectToPayPal ($resArray["TOKEN"]);
       } else {
	      //Display a user friendly Error on the page using any of the following error information returned by PayPal
	      $ErrorCode = urldecode($resArray["L_ERRORCODE0"]);
	      $ErrorShortMsg = urldecode($resArray["L_SHORTMESSAGE0"]);
	      $ErrorLongMsg = urldecode($resArray["L_LONGMESSAGE0"]);
	      $ErrorSeverityCode = urldecode($resArray["L_SEVERITYCODE0"]);
	
	      echo "SetExpressCheckout API call failed. ";
	      echo "Detailed Error Message: " . $ErrorLongMsg;
	      echo "Short Error Message: " . $ErrorShortMsg;
	      echo "Error Code: " . $ErrorCode;
	      echo "Error Severity Code: " . $ErrorSeverityCode;
       }
	break;
	
	case 'return':
       $token = "";
       if(isset($_REQUEST['token'])) {
	      $token = $_REQUEST['token'];
       }

       if($token != "") {
	      $resArray = $paypal->GetShippingDetails($token);
	      $ack = strtoupper($resArray["ACK"]);
	      if($ack == "SUCCESS") {
		     $paypalData->email 				= $resArray["EMAIL"]; // ' Email address of payer.
		     $paypalData->payerId 	     		= $resArray["PAYERID"]; // ' Unique PayPal customer account identification number.
		     //$paypalData->payerStatus		    = $resArray["PAYERSTATUS"]; // ' Status of payer. Character length and limitations: 10 single-byte alphabetic characters.
		     //$paypalData->salutation			= $resArray["SALUTATION"]; // ' Payer's salutation.
		     $paypalData->firstName		    	= $resArray["FIRSTNAME"]; // ' Payer's first name.
		     $paypalData->middleName			= $resArray["MIDDLENAME"]; // ' Payer's middle name.
		     $paypalData->lastName	    		= $resArray["LASTNAME"]; // ' Payer's last name.
		     $paypalData->suffix				= $resArray["SUFFIX"]; // ' Payer's suffix.
		     $paypalData->cntryCode	    		= $resArray["COUNTRYCODE"]; // ' Payer's country of residence in the form of ISO standard 3166 two-character country codes.
		     $paypalData->business		    	= $resArray["BUSINESS"]; // ' Payer's business name.
		     $paypalData->shipToName			= $resArray["SHIPTONAME"]; // ' Person's name associated with this address.
		     $paypalData->shipToStreet	    	= $resArray["SHIPTOSTREET"]; // ' First street address.
		     $paypalData->shipToStreet2	    	= $resArray["SHIPTOSTREET2"]; // ' Second street address.
		     $paypalData->shipToCity			= $resArray["SHIPTOCITY"]; // ' Name of city.
		     $paypalData->shipToState		    = $resArray["SHIPTOSTATE"]; // ' State or province
		     $paypalData->shipToCntryCode	    = $resArray["SHIPTOCOUNTRYCODE"]; // ' Country code. 
		     $paypalData->shipToZip		    	= $resArray["SHIPTOZIP"]; // ' U.S. Zip code or other country-specific postal code.
		     //$paypalData->addressStatus 		= $resArray["ADDRESSSTATUS"]; // ' Status of street address on file with PayPal   
		     //$invoiceNumber		    = $resArray["INVNUM"]; // ' Your own invoice or tracking number, as set by you in the element of the same name in SetExpressCheckout request .
		     $paypalData->phonNumber			= $resArray["PHONENUM"]; // ' Payer's contact telephone number. Note:  PayPal returns a contact telephone number only if your Merchant account profile settings require that the buyer enter one. 
	         header('Location: index.php?action=confirmpay');      
	      } else {
		     $ErrorCode = urldecode($resArray["L_ERRORCODE0"]);
		     //$ErrorShortMsg = urldecode($resArray["L_SHORTMESSAGE0"]);
		     $ErrorLongMsg = urldecode($resArray["L_LONGMESSAGE0"]);
		     $ErrorSeverityCode = urldecode($resArray["L_SEVERITYCODE0"]);
		
		     //echo "GetExpressCheckoutDetails API call failed. ";
		     //echo "Detailed Error Message: " . $ErrorLongMsg;
		     //echo "Short Error Message: " . $ErrorShortMsg;
		     //echo "Error Code: " . $ErrorCode;
		     //echo "Error Severity Code: " . $ErrorSeverityCode;
		     $tpl->assign('path', $tpl_path);
		     $tpl->assign('e_code', 'Error Code: ' . $ErrorCode);
		     $tpl->assign('s_code', 'Error Severity Code: ' . $ErrorSeverityCode);
		     $tpl->assign('msg', 'Detailed Error Message: ' . $ErrorLongMsg);
	         $tpl->assign('template', 'critical_error.tpl');
	         $tpl->display('index.tpl');
	      }
       }
	break;
	
	case 'confirmpay':
       $resArray = $paypal->ConfirmPayment($paypalData->cartTotal, $paypalData->list);
	   $ack = strtoupper($resArray["ACK"]);
	   if( $ack == "SUCCESS" ) {
		  $transactionId		= $resArray["TRANSACTIONID"]; // ' Unique transaction ID of the payment. Note:  If the PaymentAction of the request was Authorization or Order, this value is your AuthorizationID for use with the Authorization & Capture APIs. 
		  //$transactionType 	    = $resArray["TRANSACTIONTYPE"]; //' The type of transaction Possible values: l  cart l  express-checkout 
		  $paymentType		    = $resArray["PAYMENTTYPE"];  //' Indicates whether the payment is instant or delayed. Possible values: l  none l  echeck l  instant 
		  $orderTime 			= $resArray["ORDERTIME"];  //' Time/date stamp of payment
		  $amt				    = $resArray["AMT"];  //' The final amount charged, including any shipping and taxes from your Merchant Profile.
		  //$currencyCode		    = $resArray["CURRENCYCODE"];  //' A three-character currency code for one of the currencies listed in PayPay-Supported Transactional Currencies. Default: USD. 
		  $feeAmt				= $resArray["FEEAMT"];  //' PayPal fee amount charged for the transaction
		  //$settleAmt			= $resArray["SETTLEAMT"];  //' Amount deposited in your PayPal account after a currency conversion.
		  //$taxAmt				= $resArray["TAXAMT"];  //' Tax charged on the transaction.
		  //$exchangeRate		    = $resArray["EXCHANGERATE"];  //' Exchange rate if a currency conversion occurred. Relevant only if your are billing in their non-primary currency. If the customer chooses to pay with a currency other than the non-primary currency, the conversion occurs in the customer’s account.
		
		  $paymentStatus	= $resArray["PAYMENTSTATUS"]; 
		  $pendingReason	= $resArray["PENDINGREASON"];  	
		 // $reasonCode		= $resArray["REASONCODE"];   
		  $prod_data = array();
		  $data = array(
		     'transactionId' => $transactionId,
		     'payerId' => $paypalData->payerId,
		     'orderTime' => $orderTime,
		     'firstName' => $paypalData->firstName,
		     'middleName' => $paypalData->middleName,
		     'lastName'=> $paypalData->lastName,
		     'suffix' => $paypalData->suffix,
		     'cntryCode' => $paypalData->cntryCode,
		     'business' => $paypalData->business,
		     'email' => $paypalData->email,
		     'shipToName' => $paypalData->shipToName,
		     'shipToStreet' => $paypalData->shipToStreet,
		     'shipToStreet2' => $paypalData->shipToStreet2,
		     'shipToCity' => $paypalData->shipToCity,
		     'shipToState' => $paypalData->shipToState,
		     'shipToCntryCode' => $paypalData->shipToCntryCode,
		     'shipToZip' => $paypalData->shipToZip,
		     'phonNumber' => $paypalData->phonNumber,
		     'paymentType' => $paymentType,
		     'paymentStatus' => $paymentStatus,
		     'pendingReason' => $pendingReason,
		     'amt' => $amt,
		     'feeAmt' => $feeAmt,
		     'currentStatus' => 'Waiting for approval',
		     'info' => 'none'
		  );
		  
		  $tmp = $userCart->prod;
	      if(sizeof($tmp) > 0) {
	   	    foreach ($userCart->prod as $row)  {
	   	       array_push($prod_data, array('name' => $products[$row['name']]['name'], 'qty' => $row['qty']));
	   	    }
	      } 
		  $order->addOrder($data, $prod_data);
		  
	      $tpl->assign('msg', 'OKOKOKOKOKOKOKOKOKOKOKOKOKOKOKOKOKOK');
	   } else {
		$ErrorCode = urldecode($resArray["L_ERRORCODE0"]);
		$ErrorShortMsg = urldecode($resArray["L_SHORTMESSAGE0"]);
		//$ErrorLongMsg = urldecode($resArray["L_LONGMESSAGE0"]);
		//$ErrorSeverityCode = urldecode($resArray["L_SEVERITYCODE0"]);
		
		//echo "GetExpressCheckoutDetails API call failed. ";
		//echo "Detailed Error Message: " . $ErrorLongMsg;
		//echo "Short Error Message: " . $ErrorShortMsg;
		//echo "Error Code: " . $ErrorCode;
		//echo "Error Severity Code: " . $ErrorSeverityCode;
		$tpl->assign('msg', "Error Code: " . $ErrorCode . '<br />' . $ErrorShortMsg);
	  }
	  $tpl->assign('path', $tpl_path);
	  $tpl->assign('template', 'confirm.tpl');
	  $tpl->display('index.tpl');
	break;
	
	case 'cancel':
	  	$tpl->assign('path', $tpl_path);
		$tpl->assign('template', 'cancel.tpl');
		$tpl->display('index.tpl');
	break;
	
	case 'trackorder':
        $tpl->assign('path', $tpl_path);
        if (isset($_REQUEST['log']) == true && $_REQUEST['log'] != '' && isset($_REQUEST['pas']) == true && $_REQUEST['pas'] != ''){
            $bck = $order->trackOrder($_REQUEST['log'], $_REQUEST['pas']);
            if($bck != false){
            $tpl->assign('template', 'track.tpl');
            $table = '<table width="500" border="0" cellspacing="0" cellpadding="0">';
            $table .= '<tr><td width="150px" class="top">Product</td><td width="90px" class="top">Prize</td><td width="100px" class="top">Qty.</td><td class="top">Total</td></tr>';
            $tmp = $bck['products'];
            $total = 0;
            foreach ($tmp as $row)  {
              foreach($products as $key) {
              	if($key['name'] == $row['name']) {
              		$prs = $key['prize'];
              		$total += $prs;
              	}
              }
 	    	  $table .= '<tr><td>'.$row['name'].'</td><td>$ '.number_format($prs, 2, '.', ',').'</td><td>'.$row['qty'].'</td><td>$ '.number_format($prs*$row['qty'], 2, '.', ',').'</td></tr>';
            }
            $table .= '<tr><td colspan="2" class="none">&nbsp;</td><td class="sub">Sub-total:</td><td>$ '.number_format($total, 2, '.', ',').'</td></tr>';
		    $table .= '</table>';
            $tpl->assign('status', $bck['currentStatus']);
            $tpl->assign('table', $table);
            $tpl->assign('info', $bck['info']);
           }else{
            $tpl->assign('template', 'error.tpl');
           }
        }else{
          $tpl->assign('template', 'track_login.tpl');
        }
        $tpl->display('index.tpl');
	break;
 }
?>