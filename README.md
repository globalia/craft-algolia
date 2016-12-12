# Algolia plugin for Craft CMS

Algolia search-as-a-service integration for Craft CMS.

## Installation

To install Algolia, follow these steps:

1. Download & unzip the file and place the `algolia` directory into your `craft/plugins` directory
2. Install plugin in the Craft Control Panel under Settings > Plugins

Algolia works on Craft 2.4.x and Craft 2.5.x.

## Configuring Algolia

You’ll need to create a `algolia.php` configuration file in `craft/config`.

Please see sample configuration below.

```php
<?php

namespace Craft;

return [

    'applicationId' => 'LDUJXDVEZY',
    'adminApiKey' => 'vh9ldujxdvezysilfnurwbniaddfmygb',

    'indicies' => [

        'newsPosts' => [
            'elementType' => 'entry',
            'filter' => function(BaseElementModel $element) {
                return $element->section->handle == 'news';
            },
            'transformer' => function(BaseElementModel $element) {
                return [
                    'title' => $element->title,
                    'body' => (string) $element->body,
                ];
            }
        ],

    ],

];
```
