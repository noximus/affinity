<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>



<head profile="http://gmpg.org/xfn/11">

<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />



<title><?php bloginfo('name'); ?> <?php if ( is_single() ) { ?> &raquo; Blog Archive <?php } ?> <?php wp_title(); ?></title>



<link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>" type="text/css" media="screen" />

<link rel="alternate" type="application/rss+xml" title="<?php bloginfo('name'); ?> RSS Feed" href="<?php bloginfo('rss2_url'); ?>" />

<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />

<script type="text/javascript" src="http://affinitycycles.com/js/jquery-1.3.2.js"></script>

<script type="text/javascript" src="http://affinitycycles.com/js/tabs.js"></script>

<script type="text/javascript" src="<?php bloginfo('template_directory'); ?>/js/galleria-1.2.2.js"></script>





<?php wp_head(); ?>

</head>

<body>

<div id="page">





<div id="header">

	<a href="/"><img src="/images/logo_g_r.gif" width="405" height="99" alt="Affinity Cycles" /></a>

	<div id="top">

    

    	<div id="rssfeed">

           	<a href="<?php bloginfo('rss2_url'); ?>" target="_blank"><img src="/images/rss.gif" width="25" height="25" alt="Affinity Rss" /></a>

           	<a href="http://twitter.com/affinitycycles" target="_blank"><img src="/images/twitter.gif" width="25" height="25" alt="Affinity Twitter" /></a>

           	<a href="http://www.facebook.com/AffinityCycles" target="_blank"><img src="/images/facebook.gif" width="25" height="25" alt="Affinity Facebook" /></a>

        </div>

        

        <div class="links_top">

        	<a href="http://affinitycycles.com/store/customer/account/login/">My Account</a>&nbsp;&nbsp;&nbsp;<a href="http://affinitycycles.com/store/checkout/cart/">View Cart</a>&nbsp;&nbsp;&nbsp;<a href="http://affinitycycles.com/store/customer/account/login/">Log in</a>&nbsp;&nbsp;&nbsp;<a href="http://affinitycycles.com/store/customer/account/create/">Register</a>

        </div>





    </div>

    <div class="clearfloat"></div>

    <div id="menu">

        <ul>

        <?php wp_list_main_pages('title_li=0&depth=1'); ?>

        </ul>

    </div><!-- end id:menu -->

</div>

<hr />

