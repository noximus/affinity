<?php
if ( function_exists('register_sidebar') )


	register_sidebar(array(
		'name' => 'Top-Sidebar-Banners',
		'before_widget' => '',
		'after_widget' => '',
		'before_title' => '<h2>',
		'after_title' => '</h2>',
	));
	register_sidebar(array(
		'name' => 'Bottom-Sidebar-Banners',
		'before_widget' => '',
		'after_widget' => '',
		'before_title' => '<h2>',
		'after_title' => '</h2>',
	));
	
	register_sidebar(array(
		'name' => 'Archives-Sidebar',
		'before_widget' => '',
		'after_widget' => '',
		'before_title' => '<h2>',
		'after_title' => '</h2>',
	));
	
	
	 register_sidebar(array(
		'name'=>'teamsidebar',
		'before_title'=>'<h1>',
		'after_title'=>'</h1>'
	));
 
 function catch_that_image() {
  $files = get_children('post_parent='.get_the_ID().'&post_type=attachment&post_mime_type=image');
  if($files) :
    $keys = array_reverse(array_keys($files));
    $j=0;
    $num = $keys[$j];
    $image=wp_get_attachment_image($num, 'large', false);
    $imagepieces = explode('"', $image);
    $imagepath = $imagepieces[1];
    $thumb=wp_get_attachment_thumb_url($num);
    print "<img src='$thumb' align='left' height='150' />";
  else :
  	print "<img src='http://www.affinitycycles.com/images/no-image.jpg' align='left' height='150' />";
  endif;
  }
 
 function uniqueRand($n, $min = 0, $max = null)
 {
  if($max === null)
   $max = getrandmax();
  $array = range($min, $max);
  $return = array();
  $keys = array_rand($array, $n);
  foreach($keys as $key)
   $return[] = $array[$key];
  return $return;
 }

function getMostViewed($link){
    $content = load($link);
    $table = '<table width="620px" cellspacing="0" cellpadding="0" border="0">';
    $table .= '<tr>';
    $crop = strpos($content, 'products-grid-table');
    $site = substr($content, $crop-33, strpos(substr($content, $crop-33, strlen($content)), '</table>'));
    $site2 = $site;
    for($i = 0;$i < 5; $i++){
    $pos = strpos($site, 'a class="product-image-small" href="');
    $pos3 = strpos($site,'<img src="');
    $pos4 = strpos($site,'price">');
    $link = substr($site, $pos+36, strpos(substr($site, $pos+36, strlen($site)), '"'));
    $img = substr($site, $pos3+10, strpos(substr($site, $pos3+10, strlen($site)), '"'));
    $table .= '<td class="table1" width="102" height="99"><a href="'.$link.'"><img src="'.$img.'" width="102" height="99" /></a></td>';
    $site = substr($site, $pos4+7, strlen($site));
    }
    $table .= '</tr><tr>';
    for($k = 0;$k < 5; $k++){
    $pos = strpos($site2, 'a class="product-image-small" href="');
    $pos2 = strpos($site2,'title="');
    $pos4 = strpos($site2,'<span class="price">');
    $title = substr($site2, $pos2+7, strpos(substr($site2, $pos2+7, strlen($site2)), '"'));
    $link = substr($site2, $pos+36, strpos(substr($site2, $pos+36, strlen($site2)), '"'));
    $price = substr($site2, $pos4+20, strpos(substr($site2, $pos4+20, strlen($site2)), '</span></span>'));
    $table .= '<td class="table3"><a href="'.$link.'">'.$title.'</a><br/><span class="blue">'.$price.'</span></td>';
    $site2 = substr($site2, $pos4+20, strlen($site2));
    }
    $table .= '</tr></table>';
    echo $table;  
}

function getFeed($feed) {  
    $content = load($feed);  
    $x = new SimpleXmlElement($content);  
    $table = '<table width="520px" border="0" cellspacing="0" cellpadding="0">';
    $table .= '<tr>';
    $skt = $x->channel->item;
    if(sizeof($skt) > 1 && sizeof($skt)<4){
      $rnd = uniqueRand(sizeof($skt),0,sizeof($skt)-1);
    }else if (sizeof($skt) > 1) {
      $rnd = uniqueRand(4,0,sizeof($skt)-1);
    } else {
      $rnd = $skt;
    }
    for($i =0;$i < sizeof($skt); $i++) {  
        for($k = 0;$k < sizeof($rnd); $k++){
            if($rnd[$k] == $i){
                $pos = strpos($skt[$i]->description, 'src="');
                $table .= '<td class="arrivals"><a href="'.$skt[$i]->link.'"><img src="'.substr($skt[$i]->description, $pos+5, strpos(substr($skt[$i]->description, $pos+5, strlen($skt[$i]->description)), '"')).'" width="118" height="114" /></a></td>';
                
            }
        }
    }  
    $table .= "</tr><tr>";
    for($i =0;$i < sizeof($skt); $i++) {  
        for($k = 0;$k < sizeof($rnd); $k++){
            if($rnd[$k] == $i){
                $table .= '<td class="arrivals"><br /><a href="'.$skt[$i]->link.'" title="'.$skt[$i]->title.'">'.$skt[$i]->title.'</a><br />'; 
                $pos2 = strpos($skt[$i]->description, 'class="price">');
                $table .= '<span class="blue">'.substr($skt[$i]->description, $pos2+14, strpos(substr($skt[$i]->description, $pos2+14, strlen($skt[$i]->description)), '<')).'</span></td>';
                
            }
        }
    }
    $table .= '</tr></table>';
    echo $table;    
}


