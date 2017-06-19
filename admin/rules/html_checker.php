<?php

function secretary__html_checker($rules, $postID) {
    $errors = [];
    $content = get_the_content_by_id($postID);
    foreach($rules['risky-html'] as $tag) :
        $riskyHtmlFragments = [];
        $regex = '/<' . $tag . '/';
        preg_match($regex, $content, $riskyHtmlFragments);
        if (count($riskyHtmlFragments) > 0) {
            $errors[] = 'Warning: `&lt;' . $tag . '&gt;` tag detected. Do not edit this post in Visual Mode or it may become corrupted.';
        }
    endforeach;
    return $errors;
}
