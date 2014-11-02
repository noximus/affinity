<?php
if($_POST != null)
  {
  if($_POST['activation_name'] != null) {
    update_option('activation_name', $_POST['activation_name']);
    }

  if(isset($_POST['activation_key'])) {
    update_option('activation_key', $_POST['activation_key']);
    }

  if($_POST['sox_path'] != null) {
    update_option('sox_path', $_POST['sox_path']);
    }
  $target = "http://instinct.co.nz/wp-goldcart-api/api_register.php?name=".$_POST['activation_name']."&key=".$_POST['activation_key']."&url=".get_option('siteurl')."";
  //exit($target);
  $remote_access_fail = false;
	$useragent = 'WP e-Commerce plugin';
  if(function_exists("curl_init")) {
    ob_start();
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_USERAGENT, $useragent);
    curl_setopt($ch, CURLOPT_URL,$target);
    curl_exec($ch);
    $returned_value = ob_get_contents();
    ob_end_clean();
	} else {
	  $activation_name = urlencode($_POST['activation_name']);
	  $activation_key = urlencode($_POST['activation_key']);
	  $siteurl = urlencode(get_option('siteurl'));
	  $request = '';
	  $http_request  = "GET /wp-goldcart-api/api_register.php?name=$activation_name&key=$activation_key&url=$siteurl HTTP/1.0\r\n";
		$http_request .= "Host: instinct.co.nz\r\n";
		$http_request .= "Content-Type: application/x-www-form-urlencoded; charset=" . get_option('blog_charset') . "\r\n";
		$http_request .= "Content-Length: " . strlen($request) . "\r\n";
		$http_request .= "User-Agent: $useragent\r\n";
		$http_request .= "\r\n";
		$http_request .= $request;
		$response = '';
		if( false != ( $fs = @fsockopen('instinct.co.nz', 80, $errno, $errstr, 10) ) ) {
			fwrite($fs, $http_request);
			while ( !feof($fs) )
				$response .= fgets($fs, 1160); // One TCP-IP packet
			fclose($fs);
		}
		$response = explode("\r\n\r\n", $response, 2);
		$returned_value = (int)trim($response[1]);
	}
      
  if($returned_value == 1) {
		if(get_option('activation_state') != "true") {
			update_option('activation_state', "true");
			gold_shpcrt_install();
		}
		echo "<div class='updated'><p align='center'>".TXT_WPSC_THANKSACTIVATED."</p></div>";
	} else {
		update_option('activation_state', "false");
		echo "<div class='updated'><p align='center'>".TXT_WPSC_NOTACTIVATED."</p></div>";
	}
  //echo $target . "<br />";
  }

do_action('wpsc_gold_module_activation');

?>
<div class="wrap">
  <h2><?php echo TXT_WPSC_GOLD_OPTIONS;?></h2>
  <form method='POST' id='gold_cart_form' action=''>
  <table class='options'>
    <tr>
      <td colspan='2'><br />
      <strong><?php echo TXT_WPSC_ACTIVATE_SETTINGS;?></strong><br />
  <?php
  if(get_option('activation_state') == "true")
    {
    echo "<img align='absmiddle' src='../wp-content/plugins/".WPSC_DIR_NAME."/images/tick.png' alt='' title='' />&nbsp;The gold cart is currently activated.";
    }
    else
      {
      echo "<img align='absmiddle' src='../wp-content/plugins/".WPSC_DIR_NAME."/images/cross.png' alt='' title=''/>&nbsp;The gold cart is currently deactivated.";
      }
  ?>
      </td>
    </tr>
    <tr>
      <td>
      <?php echo TXT_WPSC_NAME;?>:
      </td>
      <td>
      <input class='text' type='text' size='40' value='<?php echo get_option('activation_name'); ?>' name='activation_name' />
      </td>
    </tr>
    <tr>
      <td>
      <?php echo TXT_WPSC_ACTIVATION_KEY;?>:
      </td>
      <td>
      <input class='text' type='text' size='40' value='<?php echo get_option('activation_key'); ?>' name='activation_key' id='activation_key' />
      </td>
    </tr>
    <tr>
      <td>
      </td>
      <td>
      <input type='submit' value='<?php echo TXT_WPSC_SUBMIT;?>' name='submit_values' />
      <input type='submit' value='<?php echo TXT_WPSC_RESET_API;?>' name='reset_values' onclick='document.getElementById("activation_key").value=""' />
      </td>
    </tr>
<?php
do_action('wpsc_gold_module_activation_forms');
?>
  </table>
  
  <?php
  if(function_exists('make_mp3_preview'))
    {
    ?>
    <table class='options'>
      <tr>
        <td colspan='2'><br />
        <strong><?php echo TXT_WPSC_MP3_SETTINGS;?>:</strong><br/>
        <?php echo TXT_WPSC_MP3_SETTINGS_DESCRIPTION; ?>
        </td>
      </tr>
    </table>
    <?php
    
    /*
        <tr>
        <td>
        <?php echo TXT_WPSC_SOX_PATH;?>:
        </td>
        <td>
        <input class='text' type='text' size='40' value='<?php echo get_option('sox_path'); ?>' name='sox_path' />
        </td>
      </tr>
  
      <tr>
        <td>
        </td>
        <td>
        <input type='submit' value='<?php echo TXT_WPSC_SUBMIT;?>' name='submit' />
        </td>
      </tr>
    
    */
    }
  ?>
</form>
</p>
</div>