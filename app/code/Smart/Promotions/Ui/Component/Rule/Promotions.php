<?php
/**
 * User: tutv
 * Date: 23/06/2021 16:40
 */

namespace Smart\Promotions\Ui\Component\Rule;

use Smart\Promotions\Model\ResourceModel\Promotions\CollectionFactory;

class Promotions implements \Magento\Framework\Option\ArrayInterface
{
    /**
     * @var CollectionFactory
     */
    protected $promotionsModelFactory;

    /**
     * Promotions constructor.
     * @param CollectionFactory $promotionsModelFactory
     */
    public function __construct(CollectionFactory $promotionsModelFactory)
    {
        $this->promotionsModelFactory = $promotionsModelFactory;
    }

    /**
     * @return array
     */
    public function toOptionArray()
    {
        $collection = $this->promotionsModelFactory->create()
            ->addFieldToFilter('active', true);

        $promotions = array();
        foreach ($collection as $k => $value) {
            $promotions[$k] = [
                'value' => $k,
                'label' => $value->getTitle()
            ];
        }
        return $promotions;
    }
}
