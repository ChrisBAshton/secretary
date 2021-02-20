<?php

SecretaryRules::register(array(
    'id' => 'links',
    'meta' => array(
        'title' => 'Links',
        'description' => 'Enforces new tab rules. Note that this only works on pre-Gutenberg sites.',
        'example' => '
links:
    internal:
      open-in-new-tab: false
    external:
      open-in-new-tab: true
',
    ),
    'apply' => array('SecretaryRuleLinks', 'apply'),
));

class SecretaryRuleLinks {
    public static function apply($rules, $postID) {
        $content = get_the_content_by_id($postID);
        $links = [];
        $regex = '/<a href="([^>]+)">([^<]+)<\/a>/';
        preg_match_all($regex, $content, $links, PREG_SET_ORDER, 0);
        $errors = SecretaryRuleLinks::checkLinks(
            $links,
            $rules['internal']['open-in-new-tab'],
            $rules['external']['open-in-new-tab']
        );
        return $errors;
    }

    function checkLinks($links, $internalShouldBeSameWindow, $externalShouldBeNewWindow) {
        $errors = [];
        foreach($links as $link) :
            $html = $link[0];
            $url = $link[1];
            $urlText = $link[2];
            if ($internalShouldBeSameWindow && SecretaryRuleLinks::isInternalLink($url) && SecretaryRuleLinks::opensInNewTab($html)) {
                $errors[] = "Warning: `" . $urlText . "` URL is internal but opens in a new tab.";
            }
            else if ($externalShouldBeNewWindow && !SecretaryRuleLinks::isInternalLink($url) && !SecretaryRuleLinks::opensInNewTab($html)) {
                $errors[] = "Warning: `" . $urlText . "` URL is external but does not open in a new tab.";
            }
        endforeach;
        return $errors;
    }

    function isInternalLink($url) {
        $siteName = $_SERVER[SERVER_NAME];
        return (strpos($url, $siteName) !== false);
    }

    function opensInNewTab($html) {
        return (strpos($html, 'target="_blank"') !== false);
    }
}
