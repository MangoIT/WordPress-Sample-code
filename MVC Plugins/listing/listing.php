<?php
/*
Plugin Name: Listing
Plugin URI: 
Description: 
Author: 
Version: 
Author URI: 
*/

register_activation_hook(__FILE__, 'listing_activate');
register_deactivation_hook(__FILE__, 'listing_deactivate');

function listing_activate() {
	require_once dirname(__FILE__).'/listing_loader.php';
	$loader = new ListingLoader();
	$loader->activate();
}

function listing_deactivate() {
	require_once dirname(__FILE__).'/listing_loader.php';
	$loader = new ListingLoader();
	$loader->deactivate();
}

?>