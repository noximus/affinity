<?php
/**
 * The Header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="main">
 *
 * @package WordPress
 * @subpackage Twenty_Thirteen
 * @since Twenty Thirteen 1.0
 */
?><!DOCTYPE html>
<!--[if IE 7]>
<html class="ie ie7" <?php language_attributes(); ?>>
<![endif]-->
<!--[if IE 8]>
<html class="ie ie8" <?php language_attributes(); ?>>
<![endif]-->
<!--[if !(IE 7) | !(IE 8)  ]><!-->
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<title><?php wp_title( '|', true, 'right' ); ?></title>
    <link rel="profile" href="http://gmpg.org/xfn/11">
    
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
	<!--[if lt IE 9]>
	<script src="<?php echo get_template_directory_uri(); ?>/js/html5.js"></script>
	<![endif]-->
	<?php wp_head(); ?>
    <link rel="stylesheet" type="text/css" href="<?php bloginfo('template_url'); ?>/fonts/stylesheet.css">
    <link rel="stylesheet" type="text/css" href="<?php bloginfo('template_url'); ?>/css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="<?php bloginfo('template_url'); ?>/css/style.css">
    <link href="favicon.ico" rel="icon" type="image/x-icon" />
    <script type="text/javascript" src="<?php bloginfo('template_url'); ?>/js/jquery-1.11.1.min.js"></script>
    <!--<script type="text/javascript" src="js/script.js"></script> -->
    <script type="text/javascript" src="<?php bloginfo('template_url'); ?>/js/bootstrap.js"></script>
</head>

<body <?php body_class(); ?>>
	<div id="page" class="hfeed site">
        <nav class="navbar navbar-default navbar-fixed-top" role="navigation">
          <div class="container">
            <div class="navbar-header">
              <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1"> <span class="sr-only">Toggle navigation</span> <span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span> </button>
              <a class="navbar-brand" href="/"><img src="<?php bloginfo('template_url'); ?>/img/logo.gif"></a> </div>
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
              <ul class="nav navbar-nav navbar-right">
                <li class="dropdown"><a href="#" class="dropdown-toggle text-center" data-toggle="dropdown">&nbsp;&nbsp;shop&nbsp;&nbsp;</a>
                  <ul class="dropdown-menu" role="menu">
                    <li><a href="products.html">frames</a></li>
                    <li><a href="products.html">completes</a></li>
                    <li><a href="products.html">components</a></li>
                    <li><a href="products.html">apparel</a></li>
                    <li><a href="products.html">accessories</a></li>
                  </ul>
                </li>
                <li class="dropdown"> <a href="#" class="dropdown-toggle text-center" data-toggle="dropdown">STORY</a>
                  <ul class="dropdown-menu" role="menu">
                    <li><a href="#">about</a></li>
                    <li><a href="#">Team</a></li>
                    <li><a href="#">partners</a></li>
                  </ul>
                </li>
                <li class="dropdown"> <a href="#" class="dropdown-toggle text-center" data-toggle="dropdown">Happenings</a>
                  <ul class="dropdown-menu" role="menu">
                    <li><a href="news.html">Social</a></li>
                    <li><a href="newsDetail.html">Videos</a></li>
                    <li><a href="#">photos</a></li>
                    <li><a href="#">team</a></li>
                    <li><a href="#">builds</a></li>
                  </ul>
                </li>
                <li><a href="/store/checkout/cart/" class="cart"><img src="<?php bloginfo('template_url'); ?>/img/cart.gif" class="center-block"></a></li>
              </ul>
            </div>
          </div>
        </nav>