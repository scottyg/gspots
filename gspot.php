<?php
/*
Plugin Name: Gspot
Version: 0.1-alpha
Description: Google Maps Location Finder
Author: Scott Gordon
Author URI: http://iamscottyg.com
Plugin URI: http://iamscottyg.com
Text Domain: gspot
Domain Path: /languages
*/

$dir = plugin_dir_path( __FILE__ );
$url = plugin_dir_url( __FILE__ );

require_once( $dir. "core/settings.php" );
require_once( $dir. "core/post-type.php" );
require_once( $dir. "core/taxonomy.php" );
require_once( $dir. "core/meta-box.php" );
require_once( $dir. "core/shortcode.php" );
