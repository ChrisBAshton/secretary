<?php
require_once(dirname(__FILE__) . '/../vendor/autoload.php');

add_action( 'admin_notices', 'show_report_at_top_of_post_admin_editor' ) ;

function show_report_at_top_of_post_admin_editor() {
    global $post;
    if (shouldDisplayHealthMessage()) {
        $checks = getWebsiteConfig();
        loadRules($checks);
        $errors = applyRules($checks);
        displayOutput($errors);
    }
}

function shouldDisplayHealthMessage() {
    return get_current_screen()->post_type === 'post';
}

function getWebsiteConfig() {
    return spyc_load_file(file_get_contents(__DIR__ . '/configs/' . getWebsiteName() . '.yaml'));
}

function loadRules($checks) {
    foreach($checks as $rule => $details) {
        require(__DIR__ . '/rules/' . $rule . '.php');
    }
}

function applyRules($checks) {
    $errors = array();
    foreach($checks as $rule => $details) {
        $ruleFunction = 'article_health__' . $rule;
        $ruleErrors = $ruleFunction($details, $post->ID);
        $errors = array_merge($errors, $ruleErrors);
    }
    return $errors;
}

function displayOutput($errors) {
    if (count($errors > 0)) :
        echo '<ul>';
        foreach($errors as $error) :
            echo '<li>' . $error . '</li>';
        endforeach;
        echo '</ul>';
    else:
        echo 'No problems found!';
    endif;
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