function getSeed($feed) {  
    $content = load($feed);  
    $x = new SimpleXmlElement($content);  
    $table = '<table width="620px" border="0" cellspacing="0" cellpadding="0">';
    $table .= '<tr>';
    $skt = $x->channel->item;
    if(sizeof($skt) > 1 && sizeof($skt)<5){
      $rnd = uniqueRand(sizeof($skt),0,sizeof($skt)-1);
    }else if (sizeof($skt) > 1) {
      $rnd = uniqueRand(5,0,sizeof($skt)-1);
    } else {
      $rnd = $skt;
    }
    $rnd = $skt;

    $arr = array();
    for($i =0;$i < min(sizeof($skt),5); $i++) { 
        array_push($arr, $skt[$i]);
    }
    shuffle($arr);

    for($i =0;$i < min(count($arr),5); $i++) {  
        $pos = strpos($arr[$i]->description, 'src="');
        $table .= '<td class="table1"><a href="'.$arr[$i]->link.'"><img src="'.substr($arr[$i]->description, $pos+5, strpos(substr($arr[$i]->description, $pos+5, strlen($arr[$i]->description)), '"')).'" width="102" height="99" /></a></td>';
        // for($k = 0;$k < sizeof($rnd); $k++){
        //     if($rnd[$k] == $i){
        //         $pos = strpos($skt[$i]->description, 'src="');
        //         $table .= '<td class="table1" tag="'.$i.'"><a href="'.$skt[$i]->link.'"><img src="'.substr($skt[$i]->description, $pos+5, strpos(substr($skt[$i]->description, $pos+5, strlen($skt[$i]->description)), '"')).'" width="102" height="99" /></a></td>';
                
        //     }
        // }
    }
    $table .= "</tr><tr>";
    for($i =0;$i < min(count($arr),5); $i++) {
        $table .= '<td class="table3"><a href="'.$arr[$i]->link.'" title="'.$arr[$i]->title.'">'.$arr[$i]->title.'</a><br />'; 
        $pos2 = strpos($arr[$i]->description, 'class="price">');
        
        $pos3 = strpos($arr[$i]->description, 'class="price">', $pos2 + 14);
        if ($pos3 > 0) {
            $table .= '<span class="red">SPECIAL: '.substr($arr[$i]->description, $pos3+14, strpos(substr($arr[$i]->description, $pos3+14, strlen($arr[$i]->description)), '<')).'</span>';
        } else {
            $table .= '<span class="blue">'.substr($arr[$i]->description, $pos2+14, strpos(substr($arr[$i]->description, $pos2+14, strlen($arr[$i]->description)), '<')).'</span>';
        }
        $table .= '</td>';

        // for($k = 0;$k < sizeof($rnd); $k++){
        //     if($rnd[$k] == $i){
        //         $table .= '<td class="table3"><a href="'.$skt[$i]->link.'" title="'.$skt[$i]->title.'">'.$skt[$i]->title.'</a><br />'; 
        //         $pos2 = strpos($skt[$i]->description, 'class="price">');
        //         $table .= '<span class="blue">'.substr($skt[$i]->description, $pos2+14, strpos(substr($skt[$i]->description, $pos2+14, strlen($skt[$i]->description)), '<')).'</span></td>';
                
        //     }
        // }
    }
    $table .= '</tr></table>';

    echo $table;    
}

function fb_list_authors($space = false, $userlevel = 'all', $show_fullname = true) {
	global $wpdb;
 
/*
 all = Display all user
 1 = subscriber
 2 = editor 
 3 = author
 7 = publisher
10 = administrator
*/
 $inc = 0;
if ( $userlevel == 'all' ) {
	$author_subscriper = $wpdb->get_results("SELECT * from $wpdb->usermeta WHERE meta_key = 'wp_capabilities' AND meta_value = 'a:1:{s:10:\"subscriber\";b:1;}'");
	foreach ( (array) $author_subscriper as $author ) {
        $author_id = $author->user_id;
		$author    = get_userdata( $author->user_id );
		$userlevel = $author->wp2_user_level;
		$name      = $author->nickname;
		if ( $show_fullname && ($author->first_name != '' && $author->last_name != '') ) {
			$name = "$author->first_name $author->last_name";
		}
        $link = '<li><a href="http://affinitycycles.simple-helix.net/?author='.$author_id .'"><img src="http://affinitycycles.simple-helix.net/avatars/'.$author->nickname.'.jpg" width=90 height=80 alt=""/></a>';
		$link .= '<a href="http://affinitycycles.simple-helix.net/?author='.$author_id .'">' . $name . '</a></li>';
		echo $link;
	}
 
	$i = 0;
	while ( $i <= 10 ) {
		$userlevel = $i;
		$authors = $wpdb->get_results("SELECT * from $wpdb->usermeta WHERE meta_key = 'wp_user_level' AND meta_value = '$userlevel'");
		foreach ( (array) $authors as $author ) {
            $author_id = $author->user_id;
			$author    = get_userdata( $author->user_id );
			$userlevel = $author->wp2_user_level;
			$name      = $author->nickname;
			if ( $show_fullname && ($author->first_name != '' && $author->last_name != '') ) {
				$name = "$author->first_name $author->last_name";
			}
            if($space == false){
              $link = '<li><a class="link_img" href="http://affinitycycles.simple-helix.net/?author='.$author_id .'"><img src="http://affinitycycles.simple-helix.net/avatars/'.$author->nickname.'.jpg" width=108 height=96 alt=""/></a>';
              $link .= '<a href="http://affinitycycles.simple-helix.net/?author='.$author_id .'">' . $name . '</a></li>';
            } 
            
            if($space == true){
              if($inc == 0){
                $link = '<li class="margines"><a class="link_img" href="http://affinitycycles.simple-helix.net/?author='.$author_id .'"><img src="http://affinitycycles.simple-helix.net/avatars/'.$author->nickname.'.jpg" width=90 height=80 alt=""/></a>';
                $link .= '<a href="http://affinitycycles.simple-helix.net/?author='.$author_id .'">' . $name . '</a></li>';
              }
              $inc++;
              if($inc == 2) {
                $link = '<li><a class="link_img" href="http://affinitycycles.simple-helix.net/?author='.$author_id .'"><img src="http://affinitycycles.simple-helix.net/avatars/'.$author->nickname.'.jpg" width=90 height=80 alt=""/></a>';
                $link .= '<a href="http://affinitycycles.simple-helix.net/?author='.$author_id .'">' . $name . '</a></li>';
                $inc = 0;
              }
            }
              
			echo $link;
		}
		$i++;
	}
} else {
	if ($userlevel == 1) {
		$authors = $wpdb->get_results("SELECT * from $wpdb->usermeta WHERE meta_key = 'wp_capabilities' AND meta_value = 'a:1:{s:10:\"subscriber\";b:1;}'");
	} else {
		$authors = $wpdb->get_results("SELECT * from $wpdb->usermeta WHERE meta_value = '$userlevel'");
	}
	foreach ( (array) $authors as $author ) {
        $author_id = $author->user_id;
		$author = get_userdata( $author->user_id );
		$userlevel = $author->wp2_user_level;
		$name = $author->nickname;
		if ( $show_fullname && ($author->first_name != '' && $author->last_name != '') ) {
			$name = "$author->first_name $author->last_name";
		}
        $link = '<li><img src="http://affinitycycles.simple-helix.net/avatars/'.$author->nickname.'.jpg" width=90 height=80 alt=""/>';
		$link  .= '<b>' . $userlevelname[$userlevel] . '</b></li>';
		$link .= '<a href="http://affinitycycles.simple-helix.net/?author='.$author_id .'">' . $name . '</a></li>';
		echo $link;
	}
}
}

