<?php

// let's load up the most minimal version of wordpress possible
$wp_root = preg_replace('/wp-content(?!.*wp-content).*/','',__DIR__);

define( 'BASE_PATH', $wp_root );
define( 'SHORTINIT', true );
define( 'WP_USE_THEMES', false );
define( 'WP_PLUGIN_URL', false );

//WP config file
require( BASE_PATH . 'wp-config.php');

// Run the installer if WordPress is not installed.
wp_not_installed();

require( ABSPATH . WPINC . '/class-wp-user.php' );
require( ABSPATH . WPINC . '/class-wp-roles.php' );
require( ABSPATH . WPINC . '/class-wp-role.php' );
require( ABSPATH . WPINC . '/class-wp-session-tokens.php' );
require( ABSPATH . WPINC . '/class-wp-user-meta-session-tokens.php' );

require( ABSPATH . WPINC . '/formatting.php' );
require( ABSPATH . WPINC . '/capabilities.php' );
require( ABSPATH . WPINC . '/query.php' );
require( ABSPATH . WPINC . '/user.php' );
require( ABSPATH . WPINC . '/meta.php' );

// Define constants after multisite is loaded. Cookie-related constants may be overridden in ms_network_cookies().
wp_cookie_constants();

// Create common globals.
require( ABSPATH . WPINC . '/vars.php' );
require( ABSPATH . WPINC . '/kses.php' );
require( ABSPATH . WPINC . '/rest-api.php' );
require( ABSPATH . WPINC . '/pluggable.php' );
require( BASE_PATH . 'wp-load.php');

require('vtbots-tank-controller.php');

$action = '';
if( !empty( $_GET['action'] ) ) {
	$action = $_GET['action'];
} elseif( !empty( $_POST['action'] ) ) {
	$action = $_POST['action'];
}

switch( $action ) {
	case 'vtbots_tank_get_command' :
		vtbots_tank_get_command();
		break;
	case 'vtbots_tank_register_hit' :
		vtbots_tank_register_hit();
		break;
	case 'vtbots_tank_send_command' :
		vtbots_tank_send_command();
		break;
	case 'vtbots_tank_get_score' :
		vtbots_tank_get_score();
		break;
	default:
		echo "no action sent";
}

function vtbots_tank_get_command() {
	$tanks = VTBTank::Instance();
	$tanks->get_command();
}

function vtbots_tank_register_hit() {
	$tanks = VTBTank::Instance();
	$tanks->register_hit();
}

function vtbots_tank_send_command() {
	$tanks = VTBTank::Instance();
	$tanks->send_command();
}

function vtbots_tank_get_score() {
	$tanks = VTBTank::Instance();
	$tanks->get_score();
}

die();
