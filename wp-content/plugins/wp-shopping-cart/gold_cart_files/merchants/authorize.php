<?php
if(!is_callable('get_option')) {
  // This is here to stop error messages on servers with Zend Accelerator, it includes all files before get_option is declared
  // then evidently includes them again, otherwise this code would break these modules
  return;
  exit("Something strange is happening, and \"return\" is not breaking out of a file.");
}

$nzshpcrt_gateways[$num]['name'] = 'Authorize.net';
$nzshpcrt_gateways[$num]['internalname'] = 'authorize';
$nzshpcrt_gateways[$num]['function'] = 'gateway_authorize';
$nzshpcrt_gateways[$num]['form'] = "form_authorize";
$nzshpcrt_gateways[$num]['submit_function'] = "submit_authorize";
//include_once(ABSPATH.'wp-content/plugins/wp-shopping-cart/classes/authorize_class.php');

//if(get_option('payment_gateway') == 'authorize') {
if(in_array('authorize',(array)get_option('custom_gateway_options'))) {
	$gateway_checkout_form_fields[$nzshpcrt_gateways[$num]['internalname']] = "
	<tr>
		<td>Credit Card Number *</td>
		<td>
			<input type='text' value='' name='card_number' />
		</td>
	</tr>
	<tr>
		<td>Credit Card Expiry *</td>
		<td>
			<input type='text' size='2' value='' maxlength='2' name='expiry[month]' />/<input type='text' size='2'  maxlength='2' value='' name='expiry[year]' />
		</td>
	</tr>
	<tr>
		<td>CVV </td>
		<td><input type='text' size='4' value='' maxlength='4' name='card_code' /></td>
	</tr>
";
  }

