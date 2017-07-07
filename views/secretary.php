<?php

class SecretaryView {
    function __construct($errors) {
        $this->rules = $this->reorderToShowErrorsFirst($errors);
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

    function reorderToShowErrorsFirst($rules) {
        // @TODO ugly. Refactor
        $sortedArray = array();
        foreach($rules as $title => $ruleErrors):
            if ($this->containsErrors($ruleErrors)) {
                $sortedArray[$title] = $ruleErrors;
            }
        endforeach;
        foreach($rules as $title => $ruleErrors):
            if (!$this->containsErrors($ruleErrors)) {
                $sortedArray[$title] = $ruleErrors;
            }
        endforeach;
        return $sortedArray;
    }

    function renderMetaBox() {
        echo '<ul class="secretary">';
        foreach($this->rules as $title => $ruleErrors):
            if ($this->containsErrors($ruleErrors)) :
                echo '<li class="secretary-rule-title">❌ ' . $title . '</li>';
                $this->displayErrors($ruleErrors);
            else:
                echo '<li class="secretary-rule-title">✅ ' . $title . '</li>';
            endif;
        endforeach;
        $this->displaySummary($this->rules);
        echo '</ul>';
    }

    function containsErrors($rules) {
        return count($rules) !== 0;
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
