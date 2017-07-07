<?php

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

SecretaryRules::register(array(
    'id' => 'categories',
    'meta' => array(
        'title' => 'Categories',
        'description' => '
Warn if a post is in a forbidden category.

`not` takes an array of categories that the post should NOT be in, period.

`not-only` takes an array of categories where that should not be the ONLY category the post is in. For example, "Featured" is not particularly descriptive - you probably want your post to be in another category alongside that.
',
        'example' => '
categories:
    not:
        - Uncategorized
    not-only:
        - Featured
'
    ),
    'apply' => array('SecretaryRuleCategories', 'apply'),
));
