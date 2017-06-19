<?php

/**
 * Plugin Name: Secretary
 * Description: Automatic quality-assurance checks, ensuring your articles meet editorial guidelines.
 * Version: 1.0.0
 * Author: ChrisBAshton
 * Author URI: http://ashton.codes
 */

require_once(dirname(__FILE__) . '/admin/init.php');

// ###########################################################################
// IMPORTANT - need to run flush_rewrite_rules once after activation,
// otherwise custom post types cannot be found (404 error).
// Only want to run it once because it is a very expensive operation, hence
// we only do it on activation/deactivation.
//
// See: http://codex.wordpress.org/Function_Reference/flush_rewrite_rules
// ###########################################################################
register_activation_hook( __FILE__, 'secretary__myplugin_flush_rewrites' );
register_deactivation_hook( __FILE__, 'secretary__myplugin_flush_rewrites_deactivate' );

function secretary__myplugin_flush_rewrites() {
    flush_rewrite_rules();
}

function secretary__myplugin_flush_rewrites_deactivate() {
    flush_rewrite_rules();
}