function load($url,$options=array()) {
    $default_options = array(
        'method'        => 'get',
        'post_data'        => false,
        'return_info'    => false,
        'return_body'    => true,
        'cache'            => false,
        'referer'        => '',
        'headers'        => array(),
        'session'        => false,
        'session_close'    => false,
    );
    // Sets the default options.
    foreach($default_options as $opt=>$value) {
        if(!isset($options[$opt])) $options[$opt] = $value;
    }

    $url_parts = parse_url($url);
    $ch = false;
    $info = array(//Currently only supported by curl.
        'http_code'    => 200
    );
    $response = '';
    
    $send_header = array(
        'Accept' => 'text/*',
        'User-Agent' => 'BinGet/1.00.A (http://www.bin-co.com/php/scripts/load/)'
    ) + $options['headers']; // Add custom headers provided by the user.
    
    if($options['cache']) {
        $cache_folder = joinPath(sys_get_temp_dir(), 'php-load-function');
        if(isset($options['cache_folder'])) $cache_folder = $options['cache_folder'];
        if(!file_exists($cache_folder)) {
            $old_umask = umask(0); // Or the folder will not get write permission for everybody.
            mkdir($cache_folder, 0777);
            umask($old_umask);
        }
        
        $cache_file_name = md5($url) . '.cache';
        $cache_file = joinPath($cache_folder, $cache_file_name); //Don't change the variable name - used at the end of the function.
        
        if(file_exists($cache_file)) { // Cached file exists - return that.
            $response = file_get_contents($cache_file);
            
            //Seperate header and content
            $separator_position = strpos($response,"\r\n\r\n");
            $header_text = substr($response,0,$separator_position);
            $body = substr($response,$separator_position+4);
            
            foreach(explode("\n",$header_text) as $line) {
                $parts = explode(": ",$line);
                if(count($parts) == 2) $headers[$parts[0]] = chop($parts[1]);
            }
            $headers['cached'] = true;
            
            if(!$options['return_info']) return $body;
            else return array('headers' => $headers, 'body' => $body, 'info' => array('cached'=>true));
        }
    }

    if(isset($options['post_data'])) { //There is an option to specify some data to be posted.
        $options['method'] = 'post';
        
        if(is_array($options['post_data'])) { //The data is in array format.
            $post_data = array();
            foreach($options['post_data'] as $key=>$value) {
                $post_data[] = "$key=" . urlencode($value);
            }
            $url_parts['query'] = implode('&', $post_data);
        } else { //Its a string
            $url_parts['query'] = $options['post_data'];
        }
    } elseif(isset($options['multipart_data'])) { //There is an option to specify some data to be posted.
        $options['method'] = 'post';
        $url_parts['query'] = $options['multipart_data'];
        /*
            This array consists of a name-indexed set of options.
            For example,
            'name' => array('option' => value)
            Available options are:
            filename: the name to report when uploading a file.
            type: the mime type of the file being uploaded (not used with curl).
            binary: a flag to tell the other end that the file is being uploaded in binary mode (not used with curl).
            contents: the file contents. More efficient for fsockopen if you already have the file contents.
            fromfile: the file to upload. More efficient for curl if you don't have the file contents.

            Note the name of the file specified with fromfile overrides filename when using curl.
         */
    }

    ///////////////////////////// Curl /////////////////////////////////////
    //If curl is available, use curl to get the data.
    if(function_exists("curl_init") 
                and (!(isset($options['use']) and $options['use'] == 'fsocketopen'))) { //Don't use curl if it is specifically stated to use fsocketopen in the options
        
        if(isset($options['post_data'])) { //There is an option to specify some data to be posted.
            $page = $url;
            $options['method'] = 'post';
            
            if(is_array($options['post_data'])) { //The data is in array format.
                $post_data = array();
                foreach($options['post_data'] as $key=>$value) {
                    $post_data[] = "$key=" . urlencode($value);
                }
                $url_parts['query'] = implode('&', $post_data);
            
            } else { //Its a string
                $url_parts['query'] = $options['post_data'];
            }
        } else {
            if(isset($options['method']) and $options['method'] == 'post') {
                $page = $url_parts['scheme'] . '://' . $url_parts['host'] . $url_parts['path'];
            } else {
                $page = $url;
            }
        }

        if($options['session'] and isset($GLOBALS['_binget_curl_session'])) $ch = $GLOBALS['_binget_curl_session']; //Session is stored in a global variable
        else $ch = curl_init($url_parts['host']);
        
        curl_setopt($ch, CURLOPT_URL, $page) or die("Invalid cURL Handle Resouce");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); //Just return the data - not print the whole thing.
        curl_setopt($ch, CURLOPT_HEADER, true); //We need the headers
        curl_setopt($ch, CURLOPT_NOBODY, !($options['return_body'])); //The content - if true, will not download the contents. There is a ! operation - don't remove it.
        $tmpdir = NULL; //This acts as a flag for us to clean up temp files
        if(isset($options['method']) and $options['method'] == 'post' and isset($url_parts['query'])) {
            curl_setopt($ch, CURLOPT_POST, true);
            if(is_array($url_parts['query'])) {
                //multipart form data (eg. file upload)
                $postdata = array();
                foreach ($url_parts['query'] as $name => $data) {
                    if (isset($data['contents']) && isset($data['filename'])) {
                        if (!isset($tmpdir)) { //If the temporary folder is not specifed - and we want to upload a file, create a temp folder.
                            //  :TODO:
                            $dir = sys_get_temp_dir();
                            $prefix = 'load';
                            
                            if (substr($dir, -1) != '/') $dir .= '/';
                            do {
                                $path = $dir . $prefix . mt_rand(0, 9999999);
                            } while (!mkdir($path, $mode));
                        
                            $tmpdir = $path;
                        }
                        $tmpfile = $tmpdir.'/'.$data['filename'];
                        file_put_contents($tmpfile, $data['contents']);
                        $data['fromfile'] = $tmpfile;
                    }
                    if (isset($data['fromfile'])) {
                        // Not sure how to pass mime type and/or the 'use binary' flag
                        $postdata[$name] = '@'.$data['fromfile'];
                    } elseif (isset($data['contents'])) {
                        $postdata[$name] = $data['contents'];
                    } else {
                        $postdata[$name] = '';
                    }
                }
                curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
            } else {
                curl_setopt($ch, CURLOPT_POSTFIELDS, $url_parts['query']);
            }
        }

        //Set the headers our spiders sends
        curl_setopt($ch, CURLOPT_USERAGENT, $send_header['User-Agent']); //The Name of the UserAgent we will be using ;)
        $custom_headers = array("Accept: " . $send_header['Accept'] );
        if(isset($options['modified_since']))
            array_push($custom_headers,"If-Modified-Since: ".gmdate('D, d M Y H:i:s \G\M\T',strtotime($options['modified_since'])));
        curl_setopt($ch, CURLOPT_HTTPHEADER, $custom_headers);
        if($options['referer']) curl_setopt($ch, CURLOPT_REFERER, $options['referer']);

        curl_setopt($ch, CURLOPT_COOKIEJAR, "/tmp/binget-cookie.txt"); //If ever needed...
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_MAXREDIRS, 5);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        $custom_headers = array();
        unset($send_header['User-Agent']); // Already done (above)
        foreach ($send_header as $name => $value) {
            if (is_array($value)) {
                foreach ($value as $item) {
                    $custom_headers[] = "$name: $item";
                }
            } else {
                $custom_headers[] = "$name: $value";
            }
        }
        if(isset($url_parts['user']) and isset($url_parts['pass'])) {
            $custom_headers[] = "Authorization: Basic ".base64_encode($url_parts['user'].':'.$url_parts['pass']);
        }
        curl_setopt($ch, CURLOPT_HTTPHEADER, $custom_headers);

        $response = curl_exec($ch);

        if(isset($tmpdir)) {
            //rmdirr($tmpdir); //Cleanup any temporary files :TODO:
        }

        $info = curl_getinfo($ch); //Some information on the fetch
        
        if($options['session'] and !$options['session_close']) $GLOBALS['_binget_curl_session'] = $ch; //Dont close the curl session. We may need it later - save it to a global variable
        else curl_close($ch);  //If the session option is not set, close the session.

    //////////////////////////////////////////// FSockOpen //////////////////////////////
    } else { //If there is no curl, use fsocketopen - but keep in mind that most advanced features will be lost with this approch.

        if(!isset($url_parts['query']) || (isset($options['method']) and $options['method'] == 'post'))
            $page = $url_parts['path'];
        else
            $page = $url_parts['path'] . '?' . $url_parts['query'];
        
        if(!isset($url_parts['port'])) $url_parts['port'] = ($url_parts['scheme'] == 'https' ? 443 : 80);
        $host = ($url_parts['scheme'] == 'https' ? 'ssl://' : '').$url_parts['host'];
        $fp = fsockopen($host, $url_parts['port'], $errno, $errstr, 30);
        if ($fp) {
            $out = '';
            if(isset($options['method']) and $options['method'] == 'post' and isset($url_parts['query'])) {
                $out .= "POST $page HTTP/1.1\r\n";
            } else {
                $out .= "GET $page HTTP/1.0\r\n"; //HTTP/1.0 is much easier to handle than HTTP/1.1
            }
            $out .= "Host: $url_parts[host]\r\n";
        foreach ($send_header as $name => $value) {
        if (is_array($value)) {
            foreach ($value as $item) {
            $out .= "$name: $item\r\n";
            }
        } else {
            $out .= "$name: $value\r\n";
        }
        }
            $out .= "Connection: Close\r\n";
            
            //HTTP Basic Authorization support
            if(isset($url_parts['user']) and isset($url_parts['pass'])) {
                $out .= "Authorization: Basic ".base64_encode($url_parts['user'].':'.$url_parts['pass']) . "\r\n";
            }

            //If the request is post - pass the data in a special way.
            if(isset($options['method']) and $options['method'] == 'post') {
                if(is_array($url_parts['query'])) {
                    //multipart form data (eg. file upload)

                    // Make a random (hopefully unique) identifier for the boundary
                    srand((double)microtime()*1000000);
                    $boundary = "---------------------------".substr(md5(rand(0,32000)),0,10);

                    $postdata = array();
                    $postdata[] = '--'.$boundary;
                    foreach ($url_parts['query'] as $name => $data) {
                        $disposition = 'Content-Disposition: form-data; name="'.$name.'"';
                        if (isset($data['filename'])) {
                            $disposition .= '; filename="'.$data['filename'].'"';
                        }
                        $postdata[] = $disposition;
                        if (isset($data['type'])) {
                            $postdata[] = 'Content-Type: '.$data['type'];
                        }
                        if (isset($data['binary']) && $data['binary']) {
                            $postdata[] = 'Content-Transfer-Encoding: binary';
                        } else {
                            $postdata[] = '';
                        }
                        if (isset($data['fromfile'])) {
                            $data['contents'] = file_get_contents($data['fromfile']);
                        }
                        if (isset($data['contents'])) {
                            $postdata[] = $data['contents'];
                        } else {
                            $postdata[] = '';
                        }
                        $postdata[] = '--'.$boundary;
                    }
                    $postdata = implode("\r\n", $postdata)."\r\n";
                    $length = strlen($postdata);
                    $postdata = 'Content-Type: multipart/form-data; boundary='.$boundary."\r\n".
                                'Content-Length: '.$length."\r\n".
                                "\r\n".
                                $postdata;

                    $out .= $postdata;
                } else {
                    $out .= "Content-Type: application/x-www-form-urlencoded\r\n";
                    $out .= 'Content-Length: ' . strlen($url_parts['query']) . "\r\n";
                    $out .= "\r\n" . $url_parts['query'];
                }
            }
            $out .= "\r\n";

            fwrite($fp, $out);
            while (!feof($fp)) {
                $response .= fgets($fp, 128);
            }
            fclose($fp);
        }
    }

    //Get the headers in an associative array
    $headers = array();

    if($info['http_code'] == 404) {
        $body = "";
        $headers['Status'] = 404;
    } else {
        //Seperate header and content
        $header_text = substr($response, 0, $info['header_size']);
        $body = substr($response, $info['header_size']);
        
        foreach(explode("\n",$header_text) as $line) {
            $parts = explode(": ",$line);
            if(count($parts) == 2) {
                if (isset($headers[$parts[0]])) {
                    if (is_array($headers[$parts[0]])) $headers[$parts[0]][] = chop($parts[1]);
                    else $headers[$parts[0]] = array($headers[$parts[0]], chop($parts[1]));
                } else {
                    $headers[$parts[0]] = chop($parts[1]);
                }
            }
        }

    }
    
    if(isset($cache_file)) { //Should we cache the URL?
        file_put_contents($cache_file, $response);
    }

    if($options['return_info']) return array('headers' => $headers, 'body' => $body, 'info' => $info, 'curl_handle'=>$ch);
    return $body;
}

