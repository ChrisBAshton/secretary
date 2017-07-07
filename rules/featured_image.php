<?php

SecretaryRules::register(array(
    'id' => 'featured-image',
    'meta' => array(
        'title' => 'Featured Image',
        'description' => 'Checks dimensions, filesize and file type of your Featured Image.',
        'example' => '
featured-image:
    max-size: 100
    format: jpg
    dimensions:
        width: 760
        height: 350
',
    ),
    'apply' => array('SecretaryRuleFeaturedImage', 'apply'),
));

class SecretaryRuleFeaturedImage {

    public static function apply($rules, $postID) {
        $errors = [];
        if (has_post_thumbnail($postID)) {
            $attachmentID = get_post_thumbnail_id($postID);
            $image = wp_get_attachment_image_src($attachmentID, 'full');
            SecretaryRuleFeaturedImage::checkDimensions($errors, $image, $rules);
            SecretaryRuleFeaturedImage::checkFileInfo($errors, $image, $rules);
        }
        else {
            $errors[] = "No featured image specified!";
        }
        return $errors;
    }

    function checkDimensions(&$errors, $image, $rules) {
        $width = $image[1];
        $height = $image[2];
        $desiredWidth = $rules['dimensions']['width'];
        $desiredHeight = $rules['dimensions']['height'];

        if ($width !== $desiredWidth) {
            $errors[] = "Width should be " . $desiredWidth . "px, but " . $width . "px was detected";
        }
        if ($height !== $desiredHeight) {
            $errors[] = "Height should be " . $desiredHeight . "px, but " . $height . "px was detected";
        }
    }

    function checkFileInfo(&$errors, $image, $rules) {
        $url = $image[0];
        $imageType = pathinfo($url, PATHINFO_EXTENSION);
        $filesizeInKb = SecretaryRuleFeaturedImage::getKbSizeOfUrl($url);
        if ($filesizeInKb > $rules['max-size']) {
            $errors[] = "Max recommended size is " . $rules['max-size'] . "KB but image is " . $filesizeInKb . "KB in size";
        }
        if ($imageType !== 'jpg') {
            $errors[] = "Image type should usually be " . $rules['format'] . ", but " . $imageType . " type was detected.";
        }
    }

    function getKbSizeOfUrl($url) {
        return round(((int) get_headers($url, true)['Content-Length']) / 1024);
    }
}
