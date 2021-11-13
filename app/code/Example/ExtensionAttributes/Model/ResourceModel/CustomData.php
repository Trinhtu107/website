<?php

namespace Example\ExtensionAttributes\Model\ResourceModel;

use Example\ExtensionAttributes\Api\Data\CustomDataInterface;
use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use \Magento\Framework\Model\ResourceModel\Db\Context;

class CustomData extends AbstractDb
{
    const TABLE_NAME = 'product_extension_attributes';


    private $imageProcessor;

    public function __construct(
        Context $context,
        $connectionName = null
    ) {
        parent::__construct($context, $connectionName);
    }

    /**
     * @inheritdoc
     */
    protected function _construct()
    {
        $this->_init(self::TABLE_NAME, 'id');
    }

}
