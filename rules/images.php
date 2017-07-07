<?php

SecretaryRules::register(array(
    'id' => 'images',
    'meta' => array(
        'title' => 'Images',
        'description' => 'Enforces ALT. Does not currently look at any options.',
        'example' => '
images: true
',
    ),
    'apply' => array('SecretaryRuleImages', 'apply'),
));

class SecretaryRuleImages {

    public static function apply($rules, $postID) {
        if (!$rules['alt-required']) {
            return;
        }
        $content = get_the_content_by_id($postID);
        $images = [];
        $regex = '/<img[^>]+src="([^">]+)" [^>]+\/>/';
        preg_match_all($regex, $content, $images, PREG_SET_ORDER, 0);
        $errors = SecretaryRuleImages::checkImages($images);
        return $errors;
    }

    function checkImages($images) {
        $errors = [];
        foreach($images as $image) :
            $html = $image[0];
            $url = $image[1];
            if (SecretaryRuleImages::hasNoAltTag($html)) {
                $errors[] = "Error: image `" . $url . "` URL has no alt tag.";
            }
            else if (SecretaryRuleImages::hasEmptyAltTag($html)) {
                $errors[] = "Warning: image `" . $url . "` URL has an empty alt tag. This may or may not be deliberate.";
            }
        endforeach;
        return $errors;
    }

    function hasNoAltTag($html) {
        return (strpos($html, 'alt="') === false);
    }

    function hasEmptyAltTag($html) {
        return (strpos($html, 'alt=""') !== false);
    }
}
