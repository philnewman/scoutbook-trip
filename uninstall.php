<?php

// if uninstall.php is not called by WordPress, die
if (!defined('WP_UNINSTALL_PLUGIN')) {
    die;
}

global $wpdb;

$wpdb->delete("wp_posts", array('post_type'=>'trip'));
$qry = "DELETE FROM wp_postmeta WHERE post_id NOT IN (SELECT id FROM wp_posts)";
$wpdb->get_results($qry);
