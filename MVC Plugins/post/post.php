<?php
/*
Plugin Name: Post
Plugin URI: 
Description: 
Author: 
Version: 
Author URI: 
*/

register_activation_hook(__FILE__, 'post_activate');
register_deactivation_hook(__FILE__, 'post_deactivate');

function post_activate() {
	require_once dirname(__FILE__).'/post_loader.php';
	$loader = new PostLoader();
	$loader->activate();
}

function post_deactivate() {
	require_once dirname(__FILE__).'/post_loader.php';
	$loader = new PostLoader();
	$loader->deactivate();
}

?>