<?php
/*
Plugin Name: Sidebar Login
Plugin URI: http://wordpress.org/extend/plugins/sidebar-login/
Description: Adds a sidebar widget to let users login
Version: 2.1.7
Author: Mike Jolley
Author URI: http://blue-anvil.com
*/

load_plugin_textdomain('sblogin','wp-content/plugins/sidebar-login/');

function wp_sidebarlogin_menu() {
	add_management_page(__('Sidebar Login','sblogin'), __('Sidebar Login','sblogin'), 6,'Sidebar Login', 'wp_sidebarlogin_admin');
}

function wp_sidebarlogin_magic() { 
	function stripit($in) {
		if (!is_array($in)) $out = stripslashes($in); else $out = $in;
		return $out;
	}
	if (get_magic_quotes_gpc()){ 
	 $_GET = array_map('stripit', $_GET); 
	 $_POST = array_map('stripit', $_POST); 
	}
	return;
}

if (!function_exists(is_ssl)) :
function is_ssl() {
return ( isset($_SERVER['HTTPS']) && 'on' == strtolower($_SERVER['HTTPS']) ) ? true : false;
}
endif;

function wp_sidebarlogin_admin(){
	// Update options
	if ($_POST) {
		wp_sidebarlogin_magic();
		update_option('sidebarlogin_login_redirect', $_POST['sidebarlogin_login_redirect']);
		update_option('sidebarlogin_logout_redirect', $_POST['sidebarlogin_logout_redirect']);
		update_option('sidebarlogin_register_link', $_POST['sidebarlogin_register_link']);
		update_option('sidebarlogin_forgotton_link', $_POST['sidebarlogin_forgotton_link']);
		update_option('sidebarlogin_logged_in_links', $_POST['sidebarlogin_logged_in_links']);
		echo '<div id="message"class="updated fade">';	
		_e('<p>Changes saved</p>',"sblogin");			
		echo '</div>';
	}
	// Get options
	$sidebarlogin_login_redirect = get_option('sidebarlogin_login_redirect');
	$sidebarlogin_logout_redirect = get_option('sidebarlogin_logout_redirect');
	$sidebarlogin_register_link = get_option('sidebarlogin_register_link');
	$sidebarlogin_forgotton_link = get_option('sidebarlogin_forgotton_link');
	$sidebarlogin_logged_in_links = get_option('sidebarlogin_logged_in_links');
	?>
	<div class="wrap alternate">
        <h2><?php _e('Sidebar Login',"sblogin"); ?></h2>
        <br class="a_break" style="clear: both;"/>
        <form action="?page=Sidebar Login" method="post">
            <table class="niceblue form-table">
                <tr>
                    <th scope="col"><?php _e('Login redirect URL',"wp-download_monitor"); ?>:</th>
                    <td><input type="text" name="sidebarlogin_login_redirect" value="<?php echo $sidebarlogin_login_redirect; ?>" /> <span class="setting-description"><?php _e('Url to redirect the user to after login. Leave blank to use their current page.','sblogin'); ?></span></td>
                </tr>
                <tr>
                    <th scope="col"><?php _e('Logout redirect URL',"wp-download_monitor"); ?>:</th>
                    <td><input type="text" name="sidebarlogin_logout_redirect" value="<?php echo $sidebarlogin_logout_redirect; ?>" /> <span class="setting-description"><?php _e('Url to redirect the user to after logout. Leave blank to use their current page.','sblogin'); ?></span></td>
                </tr>
                <tr>
                    <th scope="col"><?php _e('Show Register Link',"wp-download_monitor"); ?>:</th>
                    <td><select name="sidebarlogin_register_link">
                    	<option <?php if ($sidebarlogin_register_link=='yes') echo 'selected="selected"'; ?> value="yes"><?php _e('Yes','sblogin'); ?></option>
                    	<option <?php if ($sidebarlogin_register_link=='no') echo 'selected="selected"'; ?> value="no"><?php _e('No','sblogin'); ?></option>
                    </select> <span class="setting-description"><?php _e('User registrations must also be turned on for this to work (\'Anyone can register\' checkbox in settings).','sblogin'); ?></span></td>
                </tr>
                <tr>
                    <th scope="col"><?php _e('Show Lost Password Link',"wp-download_monitor"); ?>:</th>
                    <td><select name="sidebarlogin_forgotton_link">
                    	<option <?php if ($sidebarlogin_forgotton_link=='yes') echo 'selected="selected"'; ?> value="yes"><?php _e('Yes','sblogin'); ?></option>
                    	<option <?php if ($sidebarlogin_forgotton_link=='no') echo 'selected="selected"'; ?> value="no"><?php _e('No','sblogin'); ?></option>
                    </select></td>
                </tr>
                <tr>
                    <th scope="col"><?php _e('Logged in links',"wp-download_monitor"); ?>:</th>
                    <td><textarea name="sidebarlogin_logged_in_links" rows="3" cols="80" /><?php echo $sidebarlogin_logged_in_links; ?></textarea><br/><span class="setting-description"><?php _e('One link per line (e.g. <code>&lt;a href="http://Yoursite.com/wp-admin/"&gt;Dashboard&lt;/a&gt;</code>). Logout link will always show regardless.','sblogin'); ?></span></td>
                </tr>
            </table>
            <p class="submit"><input type="submit" value="<?php _e('Save Changes',"sblogin"); ?>" /></p>
        </form>
    </div>
    <?php
}

