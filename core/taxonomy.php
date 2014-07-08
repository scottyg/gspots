<?php
/**
 * Location Type Taxonomy For Location Post Type
 */
function type_init() {
	register_taxonomy( 'type', array( 'location' ), array(
		'hierarchical'      => false,
		'public'            => true,
		'show_in_nav_menus' => true,
		'show_ui'           => true,
		'show_admin_column' => false,
		'query_var'         => true,
		'rewrite'           => true,
		'capabilities'      => array(
			'manage_terms'  => 'edit_posts',
			'edit_terms'    => 'edit_posts',
			'delete_terms'  => 'edit_posts',
			'assign_terms'  => 'edit_posts'
		),
		'labels'            => array(
			'name'                       => __( 'Location Types', 'gspot_location_type' ),
			'singular_name'              => _x( 'Location Type', 'taxonomy general name', 'gspot_location_type' ),
			'search_items'               => __( 'Search Location Types', 'gspot_location_type' ),
			'popular_items'              => __( 'Popular Location Types', 'gspot_location_type' ),
			'all_items'                  => __( 'All Location Types', 'gspot_location_type' ),
			'parent_item'                => __( 'Parent Location Type', 'gspot_location_type' ),
			'parent_item_colon'          => __( 'Parent Location Type:', 'gspot_location_type' ),
			'edit_item'                  => __( 'Edit Location Type', 'gspot_location_type' ),
			'update_item'                => __( 'Update Location Type', 'gspot_location_type' ),
			'add_new_item'               => __( 'New Location Type', 'gspot_location_type' ),
			'new_item_name'              => __( 'New Location Type', 'gspot_location_type' ),
			'separate_items_with_commas' => __( 'Location Types separated by comma', 'gspot_location_type' ),
			'add_or_remove_items'        => __( 'Add or remove Location Types', 'gspot_location_type' ),
			'choose_from_most_used'      => __( 'Choose from the most used Location Types', 'gspot_location_type' ),
			'menu_name'                  => __( 'Location Types', 'gspot_location_type' ),
		),
	) );

}
add_action( 'init', 'type_init' );
