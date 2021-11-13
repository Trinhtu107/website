<?php
/**
 * User: TuTV
 * Date: 11/11/2021 23:25
 */

namespace Kidsland\KiotVietSync\Model;


class SyncLog extends \Magento\Framework\Model\AbstractModel
{
    protected function _construct()
    {
        $this->_init('Kidsland\KiotVietSync\Model\ResourceModel\SyncLog');
    }
}
