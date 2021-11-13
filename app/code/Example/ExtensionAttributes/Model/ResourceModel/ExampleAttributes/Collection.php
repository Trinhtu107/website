<?php

namespace Example\ExtensionAttributes\Model\ResourceModel\ExampleAttributes;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Example\ExtensionAttributes\Model\ResourceModel\ExampleAttributes as ResourceModel;
use Example\ExtensionAttributes\Model\ExampleAttributes as RegularModel;

/**
 * Class Collection
 *
 * @package Example\ExtensionAttributes\Model\ResourceModel\ExampleAttributes
 */
class Collection extends AbstractCollection
{
    /**
     * @var string
     */
    protected $_idFieldName = 'id';

    /**
     * Constructor
     */
    protected function _construct()
    {
        $this->_init(RegularModel::class, ResourceModel::class);
    }
}