function gateway_authorize($seperator, $sessionid) {
global $wpdb;
$purchase_log_sql = "SELECT * FROM `".$wpdb->prefix."purchase_logs` WHERE `sessionid`= ".$sessionid." LIMIT 1";
$purchase_log = $wpdb->get_results($purchase_log_sql,ARRAY_A);
$cart_sql = "SELECT * FROM `".$wpdb->prefix."cart_contents` WHERE `purchaseid`='".$purchase_log[0]['id']."'";
$cart = $wpdb->get_results($cart_sql,ARRAY_A);
$prodid=$cart[0]['prodid'];
$product_sql = "SELECT * FROM `".$wpdb->prefix."product_list` WHERE `id`='".$prodid."'";
$product_data = $wpdb->get_results($product_sql,ARRAY_A);
$status = get_product_meta($prodid,'is_membership',true);
$free_trial = get_product_meta($prodid,'free_trial',true);
if (($status[0] == 1) && function_exists('wpsc_members_init')) {
	$membership_length = get_product_meta($prodid,'membership_length',true);
	$membership_length = $membership_length[0];
	$length = $membership_length['length'];
	$unit = $membership_length['unit'];
	if ($unit == 'd') {
		$unit='days';
	} elseif ($unit == 'm') {
		$unit='months';
	}
	$amount = nzshpcrt_overall_total_price($_SESSION['selected_country']);
	$loginname = get_option('authorize_login');
	$transactionkey = get_option("authorize_password");
	$firstName = $_POST['collected_data'][get_option('authorize_form_first_name')];
	$lastName = $_POST['collected_data'][get_option('authorize_form_last_name')];
	$cardNumber = $_POST['card_number'];
	$expirationDate ="20" . $_POST['expiry']['year']."-".$_POST['expiry']['month'] ;
	$cardCode = $_POST['card_code'];
	$startDate=date('Y-m-d');
	$totalOccurrences = 99;
	$trialOccurrences =1;
	$amount = $product_data[0]['price'];
	$trialAmount = 0;

	$xml = "<?xml version='1.0' encoding='utf-8' ?>".
	"<ARBCreateSubscriptionRequest xmlns='AnetApi/xml/v1/schema/AnetApiSchema.xsd'>".
		"<merchantAuthentication>".
			"<name>" . $loginname . "</name>".
			"<transactionKey>" . $transactionkey . "</transactionKey>".
		"</merchantAuthentication>".
		"<refId>Instinct</refId>".
		"<subscription>".
			"<name>Samplesubscription</name>".
				"<paymentSchedule>".
					"<interval>".
						"<length>". $length ."</length>".
						"<unit>". $unit ."</unit>".
					"</interval>".
					"<startDate>" . $startDate . "</startDate>".
					"<totalOccurrences>". $totalOccurrences . "</totalOccurrences>".
					"<trialOccurrences>". $trialOccurrences . "</trialOccurrences>".
				"</paymentSchedule>".
			"<amount>". $amount ."</amount>".
			"<trialAmount>" . $trialAmount . "</trialAmount>".
			"<payment>".
				"<creditCard>".
					"<cardNumber>" . $cardNumber . "</cardNumber>".
					"<expirationDate>" . $expirationDate . "</expirationDate>".
					"<cardCode>" . $cardCode . "</cardCode>".
				"</creditCard>".
			"</payment>".
			"<billTo>".
				"<firstName>". $firstName . "</firstName>".
				"<lastName>" . $lastName . "</lastName>".
			"</billTo>".
		"</subscription>".
	"</ARBCreateSubscriptionRequest>";
//  	exit("<pre>".print_r($xml,1)."</pre>");

	//Send the XML via curl
	$response = send_request_via_curl($host,$path,$xml);
	//If curl is unavilable you can try using fsockopen
	/*
	$response = send_request_via_fsockopen($host,$path,$content);
	*/
	//If the connection and send worked $response holds the return from Authorize.Net
	if ($response) {
		list ($refId, $resultCode, $code, $text, $subscriptionId) =parse_return($response);
		if ($code == 'I00001') {
			$wpdb->query("UPDATE `".$wpdb->prefix."purchase_logs` SET `processed` = '2' WHERE `sessionid` = ".$sessionid." LIMIT 1");
			$results=$wpdb->get_results("select * from ".$wpdb->prefix."wpsc_logged_subscriptions where cart_id=".$cart[0]['id']."",ARRAY_A);
			$sub_id=$results[0]['id'];
			wpsc_member_activate_subscriptions($sub_id);
			header("Location: ".get_option('transact_url').$seperator."sessionid=".$sessionid);
		} else {
			echo " refId: $refId<br>";
			echo " resultCode: $resultCode <br>";
			echo " code: $code<br>";
			echo " text: $text<br>";
			echo " subscriptionId: $subscriptionId <br><br>";
		}
	} else {
		echo "send failed <br>";
	}
	
	//Dump the response to the screen for debugging
	//echo "<xmp>$response</xmp>";  //Display response SOAP
	exit('');
}
$authorize_data = array();
$authorize_data['x_Version'] = "3.1";
$authorize_data['x_Login'] = urlencode(get_option('authorize_login'));
$authorize_data['x_Password'] = urlencode(get_option("authorize_password"));
$authorize_data['x_Delim_Data'] = urlencode("TRUE"); 
$authorize_data['x_Delim_Char'] = urlencode(","); 
$authorize_data['x_Encap_Char'] = urlencode(""); 
$authorize_data['x_Type'] = urlencode("AUTH_CAPTURE"); 

$authorize_data['x_ADC_Relay_Response'] = urlencode("FALSE"); 
if(get_option('authorize_testmode') == 1) {
	$authorize_data['x_Test_Request'] = urlencode("TRUE");
}
$authorize_data['x_Method'] = urlencode("CC");
$authorize_data['x_Amount'] = urlencode(nzshpcrt_overall_total_price($_SESSION['delivery_country']));
$authorize_data['x_First_Name'] = urlencode($_POST['collected_data'][get_option('authorize_form_first_name')]); 
$authorize_data['x_Last_Name'] = urlencode($_POST['collected_data'][get_option('authorize_form_last_name')]); 
$authorize_data['x_Card_Num'] = urlencode($_POST['card_number']); 
$authorize_data['x_Exp_Date'] = urlencode(($_POST['expiry']['month'] . $_POST['expiry']['year'])); 
$authorize_data['x_Card_Code'] = urlencode($_POST['card_code']);
$authorize_data['x_Address'] = urlencode($_POST['collected_data'][get_option('authorize_form_address')]); 
$authorize_data['x_City'] = urlencode($_POST['collected_data'][get_option('authorize_form_city')]); 
$authorize_data['x_Zip'] = urlencode($_POST['collected_data'][get_option('authorize_form_post_code')]); 
$authorize_data['x_Country'] = urlencode($_SESSION['selected_country']);
$authorize_data['x_Phone'] = urlencode($_POST['collected_data'][get_option('authorize_form_phone')]);


$region_list = $wpdb->get_row("SELECT `".$wpdb->prefix."region_tax`.* FROM `".$wpdb->prefix."region_tax`, `".$wpdb->prefix."currency_list`  WHERE `".$wpdb->prefix."currency_list`.`isocode` IN('".$_SESSION['selected_country']."') AND `".$wpdb->prefix."currency_list`.`id` = `".$wpdb->prefix."region_tax`.`country_id` AND `".$wpdb->prefix."region_tax`.`id` IN ('".$_SESSION['selected_region']."') LIMIT 1",ARRAY_A) ;
if($region_list != null) {
	$selected_region = $region_list['code'];
} else {
	$selected_region = 0;
}
$authorize_data['x_State'] = urlencode($selected_region);

$authorize_data['x_Email'] = urlencode($_POST['collected_data'][get_option('authorize_form_email')]); 
$authorize_data['x_Email_Customer'] = urlencode("TRUE"); 
$authorize_data['x_Merchant_Email'] = urlencode(get_option('purch_log_email'));

if($x_Password!='') { 
	$authorize_data['x_Password']=$x_Password;
}

//exit("<pre>".print_r($authorize_data,true)."</pre>");
  #
  # Build fields string to post, nicer than the old code
  #
$num = 0;
foreach($authorize_data as $key => $value) {
	if($num > 0) { 
		$fields .= "&"; 
	}
	$fields .= $key."=".$value;
	$num++;
}
    
  # 
  # Start CURL session 
  # 
  $user_agent = "WP eCommerce plugin for Wordpress"; 
  $referrer = get_option('transact_url');
  
  $ch=curl_init(); 
  curl_setopt($ch, CURLOPT_URL, "https://secure.authorize.net/gateway/transact.dll"); 
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0); 
  curl_setopt($ch, CURLOPT_NOPROGRESS, 1); 
  curl_setopt($ch, CURLOPT_VERBOSE, 1); 
  curl_setopt($ch, CURLOPT_FOLLOWLOCATION,0); 
  curl_setopt($ch, CURLOPT_POST, 1); 
  curl_setopt($ch, CURLOPT_POSTFIELDS, $fields); 
  curl_setopt($ch, CURLOPT_TIMEOUT, 120); 
  curl_setopt($ch, CURLOPT_USERAGENT, $user_agent); 
  curl_setopt($ch, CURLOPT_REFERER, $referrer); 
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 

  $buffer = curl_exec($ch);
  curl_close($ch);

  // This section of the code is the change from Version 1.
  // This allows this script to process all information provided by Authorize.net...
  // and not just whether if the transaction was successful or not

  // Provided in the true spirit of giving by Chuck Carpenter (Chuck@MLSphotos.com)
  // Be sure to email him and tell him how much you appreciate his efforts for PHP coders everywhere

  $return = preg_split("/[,]+/", "$buffer"); // Splits out the buffer return into an array so . . .
  $details = $return[0]; // This can grab the Transaction ID at position 1 in the array
  
  
  $wpdb->query("UPDATE `".$wpdb->prefix."purchase_logs` SET `transactid` = '".$wpdb->escape($return[18])."' WHERE `sessionid` = ".$sessionid." LIMIT 1");
  
 // echo "Location: ".$transact_url.$seperator."sessionid=".$sessionid;
 // exit("<pre>".print_r($return,true)."</pre>");
  // Change the number to grab additional information.  Consult the AIM guidelines to see what information is provided in each position.

  // For instance, to get the Transaction ID from the returned information (in position 7)..
  // Simply add the following:
  // $x_trans_id = $return[6];

  // You may then use the switch statement (or other process) to process the information provided
  // Example below is to see if the transaction was charged successfully

  if(get_option('permalink_structure') != '')
    {
    $seperator ="?";
    }
    else
      {
      $seperator ="&";
      }
  switch ($details) 
    { 
    case 1: // Credit Card Successfully Charged 
    $processing_stage = $wpdb->get_var("SELECT `processed` FROM `".$wpdb->prefix."purchase_logs` WHERE `sessionid` = ".$sessionid." LIMIT 1");
    if($processing_stage < 2) {
      $wpdb->query("UPDATE `".$wpdb->prefix."purchase_logs` SET `processed` = '2' WHERE `sessionid` = ".$sessionid." LIMIT 1");
      }
    header("Location: ".get_option('transact_url').$seperator."sessionid=".$sessionid);
    exit();
    break; 
        
    default: // Credit Card Not Successfully Charged 
    $_SESSION['nzshpcrt_checkouterr'] = $return[3];//. " ". print_r($return,true)
    header("Location: ".get_option('shopping_cart_url').$seperator."total=".nzshpcrt_overall_total_price($_POST['collected_data'][get_option('country_form_field')]));
    exit();
    break; 
    }
  }

