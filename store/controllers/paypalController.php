<?php
class paypalController {
	private $username;
	private $password;
	private $signature;
	private $apiendpoint;
	private $url;
	private $PROXY_HOST = '127.0.0.1';
	private $PROXY_PORT = '808';
	private $USE_PROXY = false;
	private $version="57.0";
	
	public function setConfig($username, $password, $signature, $apiendpoint, $url) {
		$this->username = $username;
		$this->password = $password;
		$this->signature = $signature;
		$this->apiendpoint = $apiendpoint;
		$this->url = $url;
	}
	
	public function CallShortcutExpressCheckout($paymentAmount, $currencyCodeType, $paymentType, $returnURL, $cancelURL, $checkoutarr) {
        $payment = new Zend_Session_Namespace('paypalData');
		$nvpstr = "&PAYMENTACTION=" . $paymentType;
		$nvpstr = $nvpstr . "&ReturnUrl=" . $returnURL;
		$nvpstr = $nvpstr . "&CANCELURL=" . $cancelURL;
		$tmp = '';
		for($i = 0; $i<sizeof($checkoutarr); $i++) {
		   $tmp .= "&L_NAME".$i."=" . $checkoutarr[$i]['L_NAME'];
		   $tmp .= "&L_AMT".$i."=" . $checkoutarr[$i]['L_AMT'];
		   $tmp .= "&L_QTY".$i."=" . $checkoutarr[$i]['L_QTY'];
		}
		$nvpstr .= $tmp;
		$nvpstr = $nvpstr . "&CURRENCYCODE=" . $currencyCodeType;
	    $nvpstr = $nvpstr . "&AMT=". $paymentAmount;
	
		$payment->currencyCodeType = $currencyCodeType;	  
		$payment->paymentType = $paymentType;

	    $resArray=$this->hash_call("SetExpressCheckout", $nvpstr);
		$ack = strtoupper($resArray["ACK"]);
		if($ack=="SUCCESS") {
			$token = urldecode($resArray["TOKEN"]);
			$payment->token = $token;
		}
	    return $resArray;
	}
	
	public function GetShippingDetails($token) {
		$payment  = new Zend_Session_Namespace('paypalData');
	    $nvpstr="&TOKEN=" . $token;

	    $resArray=$this->hash_call("GetExpressCheckoutDetails",$nvpstr);
	    $ack = strtoupper($resArray["ACK"]);
		if($ack == "SUCCESS") {	
			$payment->payer_id = $resArray['PAYERID'];
		} 
		return $resArray;
	}
	
	public function ConfirmPayment($FinalPaymentAmt, $checkoutarr) {
        $payment = new Zend_Session_Namespace('paypalData');
		$token 				= urlencode($payment->token);
		$paymentType 		= urlencode($payment->paymentType);
		$currencyCodeType 	= urlencode($payment->currencyCodeType);
		$payerID 			= urlencode($payment->payer_id);

		$serverName 		= urlencode($_SERVER['SERVER_NAME']);

		$nvpstr  = '&TOKEN=' . $token . '&PAYERID=' . $payerID . '&PAYMENTACTION=' . $paymentType . '&AMT=' . $FinalPaymentAmt;
		$nvpstr .= '&CURRENCYCODE=' . $currencyCodeType . '&IPADDRESS=' . $serverName; 
		$tmp = '';
		for($i = 0; $i<sizeof($checkoutarr); $i++) {
		   $tmp .= "&L_NAME".$i."=" . $checkoutarr[$i]['L_NAME'];
		   $tmp .= "&L_AMT".$i."=" . $checkoutarr[$i]['L_AMT'];
		   $tmp .= "&L_QTY".$i."=" . $checkoutarr[$i]['L_QTY'];
		}
		$nvpstr .= $tmp;

		$resArray=$this->hash_call("DoExpressCheckoutPayment",$nvpstr);
		$ack = strtoupper($resArray["ACK"]);
		return $resArray;
	}
	
	public function RedirectToPayPal($token) {	
		$payPalURL = $this->url . $token;
		header("Location: ".$payPalURL);
	}
	
	private function hash_call($methodName, $nvpStr) {
		$payment = new Zend_Session_Namespace('paypalData');
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $this->apiendpoint);
		curl_setopt($ch, CURLOPT_VERBOSE, 1);

		//turning off the server and peer verification(TrustManager Concept).
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);

		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch, CURLOPT_POST, 1);
		
	    //if USE_PROXY constant set to TRUE in Constants.php, then only proxy will be enabled.
	   //Set proxy name to PROXY_HOST and port number to PROXY_PORT in constants.php 
		if($this->USE_PROXY)
			curl_setopt ($ch, CURLOPT_PROXY, $this->PROXY_HOST. ":" . $this->PROXY_PORT); 

		//NVPRequest for submitting to server
		$nvpreq="METHOD=" . urlencode($methodName) . "&VERSION=" . urlencode($this->version) . "&PWD=" . urlencode($this->password) . "&USER=" . urlencode($this->username) . "&SIGNATURE=" . urlencode($this->signature) . $nvpStr;

		//setting the nvpreq as POST FIELD to curl
		curl_setopt($ch, CURLOPT_POSTFIELDS, $nvpreq);

		//getting response from server
		$response = curl_exec($ch);

		//convrting NVPResponse to an Associative Array
		$nvpResArray=$this->deformatNVP($response);
		$nvpReqArray=$this->deformatNVP($nvpreq);
		$payment->nvpReqArray = $nvpReqArray;

		if(curl_errno($ch)) {
		   $payment->curl_error_no = curl_errno($ch);
		   $payment->url_error_msg = curl_error($ch);
		} else {
		   curl_close($ch);
		}
		return $nvpResArray;
	}
	
	private function deformatNVP($nvpstr) {
		$intial=0;
	 	$nvpArray = array();

		while(strlen($nvpstr))
		{
			//postion of Key
			$keypos= strpos($nvpstr,'=');
			//position of value
			$valuepos = strpos($nvpstr,'&') ? strpos($nvpstr,'&'): strlen($nvpstr);

			/*getting the Key and Value values and storing in a Associative Array*/
			$keyval=substr($nvpstr,$intial,$keypos);
			$valval=substr($nvpstr,$keypos+1,$valuepos-$keypos-1);
			//decoding the respose
			$nvpArray[urldecode($keyval)] =urldecode( $valval);
			$nvpstr=substr($nvpstr,$valuepos+1,strlen($nvpstr));
	     }
		return $nvpArray;
	}
}
?>