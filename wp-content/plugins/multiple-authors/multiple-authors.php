<?php
/*
Plugin Name: Multiple Authors
Plugin URI: http://txfx.net/code/wordpress/multiple-authors/
Description: Allows multiple authors to be listed for each entry, automatically keeping track of who has edited the entry.
Version: 0.2
Author: Mark Jaquith
Author URI: http://txfx.net/
*/


/*
Multiple Authors uses the "other_author" meta key to store the login name of an additional author.  These keys should be added automatically, but may be added (or deleted) manually.
*/


/*  Copyright 2005  Mark Jaquith (email: mark.gpl@txfx.net)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/

function txfx_other_authors($between=', ', $last=' and ', $before='', $after='') {
	global $post, $authordata;
	
	$other_authors = get_post_meta($post->ID, 'other_author');
	if ( count($other_authors) < 1 ) return;
	
	// initialize some vars
	$c = 0;
	$count = count($other_authors);
	
	// still here?  time to loop through the authors
	echo $before;
	foreach ($other_authors as $author) {
		$c++;
		$authordata = get_userdatabylogin($author);
		if ($count == $c) {
			echo $last;
		} else {
			echo $between;	
		}
		the_author();
	}
	echo $after;
}


function txfx_oa_add_author($post_ID) {
	global $wpdb, $userdata;
	
	if ( !$userdata ) get_currentuserinfo(); // shouldn't be needed, but can't hurt

	$main_author = $wpdb->get_var("SELECT `post_author` FROM $wpdb->posts WHERE `ID` = '$post_ID'");
	
	if ( $userdata->ID == $main_author ) return $post_ID; // the person who created the post is editing it... do nothing
	
	// if we're still here, it must be a new author, so see if they are already logged as an author on this post
	if ( $wpdb->get_var("SELECT meta_id FROM $wpdb->postmeta WHERE meta_key  = 'other_author' AND post_id = '$post_ID' AND meta_value = '$userdata->user_login'") ) return $post_ID;
	
	// if we're STILL here, it's time to add this author
	add_post_meta($post_ID, 'other_author', $userdata->user_login);
	
	//and we're done
	return $post_ID;
	
}


function txfx_all_authors() {
	global $post, $authordata, $txfx_authordata;

	// Load up the primary author
	$all_authors[0] = $authordata;

	// We're on the first author
	$txfx_authordata['current'] = 0;

	$other_authors = get_post_meta($post->ID, 'other_author');
	if ( count($other_authors) < 1 ) {
		$txfx_authordata['count'] = 1;
		return $all_authors;
	}

	foreach ( $other_authors as $author ) {
		$all_authors[] = get_userdatabylogin($author);
	}

	$txfx_authordata['count'] = count($all_authors);

	return $all_authors;
}


function txfx_next_author() {
	global $txfx_authordata;
	++$txfx_authordata['current'];
}


function txfx_author_count() {
	global $txfx_authordata;
	return $txfx_authordata['count'];
}


function txfx_author_number() {
	global $txfx_authordata;
	return $txfx_authordata['current'];
}


add_action('edit_post', 'txfx_oa_add_author');
add_action('save_post', 'txfx_oa_add_author');
?>
