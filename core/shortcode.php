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
				'submit' => 'Search',
				'zip'=> 'Zip'
			), $atts );
		
		$form_div = '<form id="'.self::$instance_id.'_form" class="'.self::$form_attributes['class'].'">
			<div><input type="text" id="'.self::$instance_id.'_zip" name="'.self::$instance_id.'_zip" placeholder="'.self::$form_attributes['zip'].'"/></div>
			<input type="text" id="'.self::$instance_id.'_radius" name="'.self::$instance_id.'_radius" />
			<div><input type="submit" value="'.self::$form_attributes['submit'].'"/></div>
		</form>';
		
		echo $form_div;
	}

	static function register_script() {
		
		wp_register_script('google-maps-api', 'http://maps.google.com/maps/api/js?key=' . get_option( 'key' ) . '&sensor=true', '', NULL, true);
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
				icon: '" . GSPOTS_URL . "core/markers/marker_red.png',
				click: function(e){
					if(console.log)
						console.log(e);
				},
				mouseover: function(e){
					if(console.log)
						console.log(e);
				},
				infoWindow: {
					content: '<h1>".$marker->title."</h1><p>".$marker->content."</p><p>".$marker->address."<br/>".$marker->zip."<br/>".$marker->city."<br/>".$marker->state."</p>'
				},
				fences: [fence_radius],
				outside: function(m, f){
					this.setMap(null);
				}
			});
			
				";
				$markers_js .= $this_marker;
				
			}
		}
		
		if( !$geocode->error ){
			echo "
		<script src='https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js'></script>
	
		<script type='text/javascript'>
		
			var ".self::$instance_id.";
			
			$(document).ready(function(){
				var $".self::$instance_id."_form = $('#".self::$instance_id."_form');
				
				".self::$instance_id." = new GMaps({
					el: '#".self::$instance_id."',
					lat: " . $geocode->lt . ",
					lng: " . $geocode->ln . ",
					scrollwheel: ".self::$attributes['scroll'].",
					zoom: ".self::$attributes['zoom'].",
				});
				fence_radius = ".self::$instance_id.".drawCircle({
					lat: ".$geocode->lt.",
					lng: ".$geocode->ln.",
					radius: ".self::$attributes['radius']*1609.34.",
					strokeColor: '#BBD8E9',
					strokeOpacity: 0,
					strokeWeight: 0,
					fillColor: '#BBD8E9',
					fillOpacity: 0
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
								
								" . $markers_js . "
	
								do_fence(latlng.lat(), latlng.lng(), radius_input);
								
								".self::$instance_id.".setCenter(latlng.lat(), latlng.lng());
								
							}
						}
					});
				});
				function do_fence(lt, ln, rad) {
					var markers = ".self::$instance_id.".markers;
					for (var i = 0; i < markers.length; i++) {
						
						if (distance(markers[i]['position']['k'],markers[i]['position']['B'], lt, ln, 'N') >= rad){
						  markers[i].setMap(null);
						}	
					}
				}
				do_fence(".$geocode->lt.", ".$geocode->ln.", ".self::$attributes['radius'].");
	
				function distance(lat1, lon1, lat2, lon2, unit) {
				
					var radlat1 = Math.PI * lat1/180;
					var radlat2 = Math.PI * lat2/180;
					var radlon1 = Math.PI * lon1/180;
					var radlon2 = Math.PI * lon2/180;
					var theta = lon1-lon2;
					var radtheta = Math.PI * theta/180;
					var dist = Math.sin(radlat1) * Math.sin(radlat2) + Math.cos(radlat1) * Math.cos(radlat2) * Math.cos(radtheta);
					dist = Math.acos(dist);
					dist = dist * 180/Math.PI;
					dist = dist * 60 * 1.1515;
					if (unit=='K') { dist = dist * 1.609344; }
					if (unit=='N') { dist = dist * 0.8684; }
					return dist;
				}
				
			});
		</script>
		
			";	
		}
	}
}

gspots_Shortcode::init();
