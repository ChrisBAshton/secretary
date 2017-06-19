<?php
require_once(dirname(__FILE__) . '/../vendor/autoload.php');
require_once(dirname(__FILE__) . '/views/secretary.php');

add_action('admin_notices', 'show_report_at_top_of_post_admin_editor');
add_action('admin_print_styles', function () {
    echo '<link rel="stylesheet" href="'. plugins_url('views/secretary.css', __FILE__ ) .'" />';
});

function show_report_at_top_of_post_admin_editor() {
    global $post;
    if (shouldDisplayHealthMessage()) {
        $checks = getWebsiteConfig();
        loadRules($checks);
        $errors = applyRules($checks);
        new SecretaryView($errors);
    }
}

function shouldDisplayHealthMessage() {
    return get_current_screen()->parent_base === 'edit' && get_current_screen()->base == 'post';
}

function getWebsiteConfig() {
    return spyc_load_file(file_get_contents(__DIR__ . '/configs/' . getWebsiteName() . '.yaml'));
}

function loadRules($checks) {
    foreach($checks as $title => $details) {
        require(__DIR__ . '/rules/' . $details['meta']['rule'] . '.php');
    }
}

function applyRules($checks) {
    $errors = array();
    foreach($checks as $title => $details) {
        $ruleFunction = 'secretary__' . $details['meta']['rule'];
        $options = isset($details['options']) ? $details['options'] : [];
        $ruleErrors = $ruleFunction($options, $post->ID);
        $errors[$title] = $ruleErrors;
    }
    return $errors;
}

function getWebsiteName() {
    // cope with www.musicmakerapps.com AND musicmakerapps.sandbox.sexy (i.e. with and without www)
    $websiteParts = explode(".", $_SERVER[SERVER_NAME]);
    $websiteName  = ($websiteParts[0] === 'www') ? $websiteParts[1] : $websiteParts[0];
    return $websiteName;
}

function get_the_content_by_id($postID) {
    $post = &get_post($postID);
    setup_postdata($post);
    $content = get_the_content();
    wp_reset_postdata($post);
    return $content;
}