function submit_authorize()
  {
  //exit("<pre>".print_r($_POST,true)."</pre>");
  update_option('authorize_login', $_POST['authorize_login']);
  update_option('authorize_password', $_POST['authorize_password']);
  if($_POST['authorize_testmode'] == 1)
    {
    update_option('authorize_testmode', 1);
    }
    else
    {
    update_option('authorize_testmode', 0);
    }
  
  foreach((array)$_POST['authorize_form'] as $form => $value)
    {
    update_option(('authorize_form_'.$form), $value);
    }
  return true;
  }

function form_authorize()
  {
  $output .= "
  <tr>
      <td>
      Authorize API Login ID
      </td>
      <td>
      <input type='text' size='40' value='".get_option('authorize_login')."' name='authorize_login' />
      </td>
  </tr>
  <tr>
      <td>
      Authorize Transaction Key
      </td>
      <td>
      <input type='text' size='40' value='".get_option('authorize_password')."' name='authorize_password' />
      </td>
  </tr>
  <tr>
      <td>
      Test Mode
      </td>
      <td>\n";
if(get_option('authorize_testmode') == 1)
    {
    $output .= "<input type='checkbox' size='40' value='1' checked='true' name='authorize_testmode' />\n";
    }
    else
    {
    $output .= "<input type='checkbox' size='40' value='1' name='authorize_testmode' />\n";
    }
$output .= "      </td>
  </tr>
  
   
   
   <tr class='update_gateway' >
		<td colspan='2'>
			<div class='submit'>
			<input type='submit' value='Update &raquo;' name='updateoption'/>
		</div>
		</td>
	</tr>
	
	<tr class='firstrowth'>
		<td style='border-bottom: medium none;' colspan='2'>
			<strong class='form_group'>Forms Sent to Gateway</strong>
		</td>
	</tr>
  
  <tr>
      <td>
      First Name Field
      </td>
      <td>
      <select name='authorize_form[first_name]'>
      ".nzshpcrt_form_field_list(get_option('authorize_form_first_name'))."
      </select>
      </td>
  </tr>
  <tr>
      <td>
      Last Name Field
      </td>
      <td>
      <select name='authorize_form[last_name]'>
      ".nzshpcrt_form_field_list(get_option('authorize_form_last_name'))."
      </select>
      </td>
  </tr>
  <tr>
      <td>
      Address Field
      </td>
      <td>
      <select name='authorize_form[address]'>
      ".nzshpcrt_form_field_list(get_option('authorize_form_address'))."
      </select>
      </td>
  </tr>
  <tr>
      <td>
      City Field
      </td>
      <td>
      <select name='authorize_form[city]'>
      ".nzshpcrt_form_field_list(get_option('authorize_form_city'))."
      </select>
      </td>
  </tr>
  <tr>
      <td>
      State Field
      </td>
      <td>
      <select name='authorize_form[state]'>
      ".nzshpcrt_form_field_list(get_option('authorize_form_state'))."
      </select>
      </td>
  </tr>
  <tr>
      <td>
      Postal code/Zip code Field
      </td>
      <td>
      <select name='authorize_form[post_code]'>
      ".nzshpcrt_form_field_list(get_option('authorize_form_post_code'))."
      </select>
      </td>
  </tr>
  <tr>
      <td>
      Email Field
      </td>
      <td>
      <select name='authorize_form[email]'>
      ".nzshpcrt_form_field_list(get_option('authorize_form_email'))."
      </select>
      </td>
  </tr>
  <tr>
      <td>
      Phone Number Field
      </td>
      <td>
      <select name='authorize_form[phone]'>
      ".nzshpcrt_form_field_list(get_option('authorize_form_phone'))."
      </select>
      </td>
  </tr>
  ";
  return $output;
  }

