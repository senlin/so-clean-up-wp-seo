<?php

/**
 * 
 * This file runs when the plugin in uninstalled (deleted).
 * This will not run when the plugin is deactivated.
 * Ideally you will add all your clean-up scripts here
 * that will clean-up unused meta, options, etc. in the database.
 *
 */

// If uninstall not called from WordPress, then exit (do nothing)
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

// Do something here if plugin is being uninstalled.

/**
 * Remove all cuws_ options from the database
 *
 * @since v2.0.0
 */
global $wpdb;
$wpdb->query( "DELETE FROM `{$wpdb->prefix}options` WHERE `option_name` LIKE '%cuws_%'" );


