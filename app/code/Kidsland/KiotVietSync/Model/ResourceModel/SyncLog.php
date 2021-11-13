<?php
/**
 * User: TuTV
 * Date: 11/11/2021 23:26
 */

namespace Kidsland\KiotVietSync\Model\ResourceModel;


class SyncLog extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    protected function _construct()
    {
        $this->_init('kiot_viet_sync_log', 'entity_id');
    }
}
