<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Smart\SurveyForm\Ui\Component\Form;

use Smart\SurveyForm\Model\ResourceModel\Question\CollectionFactory;
use Magento\Directory\Model\CountryFactory;
use Magento\Framework\Api\Filter;

/**
 * Custom DataProvider for customer addresses listing
 */
class DataProvider extends \Magento\Ui\DataProvider\AbstractDataProvider
{
    /**
     * @var \Magento\Framework\App\RequestInterface $request,
     */
    private $request;

    /**
     * @var CountryFactory
     */
    private $countryDirectory;

    /**
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param CollectionFactory $collectionFactory
     * @param \Magento\Framework\App\RequestInterface $request
     * @param CountryFactory $countryFactory
     * @param array $meta
     * @param array $data
     */
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        CollectionFactory $collectionFactory,
        \Magento\Framework\App\RequestInterface $request,
        CountryFactory $countryFactory,
        array $meta = [],
        array $data = []
    ) {
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
        $this->collection = $collectionFactory->create();
        $this->countryDirectory = $countryFactory->create();
        $this->request = $request;
    }

    /**
     * Add country key for default billing/shipping blocks on customer addresses tab
     *
     * @return array
     */
    public function getData(): array
    {
        $collection = $this->getCollection();
        $data['items'] = [];
        if ($this->request->getParam('form_id')) {
            $collection->addFieldToFilter('form_id', $this->request->getParam('form_id'));
            $data = $collection->toArray();
        }
        return $data;
    }
}
