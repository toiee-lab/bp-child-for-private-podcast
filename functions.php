<?php
/**
 * Businesspress-ppcast Theme functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package businesspress-ppcast
 */

require_once 'includes/tgmpa.php';

require_once 'includes/plugin-update-checker/plugin-update-checker.php';
$myUpdateChecker = Puc_v4_Factory::buildUpdateChecker(
	'https://github.com/toiee-lab/bp-child-for-private-podcast',
	__FILE__,
	'businesspress-ppcast'
);

add_action( 'wp_enqueue_scripts', 'businesspress_parent_theme_enqueue_styles' );

/**
 * Enqueue scripts and styles.
 */
function businesspress_parent_theme_enqueue_styles() {
	wp_enqueue_style( 'businesspress-style', get_template_directory_uri() . '/style.css' );
	wp_enqueue_style( 'businesspress-ppcast-style',
		get_stylesheet_directory_uri() . '/style.css',
		array( 'businesspress-style' )
	);
}

if( function_exists('acf_add_local_field_group') && function_exists( 'ssp_beta_check' ) ) {
	require_once 'includes/acf.php';

	require_once 'includes/membersite.php';

	require_once 'includes/ssp-extension.php';

	require_once 'includes/frontend.php';
}

