# Secretary

Automatic quality-assurance checks, ensuring your articles meet editorial guidelines.

Users define their editorial rules in YAML (see Settings -> Secretary) according to a number of [built-in rule functions](https://github.com/ChrisBAshton/secretary/tree/master/rules), or any [external rule functions](https://github.com/ChrisBAshton/secretary-rule-gallery-at-top) that conform to the API. For example, Secretary can show a warning if you've forgotten to set a Featured Image for your post, by adding the `featured-image` rule to your YAML config.

Secretary comes with 7 different rules out of the box, but other plugins could in theory provide rules to Secretary by calling `SecretaryRules::register`.

## Creating a rule

`SecretaryRules::register` expects an array with three arguments:

### id

The unique ID of your rule. This ID corresponds to what the user will put in their config, e.g. a rule called `foo` will be executed if the user's config looks like:

```yaml
foo:
  option1: true
  option2: "bar"
  my-list:
    - an item
    - another item
```

The properties underneath `foo:` in the YAML config above would be passed to your rule to do with it what you will.

### meta

This is an array of meta information describing your rule. It should contain:

- title
- description - what it does
- example - how to use it

```php
array(
    'title' => 'foo',
    'description' => 'Does foo to bar',
foo:
    option1: true
    option2: 'bar'
    my-list:
        - an item
        - another item
    ',
)
```

### apply

This is the PHP callback which should be a function that takes two parameters: `$options` and `$postID`.

`$options` is an array of options as per the YAML config you've asked the user to describe. In the example above, it would look like this:

```php
array(
    'option1' => true,
    'option2' => 'bar',
    'my-list' => array(
        'an item',
        'another item'
    )
)
```

The callback can be an anonymous function, or a variable function if you need to split your code up more neatly (e.g. `'apply' => array('SecretaryRuleCategoriesClass', 'myCustomFunction')`).

## Example rule definition

```php
SecretaryRules::register(array(
    'id' => 'excerpt',
    'meta' => array(
        'title' => 'Excerpt',
        'description' => 'Enforces excerpt length',
        'example' => '
excerpt:
    min-length: 30
    max-length: 300
        ',
    ),
    'apply' => function ($options, $postID) {
        $errors = [];
        if (!has_excerpt($postID)) {
            $errors[] = "You must specify an excerpt!";
        }
        else {
            $excerpt = get_the_excerpt($postID);
            if (strlen($excerpt) < $options['min-length']) {
                $errors[] = "Excerpt should be no less than " . $options['min-length']. " characters in length. " . strlen($excerpt) . " characters detected.";
            }
            else if (strlen($excerpt) > $options['max-length']) {
                $errors[] = "Excerpt should be no more than " . $options['max-length']. " characters in length. " . strlen($excerpt) . " characters detected.";
            }

        }
        return $errors;
    }
));
```

## Example YAML config

```yaml
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
  publish-time: "15:00"

links:
  internal:
    open-in-new-tab: false
  external:
    open-in-new-tab: true

images: true

html-checker:
  risky-html:
    - table
    - div
    - span
    - style
    - script
```

## Deployment

This repository uses [WordPress.org Plugin Deploy](https://github.com/marketplace/actions/wordpress-plugin-deploy) to:

- automatically publish new versions of the Secretary plugin when a new [release](https://github.com/ChrisBAshton/secretary/releases) is published
  - Sense-check that the deployment worked by visiting https://plugins.trac.wordpress.org/browser/secretary
- automatically update the plugin README and assets on its [homepage](https://wordpress.org/plugins/secretary/) when `readme.txt` or any asset in `.wordpress-org` is updated on `main`.
  - This way you can update the "Tested up to" value without having to bump the plugin version.
