<?php
$nzshpcrt_gateways[$num]['name'] = 'Protx';
$nzshpcrt_gateways[$num]['internalname'] = 'protx';
$nzshpcrt_gateways[$num]['function'] = 'gateway_protx';
$nzshpcrt_gateways[$num]['form'] = "form_protx";
$nzshpcrt_gateways[$num]['submit_function'] = "submit_protx";

if(!function_exists('gateway_protx')) {
	function gateway_protx($seperator, $sessionid) {
		global $wpdb;
		$purchase_log_sql = "SELECT * FROM `".$wpdb->prefix."purchase_logs` WHERE `sessionid`= ".$sessionid." LIMIT 1";
		$purchase_log = $wpdb->get_results($purchase_log_sql,ARRAY_A) ;
		$cart_sql = "SELECT * FROM `".$wpdb->prefix."cart_contents` WHERE `purchaseid`='".$purchase_log[0]['id']."'";
		$cart = $wpdb->get_results($cart_sql,ARRAY_A) ;
	
		foreach($cart as $item) {
			$product_data = $wpdb->get_results("SELECT * FROM `".$wpdb->prefix."product_list` WHERE `id`='".$item['prodid']."' LIMIT 1",ARRAY_A);
			$product_data = $product_data[0];
			//$data['Basket']
		}
		
		$transact_url = get_option('transact_url');
		$data['SuccessURL']=$transact_url.$seperator."protx=success";
		$data['Description']="Your shopping cart";
		
		$data['FailureURL']=$transact_url;
		$data['VendorTxCode'] = $sessionid;
		$data['Amount'] = number_format($purchase_log[0]['totalprice'], 2, '.', '');
		$data['Currency'] = get_option('protx_cur');
	
		if($_POST['collected_data'][get_option('protx_form_post_code')] != ''){   
			$data['BillingPostCode'] = $_POST['collected_data'][get_option('protx_form_post_code')];
		}
	
		if($_POST['collected_data'][get_option('protx_form_address')] != ''){
			$data['BillingAddress'] = $_POST['collected_data'][get_option('protx_form_address')];
		}
	
		if($_POST['collected_data'][get_option('protx_form_city')] != ''){
			$data['BillingAddress'] .= "\r".$_POST['collected_data'][get_option('protx_form_city')]; 
		}
	
		if(preg_match("/^[a-zA-Z]{2}$/",$_SESSION['selected_country'])){ 
			$result = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."currency_list WHERE isocode='".$_SESSION['selected_country']."'",ARRAY_A);
			$data['BillingAddress'] .= "\r".$result[0]['country'];
		}
	
		if($_POST['collected_data'][get_option('protx_form_first_name')] != ''){
			$data['CustomerName'] = $_POST['collected_data'][get_option('protx_form_first_name')]." ".$_POST['collected_data'][get_option('protx_form_last_name')];
		}
		$postdata = "";
		$i=0;
 		//exit("<pre>".print_r($data,true)."</pre>");
		foreach($data as $key => $da) {
			if ($i==0){
				$postdata .= "$key=$da";
			} else {
				$postdata .= "&$key=$da";
			}
		$i++;
		}
		$url = 'https://ukvpstest.protx.com/vspgateway/service/vspform-register.vsp';
		$crypt = base64_encode(SimpleXor($postdata, get_option('protx_enc_key')));
		
		$postdata1['VPSProtocol'] = get_option("protx_protocol");
		$postdata1['Vendor'] = get_option("protx_name");
		$postdata1['TxType'] = "PAYMENT";
		$postdata1['Crypt'] = urlencode($crypt);
	
		$j=0;
		$postdata2= "";
		foreach($postdata1 as $key=>$dat){
			if ($j==0){
				$postdata2 .= "$key=$dat";
			} else {
				$postdata2 .= "&$key=$dat";
			}
		$j++;
		}
		header("Location:".$url."?".$postdata2);
		exit();
	}
	
	function submit_protx()
	{
		if($_POST['protx_name'] != null)
			{
			update_option('protx_name', $_POST['protx_name']);
			}
	
	if($_POST['protx_protocol'] != null)
			{
			update_option('protx_protocol', $_POST['protx_protocol']);
			}
	
	if($_POST['protx_enc_key'] != null)
			{
			update_option('protx_enc_key', $_POST['protx_enc_key']);
			}
	
		if($_POST['protx_cur'] != null)
			{
			update_option('protx_cur', $_POST['protx_cur']);
			}
		
		foreach((array)$_POST['protx_form'] as $form => $value)
		{
			update_option(('protx_form_'.$form), $value);
			}
		return true;
		}
	
	function form_protx()
		{
			global $wpdb;
		$query = "SELECT DISTINCT code FROM ".$wpdb->prefix."currency_list ORDER BY code";
		$result = $wpdb->get_results($query, ARRAY_A);
		$output = "<table>
			<tr>
				<td>
				Protx Vendor name:
				</td>
				<td>
				<input type='text' size='40' value='".get_option('protx_name')."' name='protx_name' />
				</td>
			</tr>
		<tr>
				<td>
				Protx VPS Protocol:
				</td>
				<td>
				<input type='text' size='20' value='".get_option('protx_protocol')."' name='protx_protocol' /> e.g. 2.22
				</td>
			</tr>
		<tr>
				<td>
				Protx Encryption Key:
				</td>
				<td>
				<input type='text' size='20' value='".get_option('protx_enc_key')."' name='protx_enc_key' />
				</td>
			</tr>
			<tr>
				<td>
				Select your currency
				</td>
				<td>
				<select name='protx_cur'>";
					$current_currency = get_option('protx_cur');
				//exit($current_currency);
					foreach($result as $currency){
					if ($currency['code'] == $current_currency){
						$selected = "selected = 'true'";
					} else {
						$selected = "";
					}
					$output.= "<option $selected value='".$currency['code']."'>".$currency['code']."</option>";
				}
				$output.="</select>
			</tr></table>";
			
		$output.="<h2>Forms Sent to Gateway</h2>
		<table>
			<tr>
				<td>
				First Name Field
				</td>
				<td>
				<select name='protx_form[first_name]'>
				".nzshpcrt_form_field_list(get_option('protx_form_first_name'))."
				</select>
				</td>
		</tr>
			<tr>
				<td>
				Last Name Field
				</td>
				<td>
				<select name='protx_form[last_name]'>
				".nzshpcrt_form_field_list(get_option('protx_form_last_name'))."
				</select>
				</td>
		</tr>
			<tr>
				<td>
				Address Field
				</td>
				<td>
				<select name='protx_form[address]'>
				".nzshpcrt_form_field_list(get_option('protx_form_address'))."
				</select>
				</td>
		</tr>
		<tr>
				<td>
				City Field
				</td>
				<td>
				<select name='protx_form[city]'>
				".nzshpcrt_form_field_list(get_option('protx_form_city'))."
				</select>
				</td>
		</tr>
		<tr>
				<td>
				State Field
				</td>
				<td>
				<select name='protx_form[state]'>
				".nzshpcrt_form_field_list(get_option('protx_form_state'))."
				</select>
				</td>
		</tr>
		<tr>
				<td>
				Postal code/Zip code Field
				</td>
				<td>
				<select name='protx_form[post_code]'>
				".nzshpcrt_form_field_list(get_option('protx_form_post_code'))."
				</select>
				</td>
		</tr>
		<tr>
				<td>
				Country Field
				</td>
				<td>
				<select name='protx_form[country]'>
				".nzshpcrt_form_field_list(get_option('protx_form_country'))."
				</select>
				</td>
		</tr>
	</table> ";
		return $output;
		}
	
	function simpleXor($InString, $Key) {
		// Initialise key array
		$KeyList = array();
		// Initialise out variable
		$output = "";
		
		// Convert $Key into array of ASCII values
		for($i = 0; $i < strlen($Key); $i++){
			$KeyList[$i] = ord(substr($Key, $i, 1));
		}
	
		// Step through string a character at a time
		for($i = 0; $i < strlen($InString); $i++) {
			// Get ASCII code from string, get ASCII code from key (loop through with MOD), XOR the two, get the character from the result
			// % is MOD (modulus), ^ is XOR
			$output.= chr(ord(substr($InString, $i, 1)) ^ ($KeyList[$i % strlen($Key)]));
		}
	
		// Return the result
		return $output;
	}
}

function nzshpcrt_protx_decryption() {
		if(get_option('permalink_structure') != '') {
      $seperator ="?";
		} else {
      $seperator ="&";
		}
		$uncrypt = SimpleXor(base64_decode($_GET['crypt']), get_option('protx_enc_key'));
	  parse_str($uncrypt, $unencrypted_values);
	  
		$transact_url = get_option('transact_url').$seperator."sessionid=".$unencrypted_values['VendorTxCode'];
	  //echo "<pre>".print_r($transact_url,true)."</pre>";
	  header("Location: $transact_url");
	  exit();
}


if(($_GET['protx'] == 'success') && ($_GET['crypt'] != '')) {
	add_action('init', 'nzshpcrt_protx_decryption');
}

?>
