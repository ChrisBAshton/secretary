<?php

function article_health__featured_image($rules, $postID) {
    $errors = [];
    if (has_post_thumbnail($postID)) {
        $attachmentID = get_post_thumbnail_id($postID);
        $image = wp_get_attachment_image_src($attachmentID, 'full');
        $url = $image[0];
        $width = $image[1];
        $height = $image[2];
        $desiredWidth = $rules['dimensions']['width'];
        $desiredHeight = $rules['dimensions']['height'];
        $imageType = pathinfo($url, PATHINFO_EXTENSION);
        $filesizeInKb = getKbSizeOfUrl($url);

        if ($filesizeInKb > $rules['max-size']) {
            $errors[] = "Max recommended size for Featured Image is " . $rules['max-size'] . "KB but image appears to be " . $filesizeInKb . "KB in size";
        }
        if ($imageType !== 'jpg') {
            $errors[] = "Featured image type should usually be " . $rules['format'] . ", but " . $imageType . " type was detected. Be careful!";
        }
        if ($width !== $desiredWidth) {
            $errors[] = "Featured image width should be " . $desiredWidth . "px, but " . $width . "px was detected";
        }
        if ($height !== $desiredHeight) {
            $errors[] = "Featured image height should be " . $desiredHeight . "px, but " . $height . "px was detected";
        }
    }
    else {
        $errors[] = "No featured image specified!";
    }
    return $errors;
}

function getKbSizeOfUrl($url) {
    return round(((int) get_headers($url, true)['Content-Length']) / 1024);
}
