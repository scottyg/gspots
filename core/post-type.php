<?php
/**
 * Location Post Type
 */
function location_init() {
	register_post_type( 'location', array(
		'hierarchical'      => false,
		'public'            => true,
		'show_in_nav_menus' => true,
		'show_ui'           => true,
		'supports'          => array( 'title', 'editor' ),
		'has_archive'       => true,
		'query_var'         => true,
		'rewrite'           => true,
		'labels'            => array(
			'name'                => __( 'Locations', 'gspot_location' ),
			'singular_name'       => __( 'Location', 'gspot_location' ),
			'all_items'           => __( 'Locations', 'gspot_location' ),
			'new_item'            => __( 'New Location', 'gspot_location' ),
			'add_new'             => __( 'Add New', 'gspot_location' ),
			'add_new_item'        => __( 'Add New Location', 'gspot_location' ),
			'edit_item'           => __( 'Edit Location', 'gspot_location' ),
			'view_item'           => __( 'View Location', 'gspot_location' ),
			'search_items'        => __( 'Search Locations', 'gspot_location' ),
			'not_found'           => __( 'No Locations found', 'gspot_location' ),
			'not_found_in_trash'  => __( 'No Locations found in trash', 'gspot_location' ),
			'parent_item_colon'   => __( 'Parent Location', 'gspot_location' ),
			'menu_name'           => __( 'Locations', 'gspot_location' ),
		),
	) );

}
add_action( 'init', 'location_init' );

function location_updated_messages( $messages ) {
	global $post;

	$permalink = get_permalink( $post );

	$messages['location'] = array(
		0 => '', // Unused. Messages start at index 1.
		1 => sprintf( __('Location updated. <a target="_blank" href="%s">View Location</a>', 'gspot_location'), esc_url( $permalink ) ),
		2 => __('Custom field updated.', 'gspot_location'),
		3 => __('Custom field deleted.', 'gspot_location'),
		4 => __('Location updated.', 'gspot_location'),
		/* translators: %s: date and time of the revision */
		5 => isset($_GET['revision']) ? sprintf( __('Location restored to revision from %s', 'gspot_location'), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
		6 => sprintf( __('Location published. <a href="%s">View Location</a>', 'gspot_location'), esc_url( $permalink ) ),
		7 => __('Location saved.', 'gspot_location'),
		8 => sprintf( __('Location submitted. <a target="_blank" href="%s">Preview Location</a>', 'gspot_location'), esc_url( add_query_arg( 'preview', 'true', $permalink ) ) ),
		9 => sprintf( __('Location scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview Location</a>', 'gspot_location'),
		// translators: Publish box date format, see http://php.net/date
		date_i18n( __( 'M j, Y @ G:i' ), strtotime( $post->post_date ) ), esc_url( $permalink ) ),
		10 => sprintf( __('Location draft updated. <a target="_blank" href="%s">Preview Location</a>', 'gspot_location'), esc_url( add_query_arg( 'preview', 'true', $permalink ) ) ),
	);

	return $messages;
}
add_filter( 'post_updated_messages', 'location_updated_messages' );
