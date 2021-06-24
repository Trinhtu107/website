<?php
namespace Cowell\ExtensionAttributes\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

/**
 * Class ExampleAttributes
 *
 * @package Cowell\ExtensionAttributes\Model\ResourceModel
 */
class ExampleAttributes extends AbstractDb
{
    /**
     * Resource initialization
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('product_extension_attributes', 'id');
    }
}
