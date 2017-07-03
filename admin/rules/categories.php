<?php

SecretaryRules::register(array(
    'shortname' => 'categories',
    'class' => 'SecretaryRuleCategories',
    'title' => 'Categories',
    'description' => '
Example:

categories:
    not:
        - Uncategorized
    not-only:
        - Featured
    ',
));

class SecretaryRuleCategories {

    public static function apply($rules, $postID) {
        $errors = [];
        $categories = get_the_category($postID);
        $categoryNames = SecretaryRuleCategories::get_category_names_from($categories);
        SecretaryRuleCategories::checkForForbiddenCategories($errors, $rules, $categoryNames);
        SecretaryRuleCategories::checkForNonDescriptiveCategories($errors, $rules, $categoryNames);
        return $errors;
    }

    public static function get_category_names_from($categories) {
        $mapFunction = function ($category) {
            return $category->name;
        };
        return array_map($mapFunction, $categories);
    }

    public static function checkForForbiddenCategories(&$errors, $rules, $categoryNames) {
        foreach($rules['not'] as $shouldNotBeThisCategory) :
            if (in_array($shouldNotBeThisCategory, $categoryNames)) {
                $errors[] = 'Post is in `' . $shouldNotBeThisCategory . '` category, which is forbidden.';
            }
        endforeach;
    }

    public static function checkForNonDescriptiveCategories(&$errors, $rules, $categoryNames) {
        if (count($categoryNames) > 1) {
            return;
        }
        foreach($rules['not-only'] as $shouldNotBeOnlyThisCategory) :
            if (in_array($shouldNotBeOnlyThisCategory, $categoryNames)) {
                $errors[] = 'Post is only in the `' . $shouldNotBeOnlyThisCategory . '` category. It should be in at least one other category.';
            }
        endforeach;
    }
}
