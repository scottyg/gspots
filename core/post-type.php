<?php
/**
 * Spot Post Type
 */
function spot_init() {
	register_post_type( 'spot', array(
		'hierarchical'      => false,
		'public'            => true,
		'show_in_nav_menus' => true,
		'show_ui'           => true,
		'supports'          => array( 'title', 'editor' ),
		'has_archive'       => true,
		'query_var'         => true,
		'rewrite'           => true,
		'menu_icon' 		=> '',
		'labels'            => array(
			'name'                => __( 'Spots', 'gspots_spot' ),
			'singular_name'       => __( 'Spot', 'gspots_spot' ),
			'all_items'           => __( 'All Spots', 'gspots_spot' ),
			'new_item'            => __( 'New Spot', 'gspots_spot' ),
			'add_new'             => __( 'Add New', 'gspots_spot' ),
			'add_new_item'        => __( 'Add New Spot', 'gspots_spot' ),
			'edit_item'           => __( 'Edit Spot', 'gspots_spot' ),
			'view_item'           => __( 'View Spot', 'gspots_spot' ),
			'search_items'        => __( 'Search Spots', 'gspots_spot' ),
			'not_found'           => __( 'No Spots found', 'gspots_spot' ),
			'not_found_in_trash'  => __( 'No Spots found in trash', 'gspots_spot' ),
			'parent_item_colon'   => __( 'Parent Spot', 'gspots_spot' ),
			'menu_name'           => __( 'Spots', 'gspots_spot' ),
		),
	) );

}
add_action( 'init', 'spot_init' );

function spot_updated_messages( $messages ) {
	global $post;

	$permalink = get_permalink( $post );

	$messages['spot'] = array(
		0 => '', // Unused. Messages start at index 1.
		1 => sprintf( __('Spot updated. <a target="_blank" href="%s">View Spot</a>', 'gspots_spot'), esc_url( $permalink ) ),
		2 => __('Custom field updated.', 'gspots_spot'),
		3 => __('Custom field deleted.', 'gspots_spot'),
		4 => __('Spot updated.', 'gspots_spot'),
		/* translators: %s: date and time of the revision */
		5 => isset($_GET['revision']) ? sprintf( __('Spot restored to revision from %s', 'gspots_spot'), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
		6 => sprintf( __('Spot published. <a href="%s">View Spot</a>', 'gspots_spot'), esc_url( $permalink ) ),
		7 => __('Spot saved.', 'gspots_spot'),
		8 => sprintf( __('Spot submitted. <a target="_blank" href="%s">Preview Spot</a>', 'gspots_spot'), esc_url( add_query_arg( 'preview', 'true', $permalink ) ) ),
		9 => sprintf( __('Spot scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview Spot</a>', 'gspots_spot'),
		// translators: Publish box date format, see http://php.net/date
		date_i18n( __( 'M j, Y @ G:i' ), strtotime( $post->post_date ) ), esc_url( $permalink ) ),
		10 => sprintf( __('Spot draft updated. <a target="_blank" href="%s">Preview Spot</a>', 'gspots_spot'), esc_url( add_query_arg( 'preview', 'true', $permalink ) ) ),
	);

	return $messages;
}
add_filter( 'post_updated_messages', 'spot_updated_messages' );


//Menu

 
function add_menu_icons_styles(){
?>
 
<style>
#adminmenu .menu-icon-spot div.wp-menu-image:before {
	content: "\f230";
}
</style>
 
<?php
}
add_action( 'admin_head', 'add_menu_icons_styles' );