function sidebarlogin() {
	$args["before_widget"]="";
	$args["after_widget"]="";
	$args["before_title"]="<h2>";
	$args["after_title"]="</h2>";
	widget_wp_sidebarlogin($args);
}
function widget_wp_sidebarlogin($args) {
	
		extract($args);
				
		global $user_ID, $current_user;
		get_currentuserinfo();

		if ($user_ID != '') {
			// User is logged in
			echo $before_widget . $before_title . __("Welcome",'sblogin').' '.$current_user->display_name . $after_title;
			echo '<ul class="pagenav">';
			
			$links = get_option('sidebarlogin_logged_in_links');
			$links = explode("\n", $links);
			if (sizeof($links)>0)
			foreach ($links as $l) {
				echo '<li class="page_item">'.$l.'</li>';
			}
			echo '<li class="page_item"><a href="'.wp_sidebarlogin_current_url('logout').'">'.__('Logout').'</a></li>
				</ul>';
		} else {
			// User is NOT logged in!!!
			echo $before_widget . $before_title . __("Login") . $after_title;
			// Show any errors
			global $myerrors;
			$wp_error = new WP_Error();
			if ( !empty($myerrors) ) {
				$wp_error = $myerrors;
			}
			if ( $wp_error->get_error_code() ) {
				$errors = '';
				$messages = '';
				foreach ( $wp_error->get_error_codes() as $code ) {
					$severity = $wp_error->get_error_data($code);
					foreach ( $wp_error->get_error_messages($code) as $error ) {
						if ( 'message' == $severity )
							$messages .= '	' . $error . "<br />\n";
						else
							$errors .= '	' . $error . "<br />\n";
					}
				}
				if ( !empty($errors) )
					echo '<div id="login_error">' . apply_filters('login_errors', $errors) . "</div>\n";
				if ( !empty($messages) )
					echo '<p class="message">' . apply_filters('login_messages', $messages) . "</p>\n";
			}
			// login form
			echo '<form action="'.wp_sidebarlogin_current_url().'" method="post">';
			?>
			<p><label for="user_login"><?php _e('Username:','sblogin'); ?><br/><input name="log" value="<?php echo attribute_escape(stripslashes($_POST['log'])); ?>" class="mid" id="user_login" type="text" /></label></p>
			<p><label for="user_pass"><?php _e('Password:','sblogin'); ?><br/><input name="pwd" class="mid" id="user_pass" type="password" /></label></p>
			<p><label for="rememberme"><input name="rememberme" class="checkbox" id="rememberme" value="forever" type="checkbox" /> <?php _e('Remember me'); ?></label></p>
			<p class="submit"><input type="submit" name="wp-submit" id="wp-submit" value="<?php _e('Login','sblogin'); ?> &raquo;" />
			<input type="hidden" name="sidebarlogin_posted" value="1" />
			<input type="hidden" name="testcookie" value="1" /></p>
			</form>
			<?php 			
			// Output other links
			echo '<ul class="sidebarlogin_otherlinks">';		
			if (get_option('users_can_register') && get_option('sidebarlogin_register_link')=='yes') { 
				// MU FIX
				global $wpmu_version;
				if (empty($wpmu_version)) {
					?>
						<li><a href="<?php bloginfo('wpurl'); ?>/wp-login.php?action=register"><?php _e('Register','sblogin'); ?></a></li>
					<?php 
				} else {
					?>
						<li><a href="<?php bloginfo('wpurl'); ?>/wp-signup.php"><?php _e('Register','sblogin'); ?></a></li>
					<?php 
				}
			}
			if (get_option('sidebarlogin_forgotton_link')=='yes') : ?>
			<li><a href="<?php bloginfo('wpurl'); ?>/wp-login.php?action=lostpassword" title="<?php _e('Password Lost and Found','sblogin'); ?>"><?php _e('Lost your password?','sblogin'); ?></a></li>
			<?php endif; ?>
			</ul>
			<?php	
		}
		// echo widget closing tag
		echo $after_widget;
}
function widget_wp_sidebarlogin_init() {
	if ( !function_exists('register_sidebar_widget') ) return;
	// Register widget for use
	register_sidebar_widget(array('Sidebar Login', 'widgets'), 'widget_wp_sidebarlogin');
}
function widget_wp_sidebarlogin_check() {

	// Add options - they may not exist
			add_option('sidebarlogin_login_redirect','','no');
			add_option('sidebarlogin_logout_redirect','','no');
			add_option('sidebarlogin_register_link','yes','no');
			add_option('sidebarlogin_forgotton_link','yes','no');
			add_option('sidebarlogin_logged_in_links', "<a href=\"".get_bloginfo('wpurl')."/wp-admin/\">".__('Dashboard')."</a>\n<a href=\"".get_bloginfo('wpurl')."/wp-admin/profile.php\">".__('Profile')."</a>",'no');

	if ($_POST['sidebarlogin_posted'] || $_GET['logout']) {
		// Includes
		global $myerrors;
		$myerrors = new WP_Error();
		//Set a cookie now to see if they are supported by the browser.
		setcookie(TEST_COOKIE, 'WP Cookie check', 0, COOKIEPATH, COOKIE_DOMAIN);
		if ( SITECOOKIEPATH != COOKIEPATH )
			setcookie(TEST_COOKIE, 'WP Cookie check', 0, SITECOOKIEPATH, COOKIE_DOMAIN);
		// Logout
		if ($_GET['logout']==true) {
			nocache_headers();
			header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
			header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
			header("Cache-Control: no-store, no-cache, must-revalidate");
			header("Cache-Control: post-check=0, pre-check=0", false);
			header("Pragma: no-cache");
			wp_logout();
			$redir = get_option('sidebarlogin_logout_redirect');
			if (!empty($redir)) wp_redirect($redir);
				else wp_redirect(wp_sidebarlogin_current_url('nologout'));
			exit;
		}
		// Are we doing a sidebar login action?
		if ($_POST['sidebarlogin_posted']) {
		
			if ( is_ssl() && force_ssl_login() && !force_ssl_admin() && ( 0 !== strpos($redirect_to, 'https') ) && ( 0 === strpos($redirect_to, 'http') ) )
				$secure_cookie = false;
			else
				$secure_cookie = '';
		
			$user = wp_signon('', $secure_cookie);
			
			// Error Handling
			if ( is_wp_error($user) ) {
			
				$errors = $user;
	
				// If cookies are disabled we can't log in even with a valid user+pass
				if ( isset($_POST['testcookie']) && empty($_COOKIE[TEST_COOKIE]) )
					$errors->add('test_cookie', __("<strong>ERROR</strong>: Cookies are blocked or not supported by your browser. You must <a href='http://www.google.com/cookies.html'>enable cookies</a> to use WordPress."));
					
				if ( empty($_POST['log']) && empty($_POST['pwd']) ) {
					$errors->add('empty_username', __('<strong>ERROR</strong>: Please enter a username.'));
					$errors->add('empty_password', __('<strong>ERROR</strong>: Please enter your password.'));
				}
					
				$myerrors = $errors;
						
			} else {
				nocache_headers();
				header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
				header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
				header("Cache-Control: no-store, no-cache, must-revalidate");
				header("Cache-Control: post-check=0, pre-check=0", false);
				header("Pragma: no-cache");
				$redir = get_option('sidebarlogin_login_redirect');
				if (!empty($redir)) wp_redirect($redir);
				else wp_redirect(wp_sidebarlogin_current_url('nologout'));
				exit;
			}
		}
	}
}
if ( !function_exists('wp_sidebarlogin_current_url') ) :
function wp_sidebarlogin_current_url($url = '') {
	$pageURL = ($_SERVER['HTTPS'] == 'on') ? 'https://' : 'http://';

	if ($_SERVER["SERVER_PORT"] != "80") {
		$pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
	} else {
		$pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
	}
	
	if ($url == "logout" && strstr($pageURL,'logout')==false) {
		if (strstr($pageURL,'?')) {
			$pageURL .='&amp;logout=true&amp;action=logout';
		} else {
			$pageURL .='?logout=true&amp;action=logout';
		}
	} elseif ($url != "nologout") {
		$pageURL .='#login';
	}
	if ($url == "nologout" && strstr($pageURL,'logout')==true) {
		$pageURL = str_replace('?logout=true&action=logout','',$pageURL);
		$pageURL = str_replace('&logout=true&action=logout','',$pageURL);
	}
	//————–added by mick 
	if (!strstr(get_bloginfo('url'),'www.')) $pageURL = str_replace('www.','', $pageURL );
	//——————–	
	return $pageURL;
}
endif;
// Run code and init
add_action('init', 'widget_wp_sidebarlogin_check',0);
add_action('widgets_init', 'widget_wp_sidebarlogin_init');
add_action('admin_menu', 'wp_sidebarlogin_menu');
?>