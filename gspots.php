<?php
/*
Plugin Name: gspots
Version: 1.0.0
Description: Google Maps Location Finder
Author: Scott Gordon
Author URI: https://github.com/scottyg/
Plugin URI: https://github.com/scottyg/gspots
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
