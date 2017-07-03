<?php

class SecretaryRules {

    public static $rules = array();

    public static function register($opts) {
        SecretaryRules::$rules[$opts['shortname']] = $opts;
    }
}
