<?php
/*
Plugin Name: Registration
Plugin URI: 
Description: 
Author: 
Version: 
Author URI: 
*/

register_activation_hook(__FILE__, 'registration_activate');
register_deactivation_hook(__FILE__, 'registration_deactivate');

function registration_activate() {
	require_once dirname(__FILE__).'/registration_loader.php';
	$loader = new RegistrationLoader();
	$loader->activate();
}

function registration_deactivate() {
	require_once dirname(__FILE__).'/registration_loader.php';
	$loader = new RegistrationLoader();
	$loader->deactivate();
}

?>