<?php
/**
 * Merchant Uninstall
 */

defined( 'WP_UNINSTALL_PLUGIN' ) || exit;

global $wpdb, $wp_version;

/*
 * Only remove ALL merchant & merchant-pro data if WC_REMOVE_ALL_DATA constant is set to true in user's
 * wp-config.php. This is to prevent data loss when deleting the plugin from the backend
 * and to ensure only the site owner can perform this action. (Copied from WooCommerce).
 */
if ( defined( 'WC_REMOVE_ALL_DATA' ) && true === WC_REMOVE_ALL_DATA ) {
	$wpdb->query( $wpdb->prepare( "DROP TABLE IF EXISTS %s", $wpdb->prefix . 'merchant_sales_notifications' ) );
}