<?php

namespace Smart\Promotions\Model\ResourceModel;

/**
 * Class PromotionsModel
 *
 * @package Smart\Promotions\Model\ResourceModel
 */
class Promotions extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    /**
     * @inheritDoc
     */
    public function _construct()
    {
        $this->_init("smart_promotions_table", "id");
    }
}