// You can use this function instead of 'wp_list_pages' in your theme
function wp_list_main_pages($args = '') {
	$defaults = array(
		'depth' => 0, 'show_date' => '',
		'date_format' => get_option('date_format'),
		'child_of' => 0, 'exclude' => '',
		'title_li' => __('Pages'), 'echo' => 1,
		'authors' => '', 'sort_column' => 'menu_order, post_title'
	);

	$r = wp_parse_args( $args, $defaults );
	extract( $r, EXTR_SKIP );

	$output = '';
	$current_page = 0;

	// sanitize, mostly to keep spaces out
	$r['exclude'] = preg_replace('[^0-9,]', '', $r['exclude']);

	// Allow plugins to filter an array of excluded pages
	$r['exclude'] = implode(',', apply_filters('wp_list_pages_excludes', explode(',', $r['exclude'])));

	// Query pages.
	$r['hierarchical'] = 0;
	// Right here we call our new 'get_main_pages' function
	$pages = get_main_pages($r);

	if ( !empty($pages) ) {
		if ( $r['title_li'] )
			$output .= '<li class="pagenav">' . $r['title_li'] . '<ul>';

		global $wp_query;
		if ( is_page() || $wp_query->is_posts_page )
			$current_page = $wp_query->get_queried_object_id();
		$output .= walk_page_tree($pages, $r['depth'], $current_page, $r);

		if ( $r['title_li'] )
			$output .= '</ul></li>';
	}

	$output = apply_filters('wp_list_pages', $output);

	// This line is sloppy and needs improvement.
	// You have to remove the name of the blog your currently on from you global navigation.
	// I'm doing this the simplest but least scalable way here.
	$output = str_replace("pressroom/", "", $output);

	if ( $r['echo'] )
		echo $output;
	else
		return $output;
}

