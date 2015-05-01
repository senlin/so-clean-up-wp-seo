<?php
/**
 * Plugin Name: SO Clean Up WP SEO
 * Plugin URI:  http://so-wp.com/plugin/so-clean-up-wp-seo/
 * Description: Clean up several things that the WordPress SEO plugin adds to your WordPress Dashboard
 * Author:      SO WP
 * Author URI:  http://so-wp.com/plugins/
 * Version:     1.3.1
 * License:     GPL3+
 */

/**
 * Prevent direct access to files
 *
 * @since 1.0
 */
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Version check; any WP version under 4.0 is not supported (if only to "force" users to stay up to date)
 * 
 * adapted from example by Thomas Scholz (@toscho) http://wordpress.stackexchange.com/a/95183/2015, Version: 2013.03.31, Licence: MIT (http://opensource.org/licenses/MIT)
 *
 * @since 1.1
 */

//Only do this when on the Plugins page.
if ( ! empty ( $GLOBALS['pagenow'] ) && 'plugins.php' === $GLOBALS['pagenow'] )
	
	/* so_cuws_ prefix is derived from [so] [c]lean [u]p [w]p [s]eo. */
	add_action( 'admin_notices', 'so_cuws_check_admin_notices', 0 );

function so_cuws_min_wp_version() {
	global $wp_version;
	$require_wp = '4.0';
	$update_url = get_admin_url( null, 'update-core.php' );

	$errors = array();

	if ( version_compare( $wp_version, $require_wp, '<' ) ) 

		$errors[] = "You have WordPress version $wp_version installed, but <b>this plugin requires at least WordPress $require_wp</b>. Please <a href='$update_url'>update your WordPress version</a>.";

	return $errors; 
}

function so_cuws_check_admin_notices() {
	
	$errors = so_cuws_min_wp_version();

	if ( empty ( $errors ) )
		return;

	// Suppress "Plugin activated" notice.
	unset( $_GET['activate'] );

	// this plugin's name
	$name = get_file_data( __FILE__, array ( 'Plugin Name' ), 'plugin' );

	printf( __( '<div class="error"><p>%1$s</p><p><i>%2$s</i> has been deactivated.</p></div>', 'so-clean-up-wp-seo' ),
		join( '</p><p>', $errors ),
		$name[0]
	);
	deactivate_plugins( plugin_basename( __FILE__ ) );

}

/**
 * This function checks whether the WordPress SEO plugin is active (it needs to be active for SO Clean Up WP SEO to have any use)
 *
 * @since 1.1
 */

$plugins = get_option( 'active_plugins' );

$required_plugin = 'wordpress-seo/wp-seo.php';

// multisite throws the error message by default, because the plugin is installed on the network site, therefore check for multisite
if ( ! in_array( $required_plugin , $plugins ) && ! is_multisite() ) {

	add_action( 'admin_notices', 'so_cuws_warning' );

}

/**
 * Show warning if the WordPress SEO plugin has not been installed
 *
 * @since 1.1
 */

function so_cuws_warning() {
    
    // display the warning message
    echo '<div class="message error"><p>';
    
    _e( 'The <strong>SO Clean Up WP SEO plugin</strong> only works if you have the WordPress SEO plugin installed.', 'so-clean-up-wp-seo' );
    
    echo '</p></div>';
    
}

/**
 * if the WordPress SEO plugin has been installed, add the actions and filters that clean up the entire WP SEO experience
 *
 * @since 1.0
 */
if ( in_array( $required_plugin , $plugins ) ) {
	
	add_action( 'admin_head', 'so_cuws_hide_sidebar_ads' );	
	
	add_action( 'admin_bar_menu', 'so_cuws_remove_adminbar_settings', 999 ); // since 1.3
	
	add_filter( 'option_wpseo', 'so_cuws_remove_about_tour' );

	if ( function_exists( 'wpseo_use_page_analysis' ) ) {
		add_filter( 'wpseo_use_page_analysis', '__return_false' );
	}

}

// Remove irritating adds sidebar
// @since 1.3.1 remove tour option/introduction
function so_cuws_hide_sidebar_ads() {
	echo '<style type="text/css">
	#sidebar-container.wpseo_content_cell, .wpseotab.active > p:nth-child(6), .wpseotab.active > p:nth-child(7) {display:none;}
	</style>';
}

// Remove Settings submenu in admin bar
// also shows how to remove other menus
// @since 1.3 - inspired by [Lee Rickler](https://profiles.wordpress.org/lee-rickler/)
function so_cuws_remove_adminbar_settings() {
	
	global $wp_admin_bar;   
	
	// remove the entire menu
	//$wp_admin_bar->remove_node( 'wpseo-menu' );
	
	// remove WordPress SEO Settings
	$wp_admin_bar->remove_node( 'wpseo-settings' );
	
	// remove keyword research information
	//$wp_admin_bar->remove_node( 'wpseo-kwresearch' );

}

/**
 * After each update of the Yoast WordPress SEO plugin, the user is redirected
 * to the About page of the plugin (admin.php?page=wpseo_dashboard&intro=1#top#new)
 *
 * This is irritating at best and very unprofessional on websites of (large) companies and organisations
 * with many users that have the Administrator Role.
 *
 * This filter globally sets the "see about page" setting and the "ignore tour" setting to true
 *
 * @source: github.com/Yoast/wordpress-seo/pull/2235#issuecomment-95059096
 */
function so_cuws_remove_about_tour( $option ) {

	$option['seen_about'] = true;
	$option['ignore_tour'] = true;

	return $option;

}
