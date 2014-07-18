<?php
/*
Plugin Name: Gspots
Version: 1.0.3
Description: Google maps location finder.
Author: Scott Gordon
Author URI: https://iamscottyg.com
Plugin URI: http://wordpress.org/plugins/gspots/
Text Domain: gspots
Domain Path: /languages
*/

define('GSPOTS_PATH', plugin_dir_path( __FILE__ ) );
define('GSPOTS_URL', plugin_dir_url( __FILE__ ) );
define('GSPOTS_CACHE_LOCATION', GSPOTS_PATH . 'geocache.json' );
	
require_once( GSPOTS_PATH . "core/geocache.php" );
require_once( GSPOTS_PATH . "core/settings.php" );
require_once( GSPOTS_PATH . "core/post-type.php" );
require_once( GSPOTS_PATH . "core/taxonomy.php" );
require_once( GSPOTS_PATH . "core/meta-box.php" );
require_once( GSPOTS_PATH . "core/markers.php" );
require_once( GSPOTS_PATH . "core/shortcode.php" );