// This is essentially a private function
function &get_main_pages($args = '') {
	global $wpdb;

	// This is the magic line.
	// Now when the SQL runs to pull your navigation pages, it'll use your main blogs ID.
	//$wpdb->set_blog_id(1);

	// Notice here I call the original get_pages function and return the results
	$pages = get_pages($args);
	return $pages;
}

function string_limit_words($string, $word_limit)
{
  $words = explode(' ', $string, ($word_limit + 1));
  if(count($words) > $word_limit)
  array_pop($words);
  return implode(' ', $words);
}

function kubrick_head() {
	$head = "<style type='text/css'>\n<!--";
	$output = '';
	if ( kubrick_header_image() ) {
		$url =  kubrick_header_image_url() ;
		$output .= "#header { background: url('$url') no-repeat bottom center; }\n";
	}
	if ( false !== ( $color = kubrick_header_color() ) ) {
		$output .= "#headerimg h1 a, #headerimg h1 a:visited, #headerimg .description { color: $color; }\n";
	}
	if ( false !== ( $display = kubrick_header_display() ) ) {
		$output .= "#headerimg { display: $display }\n";
	}
	$foot = "--></style>\n";
	if ( '' != $output )
		echo $head . $output . $foot;
}

add_action('wp_head', 'kubrick_head');

function kubrick_header_image() {
	return apply_filters('kubrick_header_image', get_option('kubrick_header_image'));
}

function kubrick_upper_color() {
	if (strpos($url = kubrick_header_image_url(), 'header-img.php?') !== false) {
		parse_str(substr($url, strpos($url, '?') + 1), $q);
		return $q['upper'];
	} else
		return '69aee7';
}

function kubrick_lower_color() {
	if (strpos($url = kubrick_header_image_url(), 'header-img.php?') !== false) {
		parse_str(substr($url, strpos($url, '?') + 1), $q);
		return $q['lower'];
	} else
		return '4180b6';
}

