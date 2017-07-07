<?php

class SecretaryRules {

    public static $rules = array();

    public static function register($opts) {
        SecretaryRules::$rules[$opts['id']] = $opts;
    }

    public static function showHelp() {
        foreach(SecretaryRules::$rules as $id => $details) {
            echo '<h3>' . $details['meta']['title'] . '</h3>';
            echo apply_filters('the_content', $details['meta']['description']);
            echo '<textarea class="secretary-textarea">' . $details['meta']['example'] . '</textarea>';
        }
    }
}
