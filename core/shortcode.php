<?php
/**
 * Adds Shortcode [gspots zip=89118 radius=50 ]
 */

class gspots_Shortcode {
	static $add_script;
	static $instance_id;
	static $attributes;
	
	static function init() {
	
		self::$instance_id = "gspots_" . uniqid();

		add_shortcode('gspots', array(__CLASS__, 'handle_shortcode'));

		add_action('wp_enqueue_scripts', array(__CLASS__, 'register_script'), 9999 );
		add_action('wp_footer', array(__CLASS__, 'inline_script'), 9999 );

	}

	static function handle_shortcode($atts) {
		self::$add_script = true;
		
		self::$attributes = shortcode_atts( array(
				'class' => 'gspots',
				'radius' => 50,
				'scroll' => true,
				'zip' => 89135,
				'zoom' => 15,
		), $atts );
		//return $a['radius'];
		
		$map_div = '<div id="'.self::$instance_id.'" class="'.self::$attributes['class'].'"></div>';
		
		echo $map_div;
	}

	static function register_script() {
	
		wp_register_script('google-maps-api', 'http://maps.google.com/maps/api/js?key=' . get_option( 'api' )[ 'key' ] . '&sensor=true', '', NULL, true);
		wp_register_script('gmaps', plugins_url('/vendor/gmaps.js', __FILE__), '', NULL, true);
		
		wp_enqueue_script('google-maps-api');
		wp_enqueue_script('gmaps');
		
		wp_enqueue_style( 'gspots_styles', plugins_url('/style/gspots.css', __FILE__) );

	}
	
	static function inline_script() {
		echo "
	<script type='text/javascript'>
		var ".self::$instance_id.";
		$(document).ready(function(){
			".self::$instance_id." = new GMaps({
				el: '#".self::$instance_id."',
				zoom: ".self::$attributes['zoom'].",
				lat: -12.043333,
				lng: -77.028333,
				scrollwheel: ".self::$attributes['scroll'].",
			});
			/*
			".self::$instance_id.".addMarker({
				lat: -12.043333,
				lng: -77.03,
				title: 'Lima',
				details: {
					database_id: 42,
					author: 'HPNeo'
				},
				click: function(e){
					if(console.log)
						console.log(e);
					alert('You clicked in this marker');
				},
				mouseover: function(e){
					if(console.log)
						console.log(e);
				}
			});
			".self::$instance_id.".addMarker({
				lat: -12.042,
				lng: -77.028333,
				title: 'Marker with InfoWindow',
				infoWindow: {
					content: '<p>HTML Content</p>'
				}
			});
			*/
		
		});
	</script>
	
		";
	}
	
	static function geocode( $zip ) {
		$return = null;
		return $return;
	}

}

gspots_Shortcode::init();