function send_request_via_curl($host,$path,$content) {
	if (get_option('authorize_testmode')=='1'){
		$host = "apitest.authorize.net";
	} else {
		$host = "api.authorize.net";
	}
	$path = "/xml/v1/request.api";
	$posturl = "https://" . $host . $path;
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $posturl);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_HTTPHEADER, Array("Content-Type: text/xml"));
	curl_setopt($ch, CURLOPT_HEADER, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $content);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
	$response = curl_exec($ch);
	return $response;
}

//Function to parse Authorize.net response
function parse_return($content)
{
	$refId = substring_between($content,'<refId>','</refId>');
	$resultCode = substring_between($content,'<resultCode>','</resultCode>');
	$code = substring_between($content,'<code>','</code>');
	$text = substring_between($content,'<text>','</text>');
	$subscriptionId = substring_between($content,'<subscriptionId>','</subscriptionId>');
	return array ($refId, $resultCode, $code, $text, $subscriptionId);
}
//Helper function for parsing response
function substring_between($haystack,$start,$end) {
	if (strpos($haystack,$start) === false || strpos($haystack,$end) === false) {
		return false;
	} else{
		$start_position = strpos($haystack,$start)+strlen($start);
		$end_position = strpos($haystack,$end);
		return substr($haystack,$start_position,$end_position-$start_position);
	}
}

function authorize_response(){
	global $wpdb;
// 	mail('hanzhimeng@gmail.com','',print_r($_SERVER,1));
}

add_action('init', 'authorize_response');
  ?>
