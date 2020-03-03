<?php
/**
 * This file runs when the plugin in uninstalled (deleted).
 * This will not run when the plugin is deactivated.
 * Ideally you will add all your clean-up scripts here
 * that will clean-up unused meta, options, etc. in the database.
 *
 * @package WordPress Plugin Template/Uninstall
 */

// If plugin is not being uninstalled, exit (do nothing).
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

register_deactivation_hook( __FILE__, 'wapi_updater_deactivation' );

function wapi_updater_deactivation() {
    wp_clear_scheduled_hook( 'wapi_updater_job' );
}

// Do something here if plugin is being uninstalled.
