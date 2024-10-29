=== Plugin Name ===
Contributors: mebius0
Donate link: http://aistear.info/
Tags: google,analytics,widget,ranking,popular
Requires at least: 3.5.1
Tested up to: 3.5.1
Stable tag: 1.1

Display plugin rankings using Google Analytics.


== Description ==

This plug-in is to use the data obtained from Google Analytics to view the rankings of popular posts.

= How to use =  

1. Enter a value in the configuration screen.  
2. Add a widget.  
3. If you want to use in the theme if you can view by inserting the following functions:  
`<?php if (function_exists('aistear_ga_ranking')) { aistear_ga_ranking(); } ?>`
4. If you want to customize the display, then the function is called to set the array as follows(Showing Default Values):  
`<?php  
	$args = array(  
			'container' => 'ol',  
			'container_class' => 'ga-ranking',  
			'before' => '<li class="ga-ranking-list ga-ranking-list-%1$d">',  
			'after' => '</li>',  
			'echo' => 'true',  
		);  
	if (function_exists('aistear_ga_ranking')) { aistear_ga_ranking( $args ); }  
?>`  
= Developer =  
* Japanese(ja) - [Endoh Shingo](https://twitter.com/aistear_tech)


== Installation ==

1. Upload `aistear-ga-ranking` to the `/wp-content/plugins/` directory.
2. Activate the plugin through the 'Plugins' menu in WordPress.


== Screenshots ==

1. It is a screenshot of the settings screen.


== Changelog ==

= 1.0 =  
* First release

= 1.1 =  
* Change so that you can set the display elements for customizing