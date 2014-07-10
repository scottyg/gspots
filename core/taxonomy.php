<?php
/**
 * Type Taxonomy For Spot Post Type
 */
function type_init() {
	register_taxonomy( 'type', array( 'spot' ), array(
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
			'name'                       => __( 'Types', 'gspots_spot_type' ),
			'singular_name'              => _x( 'Type', 'taxonomy general name', 'gspots_spot_type' ),
			'search_items'               => __( 'Search Types', 'gspots_spot_type' ),
			'popular_items'              => __( 'Popular Types', 'gspots_spot_type' ),
			'all_items'                  => __( 'All Types', 'gspots_spot_type' ),
			'parent_item'                => __( 'Parent Type', 'gspots_spot_type' ),
			'parent_item_colon'          => __( 'Parent Type:', 'gspots_spot_type' ),
			'edit_item'                  => __( 'Edit Type', 'gspots_spot_type' ),
			'update_item'                => __( 'Update Type', 'gspots_spot_type' ),
			'add_new_item'               => __( 'New Type', 'gspots_spot_type' ),
			'new_item_name'              => __( 'New Type', 'gspots_spot_type' ),
			'separate_items_with_commas' => __( 'Types separated by comma', 'gspots_spot_type' ),
			'add_or_remove_items'        => __( 'Add or remove Types', 'gspots_spot_type' ),
			'choose_from_most_used'      => __( 'Choose from the most used Types', 'gspots_spot_type' ),
			'menu_name'                  => __( 'Types', 'gspots_spot_type' ),
		),
	) );

}
add_action( 'init', 'type_init' );
