<?php
/*
Plugin Name: WP-SWFObject
Plugin URI: http://blog.unijimpe.net/wp-swfobject/
Description: Allow insert Flash Movies into WordPress blog using SWFObject library. For use this plugin: [SWF]pathtofile, width, height[/SWF].
Version: 2.1
Author: Jim Penaloza Calixto 
Author URI: http://blog.unijimpe.net
*/

// Define Global params
$wpswf_version	= "2.1";										// version of plugin 
$wpswf_random	= substr(md5(uniqid(rand(), true)),0,4);		// create unique id for divs
$wpswf_number	= 0; 											// number of swf into page
$wpswf_params	= array("swf_version"		=>	"9.0.0",		// array of config options
						"swf_bgcolor"		=>	"#FFFFFF",
						"swf_wmode"			=>	"window",
						"swf_menu"			=>	"false",
						"swf_quality"		=>	"high",
						"swf_fullscreen"	=>	"false",
						"swf_align"			=>	"none",
						"swf_message"		=>	"This movie requires Flash Player 9",
						"swf_file"			=>	"v20int"
						);
$wpswf_files	= array(
						"v15int"			=>	get_settings('siteurl')."/wp-content/plugins/wp-swfobject/1.5/swfobject.js",
						"v20int"			=>	get_settings('siteurl')."/wp-content/plugins/wp-swfobject/2.0/swfobject.js",
						"v20ext"			=>	"http://ajax.googleapis.com/ajax/libs/swfobject/2.1/swfobject.js"
						);

// Define General Options
add_option("swf_version",	$wpswf_params["swf_version"], 	'Version of Flash Player.');
add_option("swf_bgcolor", 	$wpswf_params["swf_bgcolor"], 	'Background Color for Flash Movie.');
add_option("swf_wmode", 	$wpswf_params["swf_wmode"], 	'WMode for Flash Movie.');
add_option("swf_menu", 		$wpswf_params["swf_menu"], 		'Option for Activate menu for Flash Movie.');
add_option("swf_quality", 	$wpswf_params["swf_quality"], 	'Default quality for Flash Movie.');
add_option("swf_fullscreen",$wpswf_params["swf_fullscreen"],'If Allow Fullscreen mode for Flash Movie.');
add_option("swf_align", 	$wpswf_params["swf_align"], 	'Align for Flash Movie.');
add_option("swf_message", 	$wpswf_params["swf_message"], 	'Message for missing player.');
add_option("swf_file", 		$wpswf_params["swf_file"], 		'File version of SWFObject.');

