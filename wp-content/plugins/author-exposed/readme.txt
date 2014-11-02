=== Author Exposed ===
Contributors: igor-cls
Tags: author, authorexposed
Requires at least: 2.0
Tested up to: 2.3.3
Stable tag: 1.0

Simple and elegant way to display post author info (full name, website, description ...)

== Description ==


This plugin does the same thing as the the_author tag, displays the author name, only this time it’s linked to hidden layer (div). By clicking on the author link the hidden layer(div) pop’s up with author info gathered from the profile page, plus gravatar photo (if author email is assigned with one).

*   xhtml,css valid.
*   Tested in FF, Opera, IE6/7 and Safari.
*   Comes with separate css file for easier modification.

You can see the plugin in use by [clicking here](http://colorlightstudio.com/2008/03/14/wordpress-plugin-author-exposed/).


== Installation ==

Installation is simple and should not take you more than 2 minutes.

1. Upload `author_exposed` folder to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Place `<?php if (function_exists('author_exposed')) {author_exposed();} ?>` where you want the author link to appear (must be inside the loop).



== Frequently Asked Questions ==

none