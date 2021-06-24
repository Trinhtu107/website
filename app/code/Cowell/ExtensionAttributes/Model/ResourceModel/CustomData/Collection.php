<?php

namespace Cowell\ExtensionAttributes\Model\ResourceModel\CustomData;

use Cowell\ExtensionAttributes\Api\Data\CustomDataInterface;
use Cowell\ExtensionAttributes\Model\ResourceModel\CustomData;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    protected $_idFieldName = 'id';

    protected function _construct()
    {
        $this->_init(\Cowell\ExtensionAttributes\Model\CustomData::class,
            \Cowell\ExtensionAttributes\Model\ResourceModel\CustomData::class);
        $this->_setIdFieldName($this->getResource()->getIdFieldName());
    }

    /**
     * @param array $productId
     * @return array
     */
    public function getById($productId)
    {
        $this->addFieldToFilter(CustomDataInterface::PRODUCT_ID,  $productId);

        return $this->_fetchAll($this->getSelect());
    }
}
