<?php

class SecretaryRules {

    public static $rules = array();

    public static function register($opts) {
        SecretaryRules::$rules[$opts['shortname']] = $opts;
    }

    public static function showHelp() {
        foreach(SecretaryRules::$rules as $shortname => $details) {
            echo '<h3>' . $details['title'] . '</h3>';
            echo '<textarea style="min-height: 150px;">' . $details['description'] . '</textarea>';
        }
    }
}
