<?php
/**
 * Adds a meta box to the post editing screen
 */
function gspots_custom_meta() {
	add_meta_box( 'gspots_meta', __( 'Location Details', 'gspots-textdomain' ), 'gspots_meta_callback', 'location' );
}
add_action( 'add_meta_boxes', 'gspots_custom_meta' );

/**
 * Outputs the content of the meta box
 */
function gspots_meta_callback( $post ) {
	wp_nonce_field( basename( __FILE__ ), 'gspots_nonce' );
	$gspots_stored_meta = get_post_meta( $post->ID );
	?>

	<p>
		<label for="meta-address-text" class="gspots-row-title"><?php _e( 'Address', 'gspots-textdomain' )?></label>
		<input type="text" name="meta-address-text" id="meta-address-text" value="<?php if ( isset ( $gspots_stored_meta['meta-address-text'] ) ) echo $gspots_stored_meta['meta-address-text'][0]; ?>" />
	</p>
	
	<p>
		<label for="meta-zip-text" class="gspots-row-title"><?php _e( 'Zip', 'gspots-textdomain' )?></label>
		<input type="text" name="meta-zip-text" id="meta-zip-text" value="<?php if ( isset ( $gspots_stored_meta['meta-zip-text'] ) ) echo $gspots_stored_meta['meta-zip-text'][0]; ?>" />
	</p>
	
	<p>
		<label for="meta-city-text" class="gspots-row-title"><?php _e( 'City', 'gspots-textdomain' )?></label>
		<input type="text" name="meta-city-text" id="meta-city-text" value="<?php if ( isset ( $gspots_stored_meta['meta-city-text'] ) ) echo $gspots_stored_meta['meta-city-text'][0]; ?>" />
	</p>
	
	<p>
		<label for="meta-state-text" class="gspots-row-title"><?php _e( 'State', 'gspots-textdomain' )?></label>
		<input type="text" name="meta-state-text" id="meta-state-text" value="<?php if ( isset ( $gspots_stored_meta['meta-state-text'] ) ) echo $gspots_stored_meta['meta-state-text'][0]; ?>" />
	</p>

	<p><small>Longitude and latitude are calculated automatically when the post is saved or published.</small></p>
	
	<p>
		<label for="meta-lng-text" class="gspots-row-title"><?php _e( 'Longitude', 'gspots-textdomain' )?></label>
		<input type="text" name="meta-lng-text" id="meta-lng-text" value="<?php if ( isset ( $gspots_stored_meta['meta-lng-text'] ) ) echo $gspots_stored_meta['meta-lng-text'][0]; ?>" disabled="disabled"/>
	</p>
	
	<p>
		<label for="meta-lat-text" class="gspots-row-title"><?php _e( 'Latitude', 'gspots-textdomain' )?></label>
		<input type="text" name="meta-lat-text" id="meta-lat-text" value="<?php if ( isset ( $gspots_stored_meta['meta-lat-text'] ) ) echo $gspots_stored_meta['meta-lat-text'][0]; ?>" disabled="disabled"/>
	</p>
	
	
	<?php
}

/**
 * Saves the custom meta input
 */
function gspots_meta_save( $post_id ) {
 
	// Checks save status
	$is_autosave = wp_is_post_autosave( $post_id );
	$is_revision = wp_is_post_revision( $post_id );
	$is_valid_nonce = ( isset( $_POST[ 'gspots_nonce' ] ) && wp_verify_nonce( $_POST[ 'gspots_nonce' ], basename( __FILE__ ) ) ) ? 'true' : 'false';
 
	// Exits script depending on save status
	if ( $is_autosave || $is_revision || !$is_valid_nonce ) {
		return;
	}
 
	// Checks for input and sanitizes/saves if needed
	if( isset( $_POST[ 'meta-address-text' ] ) ) {
		update_post_meta( $post_id, 'meta-address-text', sanitize_text_field( $_POST[ 'meta-address-text' ] ) );
	}
	// Checks for input and sanitizes/saves if needed
	if( isset( $_POST[ 'meta-zip-text' ] ) ) {
		update_post_meta( $post_id, 'meta-zip-text', sanitize_text_field( $_POST[ 'meta-zip-text' ] ) );
	}
	// Checks for input and sanitizes/saves if needed
	if( isset( $_POST[ 'meta-city-text' ] ) ) {
		update_post_meta( $post_id, 'meta-city-text', sanitize_text_field( $_POST[ 'meta-city-text' ] ) );
	}
	// Checks for input and sanitizes/saves if needed
	if( isset( $_POST[ 'meta-state-text' ] ) ) {
		update_post_meta( $post_id, 'meta-state-text', sanitize_text_field( $_POST[ 'meta-state-text' ] ) );
	}
	// Checks for input and sanitizes/saves if needed
	if( isset( $_POST[ 'meta-lng-text' ] ) ) {
		update_post_meta( $post_id, 'meta-lng-text', sanitize_text_field( $_POST[ 'meta-lng-text' ] ) );
	}
	// Checks for input and sanitizes/saves if needed
	if( isset( $_POST[ 'meta-lat-text' ] ) ) {
		update_post_meta( $post_id, 'meta-lat-text', sanitize_text_field( $_POST[ 'meta-lat-text' ] ) );
	}
	// Calculate Long and lat from google
	if( isset( $_POST[ 'meta-address-text' ] ) || isset( $_POST[ 'meta-zip-text' ] ) || isset( $_POST[ 'meta-city-text' ] ) || isset( $_POST[ 'meta-state-text' ] ) ) {
		$region = "US";
		$address = sanitize_text_field( $_POST[ 'meta-address-text' ] ) . " ";
		$zip = sanitize_text_field( $_POST[ 'meta-zip-text' ] ) . " ";
		$city = sanitize_text_field( $_POST[ 'meta-city-text' ] ) . " ";
		$state = sanitize_text_field( $_POST[ 'meta-state-text' ] );
		//$address = "89117";
		$full_address = $address . $zip . $city . $state;
		
		$url = "http://maps.google.com/maps/api/geocode/json?address=".urlencode($full_address)."&sensor=false";
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_PROXYPORT, 3128);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		$response = curl_exec($ch);
		curl_close($ch);
		$response_a = json_decode($response);
		$lat = $response_a->results[0]->geometry->location->lat;
		$long = $response_a->results[0]->geometry->location->lng;
		
		update_post_meta( $post_id, 'meta-lng-text', $long );
		update_post_meta( $post_id, 'meta-lat-text', $lat );
		
	}

}
add_action( 'save_post', 'gspots_meta_save' );

/**
 * Adds the meta box stylesheet when appropriate
 */
function gspots_admin_styles(){
	global $typenow;
	if( $typenow == 'location' ) {
		wp_enqueue_style( 'gspots_meta_box_styles', $url . 'style/gspots.css' );
	}
}
add_action( 'admin_print_styles', 'gspots_admin_styles' );
