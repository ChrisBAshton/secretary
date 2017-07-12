<?php

class SecretaryConfig {
    public static $configName = 'secretary-config';
    public static $configOptionName = 'secretary-config-yaml';

    public static function init() {
        if (is_admin()) {
            add_action(
                'admin_menu',
                function () {
                    SecretaryConfig::add_secretary_to_settings_menu();
                }
            );
            add_action(
                'admin_init',
                function () {
                    register_setting(SecretaryConfig::$configName, SecretaryConfig::$configOptionName);
                }
            );
        }
    }

    public static function getConfigAsYaml() {
        return spyc_load_file(get_option(SecretaryConfig::$configOptionName));
    }

    public static function add_secretary_to_settings_menu() {
    	add_options_page(
            'Secretary - Config',
            'Secretary',
            'manage_options',
            SecretaryConfig::$configName,
            function () {
                wp_enqueue_style('secretary__codemirror', plugins_url('../views/codemirror.min.css', __FILE__ ));
                wp_enqueue_style('secretary__codemirror_overrides', plugins_url('../views/codemirror.overrides.css', __FILE__ ));
                wp_enqueue_script('secretary__codemirror', plugins_url('../views/codemirror.min.js', __FILE__ ));
                wp_enqueue_script('secretary__codemirror_overrides', plugins_url('../views/yaml.min.js', __FILE__ ));
                wp_enqueue_script('secretary__codemirror_init', plugins_url('../views/codemirror-init.js', __FILE__ ));

            	if (!current_user_can('manage_options'))  {
            		wp_die(__( 'You do not have sufficient permissions to access this page.'));
            	}

                echo '<div class="wrap"><h1>Secretary - Config</h1>';
                    echo '<div class="col-1/2 secretary-form">';
                        SecretaryConfig::renderForm();
                    echo '</div>';
                    echo '<div class="col-1/2 secretary-help">';
                        SecretaryRules::showHelp();
                    echo '</div>';
                echo '</div>';
            }
        );
    }


    public static function renderForm() {
        echo '<form method="post" action="options.php">';
            settings_fields(SecretaryConfig::$configName);
            do_settings_sections(SecretaryConfig::$configName);
            echo '<textarea id="' . SecretaryConfig::$configOptionName . '" class="secretary-textarea" name="' . SecretaryConfig::$configOptionName . '">'
                    . get_option(SecretaryConfig::$configOptionName) .
                 '</textarea>';
            submit_button();
        echo '</form>';
    }
}
