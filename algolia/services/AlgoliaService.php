<?php

/**
 * Algolia plugin for Craft CMS.
 *
 * Algolia Service
 *
 * @author    Joshua Baker
 * @copyright Copyright (c) 2016 Joshua Baker
 *
 * @link      https://joshuabaker.com/
 * @since     0.1.0
 */

namespace Craft;

use AlgoliaSearch\Client as AlgoliaClient;

class AlgoliaService extends BaseApplicationComponent
{
    /**
     * An Algolia client instance.
     *
     * @var \AlgoliaSearch\Client
     */
    protected $algoliaClient;

    /**
     * An array of Algolia_IndexModel instances.
     *
     * @var array
     */
    protected $indicies;

    /**
     * Returns an Algolia client instance.
     *
     * @return \AlgoliaSearch\Client
     */
    public function getAlgoliaClient()
    {
        if (is_null($this->algoliaClient)) {
            $this->algoliaClient = new AlgoliaClient(
                craft()->config->get('applicationId', 'algolia'),
                craft()->config->get('adminApiKey', 'algolia')
            );
        }

        return $this->algoliaClient;
    }

    /**
     * Returns the supplied index name prefixed.
     *
     * @param $indexName string
     *
     * @return string
     */
    public function getPrefixedIndexName($indexName)
    {
        return craft()->config->get('indexNamePrefix', 'algolia').$this->indexName;
    }

    /**
     * Returns an array of Algolia_IndexModel instances with the unprefixed index names as keys.
     *
     * @return array
     */
    public function getIndicies()
    {
        if (is_null($this->indicies)) {
            $this->indicies = [];

            $indiciesConfig = craft()->config->get('indicies', 'algolia');

            foreach ($indiciesConfig as $indexName => $indexConfig) {
                $indexConfig['indexName'] = $indexName;
                $this->indicies[$indexName] = new Algolia_IndexModel($indexConfig);
            }
        }

        return $this->indicies;
    }

    /**
     * Returns an Algolia_IndexModel instance by name.
     *
     * @param $indexName string The unprefixed index name
     *
     * @return Algolia_IndexModel
     */
    public function getIndexByName($indexName)
    {
        $indicies = $this->getIndicies();

        if (isset($indicies[$indexName])) {
            return $indicies[$indicies];
        }
    }

    /**
     * Passes the supplied element to each configured index.
     *
     * @param $element BaseElementModel
     */
    public function indexElement(BaseElementModel $element)
    {
        foreach ($this->getIndicies() as $indexName => $index) {
            $index->indexElement($element);
        }
    }

    /**
     * Passes the supplied elements to each configured index.
     *
     * @param $element array
     */
    public function indexElements($elements)
    {
        foreach ($this->getIndicies() as $indexName => $index) {
            $index->indexElements($elements);
        }
    }

    /**
     * Imports a paginated batch
     *
     * @param $page integer
     */
    public function import($index, $page = 0) 
    {
        $limit = craft()->config->get('limit', 'algolia');

        $currentIndex = array_slice(craft()->algolia->getIndicies(), $index, 1);
        $currentIndex = array_shift($currentIndex);

        $criteria = craft()->elements->getCriteria(ucfirst($currentIndex->elementType), $currentIndex->elementCriterias);
        $criteria->limit = $limit;
        $criteria->offset = $page * $limit;

        return $this->indexElements($criteria->find());
        
    }
}
