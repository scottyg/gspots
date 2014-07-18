<?php
/**
 * Geocache
 */
 
// Check if geocache is writable
if ( is_writable( GSPOTS_CACHE_LOCATION ) ) {
	
	define('GSPOTS_CACHE_STATUS', TRUE );
	
} else {
	
	define('GSPOTS_CACHE_STATUS', FALSE );
	
	function geocache_admin_notice() {
		?>
		<div class="error">
			<p><?php _e( 'Please make ' . GSPOTS_CACHE_LOCATION . ' writable.', 'gspots-text-domain' ); ?></p>
		</div>
		<?php
	}
	add_action( 'admin_notices', 'geocache_admin_notice' );
	
}

function geocache( $address ) {

	if( isset( $address ) ) {
		
		$cache = null;
		$geo_exists = false;
		
		if( GSPOTS_CACHE_STATUS == TRUE ) {
		
			$cache = json_decode( file_get_contents ( GSPOTS_CACHE_LOCATION ) );
			
			if ( $cache != null) {
			
				foreach( $cache as $geo ) {
					if( $geo->address == $address ) {
						$return['address'] = $address;
						$return['ln'] = $geo->ln;
						$return['lt'] = $geo->lt;
						$geo_exists = true;
					}
				}
				
			} else {
				$cache = array();
			}
		}
		if ( $geo_exists == false ) {
			$url = "http://maps.google.com/maps/api/geocode/json?address=" . urlencode( $address ) . "&sensor=false";
			$ch = curl_init();
			curl_setopt( $ch, CURLOPT_URL, $url );
			curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
			curl_setopt( $ch, CURLOPT_PROXYPORT, 3128 );
			curl_setopt( $ch, CURLOPT_SSL_VERIFYHOST, 0 );
			curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, 0 );
			$response = curl_exec( $ch );
			curl_close( $ch );
			$response_a = json_decode( $response );
			
			$return['address'] = $address;
			$return['ln'] = $response_a->results[0]->geometry->location->lng;
			$return['lt'] = $response_a->results[0]->geometry->location->lat;
			
			$cache[] = array(
				'address' => $address,
				'ln' => $response_a->results[0]->geometry->location->lng,
				'lt' => $response_a->results[0]->geometry->location->lat
			);
			file_put_contents ( GSPOTS_CACHE_LOCATION, json_encode($cache) );
			
		}
				
	} else {
		$return['error'] = '10: ' . 'No address input';
	}
	
	return json_encode( $return );

}
