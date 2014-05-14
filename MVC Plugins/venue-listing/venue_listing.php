<?php
/*
Plugin Name: Venue Listing
Plugin URI: 
Description: 
Author: 
Version: 
Author URI: 
*/

register_activation_hook(__FILE__, 'venue_listing_activate');
register_deactivation_hook(__FILE__, 'venue_listing_deactivate');

function venue_listing_activate() {
	require_once dirname(__FILE__).'/venue_listing_loader.php';
	$loader = new VenueListingLoader();
	$loader->activate();
}

function venue_listing_deactivate() {
	require_once dirname(__FILE__).'/venue_listing_loader.php';
	$loader = new VenueListingLoader();
	$loader->deactivate();
}

?>