function wpswfConfig() {
	// get config options into array var
	global $wpswf_params;
    static $config;
    if ( empty($config) ) {
		foreach( $wpswf_params as $option => $default) {
			$config[$option] = get_option($option);
		}
    }
    return $config;
}
function wpswfParse($text) {
	// regexp for find swfs
    return preg_replace_callback('|\[swf\](.+?),\s*(\d+)\s*,\s*(\d+)\s*(,(.+?))?\[/swf\]|i', 'wpswfObject', $text);
}
function wpswfObject($match) {
    global $wpswf_random, $wpswf_number;
	$wpswf_config = wpswfConfig();
	$wpswf_number++;
	
    if (is_feed() || $doing_rss) {
		// for feed insert <object>
		$writeswf.= "<object type=\"application/x-shockwave-flash\" width=\"".$match[2]."\" height=\"".$match[3]."\">\n";
		$writeswf.= "<param name=\"movie\" value=\"".$match[1]."\" />\n";
		if ($match[4] != "") {
			$writeswf.= "<param name=\"flashvars\" value=\"".trim(substr($match[4],1))."\" />\n";
		}
		$writeswf.= "<embed type=\"application/x-shockwave-flash\" width=\"".$match[2]."\" height=\"".$match[3]."\" ";
		$writeswf.= "src=\"".$match[1]."\" ";
		if ($match[4] != "") {
			$writeswf.= "flashvars=\"".trim(substr($match[4],1))."\" ";
		}
		$writeswf.= ">\n";
		$writeswf.= "</object>\n";
	} else {
		// for web insert SWFObject
		switch ($wpswf_config['swf_align']) {
			case "center";
				$tmpalign = "margin-left: auto; margin-right: auto; ";
				break;
			case "left";
				$tmpalign = "margin-right: auto; ";
				break;
			case "right";
				$tmpalign = "margin-left: auto; ";
				break;
			default:
				$tmpalign = "";
				break;
		}		
		$writeswf.= "<div style=\"text-align: center; width:".$match[2]."px; height:".$match[3]."px; line-height:".$match[3]."px; ".$tmpalign."background: ".$wpswf_config['swf_bgcolor'].";\"><div id=\"swf".$wpswf_random.$wpswf_number."\">".$wpswf_config['swf_message']."</div></div>\n";
		
		if ($wpswf_config['swf_file'] == "v15int") {
			// Use SWFObject 1.5 code
			$writeswf.= "<script type=\"text/javascript\">\n";
			$writeswf.= "\tvar vswf = new SWFObject(\"".$match[1]."\", \"id".$wpswf_number."\", \"".$match[2]."\", \"".$match[3]."\", \"".$wpswf_config['swf_version']."\", \"".$wpswf_config['swf_bgcolor']."\");\n";
			$writeswf.= "\tvswf.addParam(\"wmode\", \"".$wpswf_config['swf_wmode']."\");\n";
			$writeswf.= "\tvswf.addParam(\"menu\", \"".$wpswf_config['swf_menu']."\");\n";
			$writeswf.= "\tvswf.addParam(\"quality\", \"".$wpswf_config['swf_quality']."\");\n";
			if ($swf_config['swf_fullscreen'] == "true") {
				$writeswf.= "\tvswf.addParam(\"allowFullScreen\", \"".$wpswf_config['swf_fullscreen']."\");\n";
			}
			if ($match[4] != "") {
				$aleParam = ereg_replace("&amp;", "&", $match[4]);
				parse_str(trim(substr($aleParam,1)), $params);
				foreach ($params as $param => $value) {
					$writeswf.= "\tvswf.addVariable(\"".$param."\", \"".$value."\");\n";
				}
			}
			$writeswf.= "\tvswf.write(\"swf".$wpswf_random.$wpswf_number."\");\n";
			$writeswf.= "</script>\n";
		} else {
			// Use SWFObject 2.0 code
			$wpswf_params = "wmode: \"".$wpswf_config['swf_wmode']."\", ";
			$wpswf_params.= "menu: \"".$wpswf_config['swf_menu']."\", ";
			$wpswf_params.= "quality: \"".$wpswf_config['swf_quality']."\", ";
			$wpswf_params.= "bgcolor: \"".$wpswf_config['swf_bgcolor']."\"";
			if ($wpswf_config['swf_fullscreen'] == "true") {
				$wpswf_params.= ", allowFullScreen: \"".$wpswf_config['swf_fullscreen']."\"";
			}
			
			$wpswf_fvars = "";
			if ($match[4] != "") {
				$aleParam = ereg_replace("&amp;", "&", $match[4]);
				parse_str(trim(substr($aleParam,1)), $params);
				foreach ($params as $param => $value) {
					$wpswf_fvars .= ", ". $param . ": \"".$value."\"";
				}
			}
			$writeswf.= "<script type=\"text/javascript\">\n";
			$writeswf.= "\tswfobject.embedSWF(\"".$match[1]."\", \"swf".$wpswf_random.$wpswf_number."\", \"".$match[2]."\", \"".$match[3]."\", \"".$wpswf_config['swf_version']."\", \"\", {".substr($wpswf_fvars, 2)."}, {".$wpswf_params."}, {});\n";
			$writeswf.= "</script>\n";
		}
	}
	return $writeswf;
}
function wp_swfobject_echo($swffile, $swfwidth, $swfheigth, $swfvars = "") {
    echo wpswfObject( array( null, $swffile, $swfwidth, $swfheigth, "&".$swfvars) );
}
function wpswfOptionsPage() {
	// update general options
	global $wpswf_version, $wpswf_params;
	if (isset($_POST['swf_update'])) {
		check_admin_referer();
		foreach( $wpswf_params as $option => $default ) {
			$swf_param = trim($_POST[$option]);
			if ($swf_param == "") {
				$swf_param = $default;
			}
			update_option($option, $swf_param);
		}
		echo "<div class='updated'><p><strong>WP-SWFObject options updated</strong></p></div>";
	}
	$wpswf_config = wpswfConfig();
?>
		<form method="post" action="options-general.php?page=wp-swfobject.php">
		<div class="wrap">
			<h2>WP-SWFObject <sup style='color:#D54E21;font-size:12px;'><?php echo $wpswf_version; ?></sup></h2>
				<table class="form-table">
					<tr>
						<th scope="row" valign="top">
							<label for="swf_file">SWFObject Version:</label>
						</th>
						<td>
							<select name="swf_file" id="swf_file">
								<option value="v15int" <?php if ($wpswf_config["swf_file"] == "v16int") { echo "selected=\"selected\""; } ?>>SWFObject 1.5</option>
								<option value="v20int" <?php if ($wpswf_config["swf_file"] == "v20int") { echo "selected=\"selected\""; } ?>>SWFObject 2.0</option>
								<option value="v20ext" <?php if ($wpswf_config["swf_file"] == "v20ext") { echo "selected=\"selected\""; } ?>>SWFObject 2.0 (from Google Library)</option>
							</select>
							Select version of SWFObject.
						</td>
					</tr>
					<tr>
						<th scope="row" valign="top">
							<label for="swf_version">Flash Player Version:</label>
						</th>
						<td>
							<input type="text" size="16" maxlength="12" name="swf_version" id="swf_version" value="<?php echo $wpswf_config["swf_version"]; ?>" />
							Enter number of flash version required for flash player.
						</td>
					</tr>
					<tr>
						<th scope="row" valign="top">
							<label for="swf_bgcolor">Background Color:</label>
						</th>
						<td>
							<input type="text" size="16" maxlength="7" name="swf_bgcolor" id="swf_bgcolor" value="<?php echo $wpswf_config["swf_bgcolor"]; ?>" />
							Enter HEX number for background color for flash movie.
						</td>
					</tr>
					<tr>
						<th scope="row" valign="top">
							<label for="swf_wmode">Window Mode:</label>
						</th>
						<td>
							<select name="swf_mode" id="swf_mode">
								<option value="window" <?php if ($wpswf_config["swf_wmode"] == "window") { echo "selected=\"selected\""; } ?>>Window</option>
								<option value="opaque" <?php if ($wpswf_config["swf_wmode"] == "opaque") { echo "selected=\"selected\""; } ?>>Opaque</option>
								<option value="transparent" <?php if ($wpswf_config["swf_wmode"] == "transparent") { echo "selected=\"selected\""; } ?>>Transparent</option>
							</select>
							Select wmode for movie, by defaul is <strong>window</strong>.
						</td>
					</tr>
					<tr>
						<th scope="row" valign="top">
							<label for="swf_menu">Show Menu:</label>
						</th>
						<td>
							<select name="swf_menu" id="swf_menu">
								<option value="true" <?php if ($wpswf_config["swf_menu"] == "true") { echo "selected=\"selected\""; } ?>>True</option>
								<option value="false" <?php if ($wpswf_config["swf_menu"] == "false") { echo "selected=\"selected\""; } ?>>False</option>
							</select>
							Select option for show/hide menu.
						</td>
					</tr>
					<tr>
						<th scope="row" valign="top">
							<label for="swf_quality">Quality Movie:</label>
						</th>
						<td>
							<select name="swf_quality" id="swf_quality">
								<option value="low" <?php if ($wpswf_config["swf_quality"] == "low") { echo "selected=\"selected\""; } ?>>Low</option>
								<option value="autolow" <?php if ($wpswf_config["swf_quality"] == "autolow") { echo "selected=\"selected\""; } ?>>Autolow</option>
								<option value="autohigh" <?php if ($wpswf_config["swf_quality"] == "autohigh") { echo "selected=\"selected\""; } ?>>Autohigh</option>
								<option value="medium" <?php if ($wpswf_config["swf_quality"] == "medium") { echo "selected=\"selected\""; } ?>>Medium</option>
								<option value="high" <?php if ($wpswf_config["swf_quality"] == "high") { echo "selected=\"selected\""; } ?>>High</option>
								<option value="best" <?php if ($wpswf_config["swf_quality"] == "best") { echo "selected=\"selected\""; } ?>>Best</option>
							</select>
							Select quality for flash movie, by default is <strong>hight</strong>.
						</td>
					</tr>
					<tr>
						<th scope="row" valign="top">
							<label for="swf_fullscreen">Allow Fullscreen:</label>
						</th>
						<td>
							<select name="swf_fullscreen" id="swf_fullscreen">
								<option value="true" <?php if ($wpswf_config["swf_fullscreen"] == "true") { echo "selected=\"selected\""; } ?>>True</option>
								<option value="false" <?php if ($wpswf_config["swf_fullscreen"] == "false") { echo "selected=\"selected\""; } ?>>False</option>
							</select>
							Allow Fullscreen (You must have version >= 9,0,28,0 of Flash Player).
						</td>
					</tr>
					<tr>
						<th scope="row" valign="top">
							<label for="swf_align">Align:</label>
						</th>
						<td>
							<select name="swf_align" id="swf_align">
								<option value="none" <?php if ($wpswf_config["swf_align"] == "none") { echo "selected=\"selected\""; } ?>>None</option>
								<option value="left" <?php if ($wpswf_config["swf_align"] == "left") { echo "selected=\"selected\""; } ?>>Left</option>
								<option value="center" <?php if ($wpswf_config["swf_align"] == "center") { echo "selected=\"selected\""; } ?>>Center</option>
								<option value="right" <?php if ($wpswf_config["swf_align"] == "right") { echo "selected=\"selected\""; } ?>>Right</option>
							</select>
							Align for Flash Movies into Post.
						</td>
					</tr>
					<tr>
						<th scope="row" valign="top">
							<label for="swf_message">Message Require Flash:</label>
						</th>
						<td>
							<input type="text" size="50" name="swf_message" id="swf_message" value="<?php echo $wpswf_config["swf_message"]; ?>" />
							Enter message for warning missing player.
						</td>
					</tr>
					</table>
					<p class="submit">
					  <input name="swf_update" value="Save Changes" type="submit" />
					</p>
					<table>
					<tr>
						<th width="30%" valign="top" style="padding-top: 10px; text-align:left;" colspan="2">
							More Information and Support
						</th>
					</tr>
					<tr>
						<td colspan="2">
						  <p>Check our links for updates and comment there if you have any problems / questions / suggestions. </p>
					      <ul>
					        <li><a href="http://blog.unijimpe.net/wp-swfobject/">Plugin Home Page</a></li>
			                <li><a href="http://forum.unijimpe.net/?CategoryID=4">Plugin Forum Support</a> </li>
			                <li><a href="http://blog.unijimpe.net/">Author Home Page</a></li>
				            <li><a href="http://code.google.com/p/swfobject/">SWFObject 2.0 Home Page</a> </li>
				        </ul></td>
				  </tr>
				</table>
			
		</div>
		</form>
<?php
}
function wpswfAddMenu() {
	// add menu options
	add_options_page('WP-SWFObject Options', 'WP-SWFObject', 8, basename(__FILE__), 'wpswfOptionsPage');
}
function wpswfAddheader() {
	// Add SWFObject to header
	global $wpswf_version, $wpswf_files;
	echo "\n<!-- WP-SWFObject ".$wpswf_version." by unijimpe -->";
	echo "\n<script src=\"".$wpswf_files[get_option('swf_file')]."\" type=\"text/javascript\"></script>\n";
}

add_filter('the_content', 'wpswfParse');
add_action('wp_head', 'wpswfAddheader');
add_action('admin_menu', 'wpswfAddMenu');
?>