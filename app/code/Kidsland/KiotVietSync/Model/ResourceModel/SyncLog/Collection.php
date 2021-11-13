<?php
/**
 * User: TuTV
 * Date: 11/11/2021 23:28
 */

namespace Kidsland\KiotVietSync\Model\ResourceModel\SyncLog;


class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    protected $_idFieldName = 'entity_id';

    protected function _construct()
    {
        $this->_init(
            'Kidsland\KiotVietSync\Model\SyncLog',
            'Kidsland\KiotVietSync\Model\ResourceModel\SyncLog');
    }
}
