<?php
/**
 * Plugin Name: SO Hide SEO Bloat
 * Plugin URI:  http://so-wp.com/plugin/so-clean-up-wp-seo/
 * Description: Hide most of the bloat that the Yoast SEO plugin adds to your WordPress Dashboard
 * Version:     2.1.0
 * Author:      SO WP
 * Author URI:  http://so-wp.com/plugins/
 * Text Domain: so-clean-up-wp-seo
 * Domain Path: /languages
 * License:     GPL-3.0+
 * License URI: http://www.gnu.org/licenses/gpl-3.0.txt
 */

// don't load the plugin file directly
if ( ! defined( 'ABSPATH' ) ) exit;

// Load plugin class files
require_once( 'includes/class-so-clean-up-wp-seo.php' );
require_once( 'includes/class-so-clean-up-wp-seo-settings.php' );

// Load plugin libraries
require_once( 'admin/class-so-clean-up-wp-seo-admin-api.php' );

/**
 * Returns the main instance of CUWS to prevent the need to use globals.
 *
 * @since  v2.0.0
 * @return object CUWS
 */
function CUWS () {
	$instance = CUWS::instance( __FILE__, '2.1.0' );

	if ( is_null( $instance->settings ) ) {
		$instance->settings = CUWS_Settings::instance( $instance );
	}

	return $instance;
}

CUWS();
