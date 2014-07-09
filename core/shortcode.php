<?php
/**
 * Adds Shortcode [gspots zip=89118 radius=50 ]
 */

class gspots_Shortcode {
	static $add_script;
	static $instance_id;
	static $attributes;
	static $form_attributes;
	
	static function init() {
	
		self::$instance_id = "gspots";

		add_shortcode('gspots', array(__CLASS__, 'handle_shortcode'));
		add_shortcode('gspots_form', array(__CLASS__, 'handle_form_shortcode'));

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
				'zoom' => 15
		), $atts );
		
		$map_div = '<div id="'.self::$instance_id.'" class="'.self::$attributes['class'].'"></div>';
		
		echo $map_div;
	}

	static function handle_form_shortcode($atts) {
		self::$add_script = true;
		
		self::$form_attributes = shortcode_atts( array(
				'class' => 'gspots-form',
				'max' => 500,
				'min' => 1,
				'submit' => 'Search'
		), $atts );
		
		$form_div = '<form id="'.self::$instance_id.'_form" class="'.self::$form_attributes['class'].'">
			<div><input type="text" id="'.self::$instance_id.'_zip" name="'.self::$instance_id.'_zip" placeholder="Zip"/></div>
			<input type="text" id="'.self::$instance_id.'_radius" name="'.self::$instance_id.'_radius" />
			<div><input type="submit" value="'.self::$form_attributes['submit'].'"/></div>
		</form>';
		
		echo $form_div;
	}

	static function register_script() {
	
		wp_register_script('google-maps-api', 'http://maps.google.com/maps/api/js?key=' . get_option( 'api' )[ 'key' ] . '&sensor=true', '', NULL, true);
		wp_register_script('gmaps', GSPOTS_URL . 'core/vendor/gmaps.js', '', NULL, true);
		wp_register_script('powerange', GSPOTS_URL . 'core/vendor/powerange.js', '', NULL, true);
		
		wp_enqueue_script('google-maps-api');
		wp_enqueue_script('gmaps');
		wp_enqueue_script('powerange');
		
		wp_enqueue_style( 'gspots_styles', GSPOTS_URL . 'core/style/gspots.css' );

	}
	
	static function inline_script() {
	
		$geocode = json_decode( geocache( self::$attributes['zip'] ) );

		$markers = json_decode( markers::get_json() );

		if( $markers != null ) {
		
			//var_dump( $markers );		
			//var_dump( $markers->options->type );
			$markers_js = "";
			foreach( $markers->entries as $marker ) {
				$this_marker = "
			
			".self::$instance_id.".addMarker({
				lat: ".$marker->lt.",
				lng: ".$marker->ln.",
				title: '".$marker->title."',
				click: function(e){
					if(console.log)
						console.log(e);
					console.log('You clicked in this marker');
				},
				mouseover: function(e){
					if(console.log)
						console.log(e);
				},
				infoWindow: {
					content: '<h1>".$marker->title."</h1><p>".$marker->content."</p><p>".$marker->address."<br/>".$marker->zip."<br/>".$marker->city."<br/>".$marker->state."</p>'
				}
			});
			
				";
				$markers_js .= $this_marker;
				
			}
		}
		
		echo "
	<script type='text/javascript'>
	
		var ".self::$instance_id.";
		
		$(document).ready(function(){
			var $".self::$instance_id."_form = $('#".self::$instance_id."_form');
		
			".self::$instance_id." = new GMaps({
				el: '#".self::$instance_id."',
				zoom: ".self::$attributes['zoom'].",
				lat: " . $geocode->lt . ",
				lng: " . $geocode->ln . ",
				scrollwheel: ".self::$attributes['scroll'].",
			});
			
			" . $markers_js . "
			       
			var ".self::$instance_id."_elem = document.querySelector('#".self::$instance_id."_radius');
			var ".self::$instance_id."_init = new Powerange(".self::$instance_id."_elem, { min: ".self::$form_attributes['min'].", max: ".self::$form_attributes['max'].", start: ".self::$attributes['radius']." });

			$".self::$instance_id."_form.submit(function(e){
				e.preventDefault();
				var zip_input = $('#".self::$instance_id."_zip').val().trim();
				var radius_input = $('#".self::$instance_id."_radius').val().trim();
				GMaps.geocode({
					address: zip_input,
					callback: function(results, status){
						if(status=='OK'){
							var latlng = results[0].geometry.location;
							".self::$instance_id.".setCenter(latlng.lat(), latlng.lng());
						}
					}
		        });
			});
			
			
		});
	</script>
	
		";
	}
}

gspots_Shortcode::init();
