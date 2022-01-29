<?php

/**
 * Plugin Name: Secretary
 * Description: Automatic quality-assurance checks, ensuring your articles meet editorial guidelines.
 * Version: 1.0.3
 * Author: ChrisBAshton
 * Author URI: http://ashton.codes
 */

require_once(dirname(__FILE__) . '/vendor/autoload.php');

require_all('controllers/*.php');
require_all('views/*.php');
require_all('rules/*.php');

SecretaryConfig::init();
SecretaryPostAdmin::init();

function require_all($dirName) {
    $files = glob(dirname(__FILE__) . '/' . $dirName);
    foreach ($files as $file) {
        require($file);
    }
}

function get_the_content_by_id($postID) {
    $post = &get_post($postID);
    setup_postdata($post);
    $content = get_the_content();
    wp_reset_postdata($post);
    return $content;
}
