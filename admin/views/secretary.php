<?php

class SecretaryView {
    function __construct($errors) {
        $this->errors = $errors;
        add_meta_box(
            'secretary__metabox',
            'Secretary',
            function () {
                $this->renderMetaBox();
            },
            'post',
            'side',
            'high'
        );
    }

    function renderMetaBox() {
        echo '<ul class="secretary">';
        foreach($this->errors as $title => $ruleErrors):
            if ($this->containsErrors($ruleErrors)) :
                echo '<li class="secretary-rule-title">' . $title . ' ❌</li>';
                $this->displayErrors($ruleErrors);
            else:
                echo '<li class="secretary-rule-title">' . $title . ' ✅</li>';
            endif;
        endforeach;
        $this->displaySummary($this->errors);
        echo '</ul>';
    }

    function containsErrors($errors) {
        return count($errors) !== 0;
    }

    function displayErrors($errors) {
        foreach($errors as $error) :
            echo '<li class="secretary-rule">' . $error . ' </li>';
        endforeach;
    }

    function displaySummary($rules) {
        $ruleCount = count($rules);
        $rulesPassed = $ruleCount;
        foreach($rules as $rule) {
            if ($this->containsErrors($rule)) {
                $rulesPassed--;
            }
        }
        echo '<li class="secretary-summary">' . $rulesPassed . ' out of ' . $ruleCount . ' rules passed.</li>';
    }
}