function kubrick_header_image_url() {
	if ( $image = kubrick_header_image() )
		$url = get_template_directory_uri() . '/images/' . $image;
	else
		$url = get_template_directory_uri() . '/images/kubrickheader.jpg';

	return $url;
}

function kubrick_header_color() {
	return apply_filters('kubrick_header_color', get_option('kubrick_header_color'));
}

function kubrick_header_color_string() {
	$color = kubrick_header_color();
	if ( false === $color )
		return 'white';

	return $color;
}

function kubrick_header_display() {
	return apply_filters('kubrick_header_display', get_option('kubrick_header_display'));
}

function kubrick_header_display_string() {
	$display = kubrick_header_display();
	return $display ? $display : 'inline';
}

add_action('admin_menu', 'kubrick_add_theme_page');

function kubrick_add_theme_page() {
	if ( isset( $_GET['page'] ) && $_GET['page'] == basename(__FILE__) ) {
		if ( isset( $_REQUEST['action'] ) && 'save' == $_REQUEST['action'] ) {
			check_admin_referer('kubrick-header');
			if ( isset($_REQUEST['njform']) ) {
				if ( isset($_REQUEST['defaults']) ) {
					delete_option('kubrick_header_image');
					delete_option('kubrick_header_color');
					delete_option('kubrick_header_display');
				} else {
					if ( '' == $_REQUEST['njfontcolor'] )
						delete_option('kubrick_header_color');
					else {
						$fontcolor = preg_replace('/^.*(#[0-9a-fA-F]{6})?.*$/', '$1', $_REQUEST['njfontcolor']);
						update_option('kubrick_header_color', $fontcolor);
					}
					if ( preg_match('/[0-9A-F]{6}|[0-9A-F]{3}/i', $_REQUEST['njuppercolor'], $uc) && preg_match('/[0-9A-F]{6}|[0-9A-F]{3}/i', $_REQUEST['njlowercolor'], $lc) ) {
						$uc = ( strlen($uc[0]) == 3 ) ? $uc[0]{0}.$uc[0]{0}.$uc[0]{1}.$uc[0]{1}.$uc[0]{2}.$uc[0]{2} : $uc[0];
						$lc = ( strlen($lc[0]) == 3 ) ? $lc[0]{0}.$lc[0]{0}.$lc[0]{1}.$lc[0]{1}.$lc[0]{2}.$lc[0]{2} : $lc[0];
						update_option('kubrick_header_image', "header-img.php?upper=$uc&lower=$lc");
					}

					if ( isset($_REQUEST['toggledisplay']) ) {
						if ( false === get_option('kubrick_header_display') )
							update_option('kubrick_header_display', 'none');
						else
							delete_option('kubrick_header_display');
					}
				}
			} else {

				if ( isset($_REQUEST['headerimage']) ) {
					check_admin_referer('kubrick-header');
					if ( '' == $_REQUEST['headerimage'] )
						delete_option('kubrick_header_image');
					else {
						$headerimage = preg_replace('/^.*?(header-img.php\?upper=[0-9a-fA-F]{6}&lower=[0-9a-fA-F]{6})?.*$/', '$1', $_REQUEST['headerimage']);
						update_option('kubrick_header_image', $headerimage);
					}
				}

				if ( isset($_REQUEST['fontcolor']) ) {
					check_admin_referer('kubrick-header');
					if ( '' == $_REQUEST['fontcolor'] )
						delete_option('kubrick_header_color');
					else {
						$fontcolor = preg_replace('/^.*?(#[0-9a-fA-F]{6})?.*$/', '$1', $_REQUEST['fontcolor']);
						update_option('kubrick_header_color', $fontcolor);
					}
				}

				if ( isset($_REQUEST['fontdisplay']) ) {
					check_admin_referer('kubrick-header');
					if ( '' == $_REQUEST['fontdisplay'] || 'inline' == $_REQUEST['fontdisplay'] )
						delete_option('kubrick_header_display');
					else
						update_option('kubrick_header_display', 'none');
				}
			}
			//print_r($_REQUEST);
			wp_redirect("themes.php?page=functions.php&saved=true");
			die;
		}
		add_action('admin_head', 'kubrick_theme_page_head');
	}
	add_theme_page(__('Customize Header'), __('Header Image and Color'), 'edit_themes', basename(__FILE__), 'kubrick_theme_page');
}

