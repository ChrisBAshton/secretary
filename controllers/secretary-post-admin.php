<?php

class SecretaryPostAdmin {
    public static function init() {
        add_action('admin_notices', function () {
            SecretaryPostAdmin::show_report_at_top_of_post_admin_editor();
        });
        add_action('admin_print_styles', function () {
            wp_enqueue_style('secretary', plugins_url('../views/secretary.css', __FILE__ ), false);
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
        foreach($checks as $id => $details) {
            $Rule = SecretaryRules::$rules[$id];
            if (!$Rule) {
                $errors[$id] = ["Config error: no such rule!"];
            }
            else {
                $options = is_array($details) ? $details : [];
                $ruleErrors = $Rule['apply']($options, $post->ID);
                $errors[$Rule['meta']['title']] = $ruleErrors;
            }
        }
        return $errors;
    }
}
