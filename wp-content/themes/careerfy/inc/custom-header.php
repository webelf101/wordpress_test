<?php
/**
 * Sample implementation of the Custom Header feature.
 *
 * @package Careerfy
 */

function careerfy_custom_header_setup() {
	add_theme_support( 'custom-header', apply_filters( 'careerfy_custom_header_args', array(
		'default-image'          => '',
		'default-text-color'     => '000000',
		'width'                  => 1000,
		'height'                 => 250,
		'flex-height'            => true,
		'wp-head-callback'       => '',
	) ) );
}
add_action( 'after_setup_theme', 'careerfy_custom_header_setup' );