function kubrick_theme_page_head() {
?>
<script type="text/javascript" src="../wp-includes/js/colorpicker.js"></script>
<script type='text/javascript'>
// <![CDATA[
	function pickColor(color) {
		ColorPicker_targetInput.value = color;
		kUpdate(ColorPicker_targetInput.id);
	}
	function PopupWindow_populate(contents) {
		contents += '<br /><p style="text-align:center;margin-top:0px;"><input type="button" class="button-secondary" value="<?php echo attribute_escape(__('Close Color Picker')); ?>" onclick="cp.hidePopup(\'prettyplease\')"></input></p>';
		this.contents = contents;
		this.populated = false;
	}
	function PopupWindow_hidePopup(magicword) {
		if ( magicword != 'prettyplease' )
			return false;
		if (this.divName != null) {
			if (this.use_gebi) {
				document.getElementById(this.divName).style.visibility = "hidden";
			}
			else if (this.use_css) {
				document.all[this.divName].style.visibility = "hidden";
			}
			else if (this.use_layers) {
				document.layers[this.divName].visibility = "hidden";
			}
		}
		else {
			if (this.popupWindow && !this.popupWindow.closed) {
				this.popupWindow.close();
				this.popupWindow = null;
			}
		}
		return false;
	}
	function colorSelect(t,p) {
		if ( cp.p == p && document.getElementById(cp.divName).style.visibility != "hidden" )
			cp.hidePopup('prettyplease');
		else {
			cp.p = p;
			cp.select(t,p);
		}
	}
	function PopupWindow_setSize(width,height) {
		this.width = 162;
		this.height = 210;
	}

	var cp = new ColorPicker();
	function advUpdate(val, obj) {
		document.getElementById(obj).value = val;
		kUpdate(obj);
	}
	function kUpdate(oid) {
		if ( 'uppercolor' == oid || 'lowercolor' == oid ) {
			uc = document.getElementById('uppercolor').value.replace('#', '');
			lc = document.getElementById('lowercolor').value.replace('#', '');
			hi = document.getElementById('headerimage');
			hi.value = 'header-img.php?upper='+uc+'&lower='+lc;
			document.getElementById('header').style.background = 'url("<?php echo get_template_directory_uri(); ?>/images/'+hi.value+'") center no-repeat';
			document.getElementById('advuppercolor').value = '#'+uc;
			document.getElementById('advlowercolor').value = '#'+lc;
		}
		if ( 'fontcolor' == oid ) {
			document.getElementById('header').style.color = document.getElementById('fontcolor').value;
			document.getElementById('advfontcolor').value = document.getElementById('fontcolor').value;
		}
		if ( 'fontdisplay' == oid ) {
			document.getElementById('headerimg').style.display = document.getElementById('fontdisplay').value;
		}
	}
	function toggleDisplay() {
		td = document.getElementById('fontdisplay');
		td.value = ( td.value == 'none' ) ? 'inline' : 'none';
		kUpdate('fontdisplay');
	}
	function toggleAdvanced() {
		a = document.getElementById('jsAdvanced');
		if ( a.style.display == 'none' )
			a.style.display = 'block';
		else
			a.style.display = 'none';
	}
	function kDefaults() {
		document.getElementById('headerimage').value = '';
		document.getElementById('advuppercolor').value = document.getElementById('uppercolor').value = '#69aee7';
		document.getElementById('advlowercolor').value = document.getElementById('lowercolor').value = '#4180b6';
		document.getElementById('header').style.background = 'url("<?php echo get_template_directory_uri(); ?>/images/kubrickheader.jpg") center no-repeat';
		document.getElementById('header').style.color = '#FFFFFF';
		document.getElementById('advfontcolor').value = document.getElementById('fontcolor').value = '';
		document.getElementById('fontdisplay').value = 'inline';
		document.getElementById('headerimg').style.display = document.getElementById('fontdisplay').value;
	}
	function kRevert() {
		document.getElementById('headerimage').value = '<?php echo js_escape(kubrick_header_image()); ?>';
		document.getElementById('advuppercolor').value = document.getElementById('uppercolor').value = '#<?php echo js_escape(kubrick_upper_color()); ?>';
		document.getElementById('advlowercolor').value = document.getElementById('lowercolor').value = '#<?php echo js_escape(kubrick_lower_color()); ?>';
		document.getElementById('header').style.background = 'url("<?php echo js_escape(kubrick_header_image_url()); ?>") center no-repeat';
		document.getElementById('header').style.color = '';
		document.getElementById('advfontcolor').value = document.getElementById('fontcolor').value = '<?php echo js_escape(kubrick_header_color_string()); ?>';
		document.getElementById('fontdisplay').value = '<?php echo js_escape(kubrick_header_display_string()); ?>';
		document.getElementById('headerimg').style.display = document.getElementById('fontdisplay').value;
	}
	function kInit() {
		document.getElementById('jsForm').style.display = 'block';
		document.getElementById('nonJsForm').style.display = 'none';
	}
	addLoadEvent(kInit);
// ]]>
</script>
<style type='text/css'>
	#headwrap {
		text-align: center;
	}
	#kubrick-header {
		font-size: 80%;
	}
	#kubrick-header .hibrowser {
		width: 780px;
		height: 260px;
		overflow: scroll;
	}
	#kubrick-header #hitarget {
		display: none;
	}
	#kubrick-header #header h1 {
		font-family: 'Trebuchet MS', 'Lucida Grande', Verdana, Arial, Sans-Serif;
		font-weight: bold;
		font-size: 4em;
		text-align: center;
		padding-top: 70px;
		margin: 0;
	}

	#kubrick-header #header .description {
		font-family: 'Lucida Grande', Verdana, Arial, Sans-Serif;
		font-size: 1.2em;
		text-align: center;
	}
	#kubrick-header #header {
		text-decoration: none;
		color: <?php echo kubrick_header_color_string(); ?>;
		padding: 0;
		margin: 0;
		height: 200px;
		text-align: center;
		background: url('<?php echo kubrick_header_image_url(); ?>') center no-repeat;
	}
	#kubrick-header #headerimg {
		margin: 0;
		height: 200px;
		width: 100%;
		display: <?php echo kubrick_header_display_string(); ?>;
	}
	#jsForm {
		display: none;
		text-align: center;
	}
	#jsForm input.submit, #jsForm input.button, #jsAdvanced input.button {
		padding: 0px;
		margin: 0px;
	}
	#advanced {
		text-align: center;
		width: 620px;
	}
	html>body #advanced {
		text-align: center;
		position: relative;
		left: 50%;
		margin-left: -380px;
	}
	#jsAdvanced {
		text-align: right;
	}
	#nonJsForm {
		position: relative;
		text-align: left;
		margin-left: -370px;
		left: 50%;
	}
	#nonJsForm label {
		padding-top: 6px;
		padding-right: 5px;
		float: left;
		width: 100px;
		text-align: right;
	}
	.defbutton {
		font-weight: bold;
	}
	.zerosize {
		width: 0px;
		height: 0px;
		overflow: hidden;
	}
	#colorPickerDiv a, #colorPickerDiv a:hover {
		padding: 1px;
		text-decoration: none;
		border-bottom: 0px;
	}
</style>
<?php
}

