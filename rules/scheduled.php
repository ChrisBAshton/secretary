<?php

SecretaryRules::register(array(
    'id' => 'scheduled',
    'meta' => array(
        'title' => 'Scheduled',
        'description' => 'Enforces a set scheduled time.',
        'example' => '
scheduled:
    publish-time: "15:00"
', // @TODO set a publish day too
    ),
    'apply' => function ($rules, $postID) {
        $errors = [];
        if (get_post_status($postID) === 'draft' || get_post_status($postID) === 'auto-draft') {
            $errors[] = "This post needs a schedule date!";
        }
        else if ($rules['publish-time'] !== get_the_date('H:i', $postID)) {
            $errors[] = "All posts should go live at 15:00 on any given day."; // @TODO refer to $rules value
        }
        return $errors;
    }
));
