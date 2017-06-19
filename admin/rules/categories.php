<?php

function secretary__categories($rules, $postID) {
    $errors = [];
    $categories = get_the_category($postID);
    $categoryNames = get_category_names_from($categories);
    checkForForbiddenCategories($errors, $rules, $categoryNames);
    checkForNonDescriptiveCategories($errors, $rules, $categoryNames);
    return $errors;
}

function get_category_names_from($categories) {
    $mapFunction = function ($category) {
        return $category->name;
    };
    return array_map($mapFunction, $categories);
}

function checkForForbiddenCategories(&$errors, $rules, $categoryNames) {
    foreach($rules['not'] as $shouldNotBeThisCategory) :
        if (in_array($shouldNotBeThisCategory, $categoryNames)) {
            $errors[] = 'Post is in `' . $shouldNotBeThisCategory . '` category, which is forbidden.';
        }
    endforeach;
}

function checkForNonDescriptiveCategories(&$errors, $rules, $categoryNames) {
    if (count($categoryNames) > 1) {
        return;
    }
    foreach($rules['not-only'] as $shouldNotBeOnlyThisCategory) :
        if (in_array($shouldNotBeOnlyThisCategory, $categoryNames)) {
            $errors[] = 'Post is only in the `' . $shouldNotBeOnlyThisCategory . '` category. It should be in at least one other category.';
        }
    endforeach;
}
