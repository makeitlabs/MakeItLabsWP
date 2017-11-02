<?php
/**
* Plugin Name: Modal For Elementor
* Description: Modal plugin for Elementor Page Builder
* Version: 0.1.6
* Author: Luis Marques
* Author URI: https://facebook.com/Luisbeonline
* Plugin URI: https://beonline.pt/
* Text Domain: modal-for-elementor
* License: GPLv3
*/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// Load the plugin after Elementor (and other plugins) are loaded
add_action( 'plugins_loaded', function() {
	// Load localization file
	load_plugin_textdomain( 'modal-popup', FALSE, basename( dirname( __FILE__ ) ) . '/languages/' );

	// Notice if the Elementor is not active
	if ( ! did_action( 'elementor/loaded' ) ) {
		add_action( 'admin_notices', 'hello_world_fail_load' );
		return;
	}

	// Check version required
	$elementor_version_required = '1.0.0';
	if ( ! version_compare( ELEMENTOR_VERSION, $elementor_version_required, '>=' ) ) {
		add_action( 'admin_notices', 'hello_world_fail_load_out_of_date' );
		return;
	}

	// Require the main plugin file
	require( __DIR__ . '/plugin.php' );
} );




add_action( 'wp_enqueue_scripts', 'register_popup_style' );
function register_popup_style() {
	wp_enqueue_style( 'bootstrap', plugin_dir_url( __FILE__ ) . 'css/bootstrap.css' );
	wp_enqueue_style( 'modal-popup', plugin_dir_url( __FILE__ ) . 'css/popup.css', array( 'bootstrap' ) );
	
	if ( is_rtl() ) {
		wp_enqueue_style(
			'modal-popup-rtl',
			plugin_dir_url( __FILE__ ) . 'css/rtl.popup.css',
			array ( 'modal-popup' )
		);
	}
	
	wp_enqueue_script( 'bootstrap', plugin_dir_url( __FILE__ ) . 'js/bootstrap.js', array( 'jquery' ), null, true );
	wp_enqueue_script( 'modal-popup-js', plugin_dir_url( __FILE__ ) . 'js/popup.js', array( 'jquery', 'bootstrap' ), null, true );
}

/* create new custom post type named popup */
add_action( 'init', 'create_popup_post_type' );

function create_popup_post_type() {
  register_post_type( 'elementor-popup',
    array(
        'labels' => array(
        'name' => __( 'Elementor Popups', 'modal-popup'),
        'singular_name' => __( 'Popup', 'modal-popup'),
		'all_items' => __( 'All Popups', 'modal-popup'),
		'add_new_item' => __( 'Add New Popup', 'modal-popup'),
		'new_item' => __( 'Add New Popup', 'modal-popup'),
		'add_new' => __( 'Add New Popup', 'modal-popup'),
		'edit_item' => __( 'Edit Popup', 'modal-popup'),
      ),
      'has_archive' => false,
      'rewrite' => array('slug' => 'elementor-popup'),
      'menu_icon'             => 'dashicons-slides',
	  'public' => true,
	  'exclude_from_search' => true,
    )
  );
  add_post_type_support( 'elementor-popup', 'elementor' );
}