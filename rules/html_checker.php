<?php

SecretaryRules::register(array(
    'id' => 'html-checker',
    'meta' => array(
        'title' => 'HTML Checker',
        'description' => 'Warns if there is any dodgy hard-coded HTML in your post.',
        'example' => '
html-checker:
    risky-html:
      - table
      - div
      - span
      - style
      - script
',
    ),
    'apply' => function ($rules, $postID) {
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
    },
));
