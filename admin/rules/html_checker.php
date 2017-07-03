<?php

SecretaryRules::register(array(
    'shortname' => 'html_checker',
    'class' => 'SecretaryRuleHtmlChecker',
    'title' => 'HTML Checker',
    'description' => '
Example:

risky-html:
  - table
  - div
  - span
  - style
  - script
    ',
));

class SecretaryRuleHtmlChecker {

    public static function apply($rules, $postID) {
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
}