function kubrick_theme_page() {
	if ( isset( $_REQUEST['saved'] ) ) echo '<div id="message" class="updated fade"><p><strong>'.__('Options saved.').'</strong></p></div>';
?>
<div class='wrap'>
	<div id="kubrick-header">
	<h2><?php _e('Header Image and Color'); ?></h2>
		<div id="headwrap">
			<div id="header">
				<div id="headerimg">
					<h1><?php bloginfo('name'); ?></h1>
					<div class="description"><?php bloginfo('description'); ?></div>
				</div>
			</div>
		</div>
		<br />
		<div id="nonJsForm">
			<form method="post" action="">
				<?php wp_nonce_field('kubrick-header'); ?>
				<div class="zerosize"><input type="submit" name="defaultsubmit" value="<?php echo attribute_escape(__('Save')); ?>" /></div>
					<label for="njfontcolor"><?php _e('Font Color:'); ?></label><input type="text" name="njfontcolor" id="njfontcolor" value="<?php echo attribute_escape(kubrick_header_color()); ?>" /> <?php printf(__('Any CSS color (%s or %s or %s)'), '<code>red</code>', '<code>#FF0000</code>', '<code>rgb(255, 0, 0)</code>'); ?><br />
					<label for="njuppercolor"><?php _e('Upper Color:'); ?></label><input type="text" name="njuppercolor" id="njuppercolor" value="#<?php echo attribute_escape(kubrick_upper_color()); ?>" /> <?php printf(__('HEX only (%s or %s)'), '<code>#FF0000</code>', '<code>#F00</code>'); ?><br />
				<label for="njlowercolor"><?php _e('Lower Color:'); ?></label><input type="text" name="njlowercolor" id="njlowercolor" value="#<?php echo attribute_escape(kubrick_lower_color()); ?>" /> <?php printf(__('HEX only (%s or %s)'), '<code>#FF0000</code>', '<code>#F00</code>'); ?><br />
				<input type="hidden" name="hi" id="hi" value="<?php echo attribute_escape(kubrick_header_image()); ?>" />
				<input type="submit" name="toggledisplay" id="toggledisplay" value="<?php echo attribute_escape(__('Toggle Text')); ?>" />
				<input type="submit" name="defaults" value="<?php echo attribute_escape(__('Use Defaults')); ?>" />
				<input type="submit" class="defbutton" name="submitform" value="&nbsp;&nbsp;<?php _e('Save'); ?>&nbsp;&nbsp;" />
				<input type="hidden" name="action" value="save" />
				<input type="hidden" name="njform" value="true" />
			</form>
		</div>
		<div id="jsForm">
			<form style="display:inline;" method="post" name="hicolor" id="hicolor" action="<?php echo attribute_escape($_SERVER['REQUEST_URI']); ?>">
				<?php wp_nonce_field('kubrick-header'); ?>
	<input type="button"  class="button-secondary" onclick="tgt=document.getElementById('fontcolor');colorSelect(tgt,'pick1');return false;" name="pick1" id="pick1" value="<?php echo attribute_escape(__('Font Color')); ?>"></input>
		<input type="button" class="button-secondary" onclick="tgt=document.getElementById('uppercolor');colorSelect(tgt,'pick2');return false;" name="pick2" id="pick2" value="<?php echo attribute_escape(__('Upper Color')); ?>"></input>
		<input type="button" class="button-secondary" onclick="tgt=document.getElementById('lowercolor');colorSelect(tgt,'pick3');return false;" name="pick3" id="pick3" value="<?php echo attribute_escape(__('Lower Color')); ?>"></input>
				<input type="button" class="button-secondary" name="revert" value="<?php echo attribute_escape(__('Revert')); ?>" onclick="kRevert()" />
				<input type="button" class="button-secondary" value="<?php echo attribute_escape(__('Advanced')); ?>" onclick="toggleAdvanced()" />
				<input type="hidden" name="action" value="save" />
				<input type="hidden" name="fontdisplay" id="fontdisplay" value="<?php echo attribute_escape(kubrick_header_display()); ?>" />
				<input type="hidden" name="fontcolor" id="fontcolor" value="<?php echo attribute_escape(kubrick_header_color()); ?>" />
				<input type="hidden" name="uppercolor" id="uppercolor" value="<?php echo attribute_escape(kubrick_upper_color()); ?>" />
				<input type="hidden" name="lowercolor" id="lowercolor" value="<?php echo attribute_escape(kubrick_lower_color()); ?>" />
				<input type="hidden" name="headerimage" id="headerimage" value="<?php echo attribute_escape(kubrick_header_image()); ?>" />
				<p class="submit"><input type="submit" name="submitform" class="defbutton" value="<?php echo attribute_escape(__('Update Header')); ?>" onclick="cp.hidePopup('prettyplease')" /></p>
			</form>
			<div id="colorPickerDiv" style="z-index: 100;background:#eee;border:1px solid #ccc;position:absolute;visibility:hidden;"> </div>
			<div id="advanced">
				<form id="jsAdvanced" style="display:none;" action="">
					<?php wp_nonce_field('kubrick-header'); ?>
					<label for="advfontcolor"><?php _e('Font Color (CSS):'); ?> </label><input type="text" id="advfontcolor" onchange="advUpdate(this.value, 'fontcolor')" value="<?php echo attribute_escape(kubrick_header_color()); ?>" /><br />
					<label for="advuppercolor"><?php _e('Upper Color (HEX):');?> </label><input type="text" id="advuppercolor" onchange="advUpdate(this.value, 'uppercolor')" value="#<?php echo attribute_escape(kubrick_upper_color()); ?>" /><br />
					<label for="advlowercolor"><?php _e('Lower Color (HEX):'); ?> </label><input type="text" id="advlowercolor" onchange="advUpdate(this.value, 'lowercolor')" value="#<?php echo attribute_escape(kubrick_lower_color()); ?>" /><br />
					<input type="button" class="button-secondary" name="default" value="<?php echo attribute_escape(__('Select Default Colors')); ?>" onclick="kDefaults()" /><br />
					<input type="button" class="button-secondary" onclick="toggleDisplay();return false;" name="pick" id="pick" value="<?php echo attribute_escape(__('Toggle Text Display')); ?>"></input><br />
				</form>
			</div>
		</div>
	</div>
</div>
<?php } ?>
