<?php

class SecretaryPostAdmin {
    public static function init() {
        add_action('admin_notices', function () {
            SecretaryPostAdmin::show_report_at_top_of_post_admin_editor();
        });
        add_action('admin_print_styles', function () {
            echo '<link rel="stylesheet" href="'. plugins_url('views/secretary.css', __FILE__ ) .'" />';
        });
    }

    public static function show_report_at_top_of_post_admin_editor() {
        if (SecretaryPostAdmin::shouldDisplayHealthMessage()) {
            $checks = SecretaryConfig::getConfigAsYaml();
            $errors = SecretaryPostAdmin::applyRules($checks);
            new SecretaryView($errors);
        }
    }

    public static function shouldDisplayHealthMessage() {
        return get_current_screen()->parent_base === 'edit' && get_current_screen()->base == 'post';
    }

    public static function applyRules($checks) {
        $errors = array();
        foreach($checks as $shortname => $details) {
            $Rule = SecretaryRules::$rules[$shortname];
            $RuleClass = $Rule['class'];
            $options = is_array($details) ? $details : [];
            $ruleErrors = $RuleClass::apply($options, $post->ID);
            $errors[$Rule['title']] = $ruleErrors;
        }
        return $errors;
    }
}
