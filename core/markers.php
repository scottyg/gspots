<?php
/**
 * Markers
 */

class markers {
	
	public function get_json( $options ) {
	
		if( $options == null ) {
			$options = array(
				type => 'all'
			);
		}
		
		$entries = array();
		$args = array( 'post_type' => 'spot', 'posts_per_page' => 100 );
		$loop = new WP_Query( $args );
		
		while ( $loop->have_posts() ) : $loop->the_post();
			
			$meta = get_post_meta( get_the_ID() );
			$entry = array(
				address => $meta["meta-address-text"][0],
				city => $meta["meta-city-text"][0],
				content => get_the_content(),
				ln => $meta["meta-lng-text"][0],
				lt => $meta["meta-lat-text"][0],
				state => $meta["meta-state-text"][0],
				title => get_the_title(),
				type =>  wp_get_post_terms(get_the_ID(), 'type', array("fields" => "slugs")),
				zip => $meta["meta-zip-text"][0],
			);
			$entries[] = $entry;
			
		endwhile;
		
		$data['options'] = $options;
		$data['entries'] = $entries;
		$return = json_encode($data);
		
		return $return;
		
	}

}
