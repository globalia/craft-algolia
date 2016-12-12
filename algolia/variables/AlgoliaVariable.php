<?php
/**
 * Algolia plugin for Craft CMS
 *
 * Algolia Variable
 *
 * --snip--
 * Craft allows plugins to provide their own template variables, accessible from the {{ craft }} global variable
 * (e.g. {{ craft.pluginName }}).
 *
 * https://craftcms.com/docs/plugins/variables
 * --snip--
 *
 * @author    Joshua Baker
 * @copyright Copyright (c) 2016 Joshua Baker
 * @link      https://joshuabaker.com/
 * @package   Algolia
 * @since     0.1.0
 */

namespace Craft;

class AlgoliaVariable
{
    public function getPrefixedIndexName($indexName)
    {
        return craft()->algolia->getPrefixedIndexName($indexName);
    }
}
