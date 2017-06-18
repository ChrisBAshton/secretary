<?php

function article_health__featured_image($rules, $postID) {
    if (has_post_thumbnail($postID)) {
        $attachmentID = get_post_thumbnail_id($postID);
        $image = wp_get_attachment_image_src($attachmentID, 'full');
        $width = $image[1];
        $height = $image[2];
        $desiredWidth = $rules['dimensions']['width'];
        $desiredHeight = $rules['dimensions']['height'];
        if ($width !== $desiredWidth) {
            return "Featured image width should be " . $desiredWidth . "px, but " . $width . "px was detected";
        }
        if ($height !== $desiredHeight) {
            return "Featured image height should be " . $desiredHeight . "px, but " . $height . "px was detected";
        }
    }
    else {
        return "No featured image specified!";
    }
}
