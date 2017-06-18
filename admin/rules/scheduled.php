<?php

function article_health__scheduled($rules, $postID) {
    $errors = [];
    if (get_post_status($postID) === 'draft' || get_post_status($postID) === 'auto-draft') {
        $errors[] = "This post needs a schedule date!";
    }
    else if ('15:00' !== get_the_date('H:i', $postID)) {
        $errors[] = "All posts should go live at 15:00 on any given day.";
    }
    return $errors;
}
