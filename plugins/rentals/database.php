<?php

$mini_rental_version = '1.0';
$current_rental_version = get_option('mini_rental_version');

function mini_rental_install() {
	global $wpdb;
	global $mini_rental_version;

	$charset_collate = $wpdb->get_charset_collate();
	$table_name = $wpdb->prefix . 'rental_blocked_days';

	$sql = "CREATE TABLE $table_name (
		id bigint(20) NOT NULL AUTO_INCREMENT,
		property_id bigint(20) NOT NULL,
		blocked_date DATE NOT NULL,
		user_id bigint(20) NOT NULL,
		CONSTRAINT blockDate UNIQUE (property_id,blocked_date),
		PRIMARY KEY  (id)
	) $charset_collate;";

	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	dbDelta( $sql );
	add_option( 'mini_rental_version', $mini_rental_version );
}
add_action("after_switch_theme", "mini_rental_install");

// And here goes the uninstallation function:
function remove_mini_rental_install(){
    global $wpdb;
    $table_name = $wpdb->prefix . 'blocked_days';
    $wpdb->query( "DROP TABLE IF EXISTS $table_name" );
    delete_option('mini_rental_version');
}
//add_action("switch_theme", "remove_mini_rental_install");
