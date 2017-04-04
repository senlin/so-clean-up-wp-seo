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
$table         = is_multisite() ? $wpdb->base_prefix . 'sitemeta' : $wpdb->base_prefix . 'options';
$column        = is_multisite() ? 'meta_key' : 'option_name';
$delete_string = 'DELETE FROM ' . $table . ' WHERE ' . $column . ' LIKE %s LIMIT 1000';
$wpdb->query( $wpdb->prepare( $delete_string, array( '%cuws_%' ) ) );
