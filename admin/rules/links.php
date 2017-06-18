<?php

function article_health__links($rules, $postID) {
    $content = get_the_content_by_id($postID);
    $links = [];
    $regex = '/<a href="([^>]+)">([^<]+)<\/a>/';
    preg_match_all($regex, $content, $links, PREG_SET_ORDER, 0);
    $errors = checkLinks(
        $links,
        $rules['internal']['open-in-new-tab'],
        $rules['external']['open-in-new-tab']
    );
    return $errors;
}

function get_the_content_by_id($postID) {
    $post = &get_post($postID);
    setup_postdata($post);
    $content = get_the_content();
    wp_reset_postdata($post);
    return $content;
}

function checkLinks($links, $internalShouldBeSameWindow, $externalShouldBeNewWindow) {
    $errors = [];
    $siteName = $_SERVER[SERVER_NAME];
    foreach($links as $link) :
        $html = $link[0];
        $url = $link[1];
        $urlText = $link[2];
        $internalLink = (strpos($url, $siteName) !== false);
        $opensInNewTab = (strpos($html, 'target="_blank"') !== false);
        if ($internalShouldBeSameWindow && $internalLink && $opensInNewTab) {
            $errors[] = "Warning: `" . $urlText . "` URL is internal but opens in a new tab.";
        }
        else if ($externalShouldBeNewWindow && !$internalLink && !$opensInNewTab) {
            $errors[] = "Warning: `" . $urlText . "` URL is external but does not open in a new tab.";
        }
    endforeach;
    return $errors;
}
