<?php

namespace Example\ExtensionAttributes\Model;

use Magento\Framework\Model\AbstractModel;
use Example\ExtensionAttributes\Model\ResourceModel\ExampleAttributes as ResourceModel;

/**
 * Class ExampleAttributes
 *
 * @package Example\ExtensionAttributes\Model\ResourceModel
 */
class ExampleAttributes extends AbstractModel
{
    /**
     * Resource initialization
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(ResourceModel::class);
    }

    /**
     * @return int
     */
    public function getProductId() : int
    {
        return (int)$this->getData('product_id');
    }

    /**
     * @param int $productId
     *
     * @return $this
     */
    public function setProductId(int $productId)
    {
        return $this->setData('product_id', $productId);
    }

    /**
     * @return string
     */
    public function getTrainingDateStart() : string
    {
        return (string)$this->getData('date_start');
    }

    /**
     * @param string $trainingDateStart
     *
     * @return $this
     */
    public function setTrainingDateStart(string $trainingDateStart)
    {
        return $this->setData('date_start', $trainingDateStart);
    }

    /**
     * @return string
     */
    public function getTrainingDateEnd() : string
    {
        return (string)$this->getData('date_end');
    }

    /**
     * @param string $trainingDateEnd
     *
     * @return $this
     */
    public function setTrainingDateEnd(string $trainingDateEnd)
    {
        return $this->setData('date_end', $trainingDateEnd);
    }
}
