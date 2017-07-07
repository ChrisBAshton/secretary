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
            	if (!current_user_can('manage_options'))  {
            		wp_die(__( 'You do not have sufficient permissions to access this page.'));
            	}
                SecretaryConfig::renderForm();
                SecretaryConfig::highlightYamlSyntax();
                SecretaryRules::showHelp();
            }
        );
    }


    public static function renderForm() {
        echo '<div class="wrap"><h1>Secretary - Config</h1>';
            echo '<form method="post" action="options.php">';
                settings_fields(SecretaryConfig::$configName);
                do_settings_sections(SecretaryConfig::$configName);
                echo '<textarea id="' . SecretaryConfig::$configOptionName . '" name="' . SecretaryConfig::$configOptionName . '">'
                        . get_option(SecretaryConfig::$configOptionName) .
                     '</textarea>';
                submit_button();
            echo '</form>';
        echo '</div>';
    }

    public static function highlightYamlSyntax() {
        echo '
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.27.4/codemirror.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.27.4/mode/yaml/yaml.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.27.4/codemirror.min.css" />

<script>
CodeMirror.fromTextArea(document.getElementById("' . SecretaryConfig::$configOptionName . '"), {
mode: "yaml",
lineNumbers: true
});
</script>
        ';
    }
}
