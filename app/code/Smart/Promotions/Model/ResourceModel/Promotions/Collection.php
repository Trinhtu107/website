<?php

namespace Smart\Promotions\Model\ResourceModel\Promotions;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
/**
 * Class Collection
 *
 * @package Smart\Promotions\Model\ResourceModel\Promotions
 */
class Collection extends AbstractCollection
{
    /**
     * @var string
     */
    protected $_idFieldName = 'id';

    /**
     * @inheritDoc
     */
    public function _construct()
    {
        $this->_init(
            \Smart\Promotions\Model\Promotions::class,
            \Smart\Promotions\Model\ResourceModel\Promotions::class
        );
    }

}
