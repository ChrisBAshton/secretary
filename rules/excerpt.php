<?php

SecretaryRules::register(array(
    'id' => 'excerpt',
    'meta' => array(
        'title' => 'Excerpt',
        'description' => 'Enforces a minimum and maximum length for your excerpts.',
        'example' => '
excerpt:
    min-length: 30
    max-length: 300
',
    ),
    'apply' => function ($rules, $postID) {
        $errors = [];
        if (!has_excerpt($postID)) {
            $errors[] = "You must specify an excerpt!";
        }
        else {
            $excerpt = get_the_excerpt($postID);
            if (strlen($excerpt) < $rules['min-length']) {
                $errors[] = "Excerpt should be no less than " . $rules['min-length']. " characters in length. " . strlen($excerpt) . " characters detected.";
            }
            else if (strlen($excerpt) > $rules['max-length']) {
                $errors[] = "Excerpt should be no more than " . $rules['max-length']. " characters in length. " . strlen($excerpt) . " characters detected.";
            }

        }
        return $errors;
    }
));
