<?php
require_once(dirname(__FILE__) . '/../vendor/autoload.php');

add_action( 'admin_notices', 'show_report_at_top_of_post_admin_editor' ) ;

function show_report_at_top_of_post_admin_editor() {
    if (get_current_screen()->post_type === 'post') {
        $checks = spyc_load_file(file_get_contents(__DIR__ . '/configs/' . getWebsiteName() . '.yaml'));
        foreach($checks as $rule => $details) {
            require(__DIR__ . '/rules/' . $rule . '.php');
            $ruleFunction = 'article_health__' . $rule;
            $ruleFunction($details);
        }
    }
}

function getWebsiteName() {
    // cope with www.musicmakerapps.com AND musicmakerapps.sandbox.sexy (i.e. with and without www)
    $websiteParts = explode(".", $_SERVER[SERVER_NAME]);
    $websiteName  = ($websiteParts[0] === 'www') ? $websiteParts[1] : $websiteParts[0];
    return $websiteName;
}
