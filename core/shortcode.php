<?php
/**
 * Map Shortcode
 */
 
// [gspot radius="radius-value-in-miles"]
function gspot_func( $atts ) {
    $a = shortcode_atts( array(
        'class' => '.gspot',
        'radius' => 50,
    ), $atts );

    return "radius = {$a['radius']}";
}
add_shortcode( 'gspot', 'gspot_func' );