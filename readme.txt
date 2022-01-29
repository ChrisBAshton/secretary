=== Secretary ===
Contributors: ChrisBAshton
Donate link: http://twitter.com/ChrisBAshton
Tags: secretary, quality, validate, check, qa, proofread
Tested up to: 5.9
Stable tag: trunk
License: GPLv3
License URI: https://www.gnu.org/licenses/gpl-3.0.html

Automatic quality-assurance checks, ensuring your articles meet editorial guidelines.

== Description ==

Users define their editorial rules in YAML (see Settings -> Secretary) according to a number of built-in rule functions. For example, Secretary can show a warning if you've forgotten to set a Featured Image for your post, by adding the `featured-image` rule to your YAML config.

Secretary comes with a number of rules out of the box, but you can write your own custom plugins for Secretary by calling `SecretaryRules::register`. See example at https://github.com/ChrisBAshton/secretary-rule-gallery-at-top, and API documentation at https://github.com/ChrisBAshton/secretary.

== Installation ==

1. Install the plugin through the WordPress plugins screen directly
2. Activate the plugin through the 'Plugins' screen in WordPress
3. Use the Settings->Secretary screen to configure the plugin (you can see all available rule configs on the right hand side of the screen)

Example YAML config below:

`
categories:
    not:
        - Uncategorized
    not-only:
        - Featured

featured-image:
    max-size: 100
    format: jpg
    dimensions:
        width: 760
        height: 350

excerpt:
    min-length: 30
    max-length: 300

scheduled:
    publish-time: '15:00'

links:
    internal:
        open-in-new-tab: false
    external:
        open-in-new-tab: true

images:
    true

html-checker:
    risky-html:
      - table
      - div
      - span
      - style
      - script
`

== Frequently Asked Questions ==

= How do I know what rules are available? =

Look at the right hand side of the screen under Settings -> Secretary.

= I'm getting a `Config error: no such rule!` =

If you see something like:

`
❌ foo
Config error: no such rule!
`

...it means you have asked Secretary to apply a rule which does not exist. Check your spelling.

== Screenshots ==

1. Secretary warns you against common problems; in this case, the Featured Image set for the post was too large in filesize.
2. Secretary will only look for the rules you tell it to look for. There are a few built into the Secretary plugin, but you can install other plugins which define other rules.
3. The `HTML Checker` rule searches for HTML to warn your content editors that this is a brittle way of styling posts.
4. If all is well, you should see all green ticks!

== Changelog ==

= 1.0.3 =
* Removes an accidentally committed internal-facing README file

= 1.0.2 =
* Removed unneeded 'assets/' directory

= 1.0.1 =
* Tested with Gutenberg

= 1.0.0 =
* Initial release

== Contact ==

If you spot any issues, or want to know how to contribute, please visit https://github.com/ChrisBAshton/secretary.

Please note that I have open-sourced this plugin to give back to the community, and do not have much spare time to answer support queries, but I'll help where I can.
