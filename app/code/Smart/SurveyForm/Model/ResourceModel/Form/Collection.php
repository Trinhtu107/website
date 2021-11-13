<?php

namespace Smart\SurveyForm\Model\ResourceModel\Form;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

/**
 * Class Collection
 *
 * @package Smart\SurveyForm\Model\ResourceModel\Form
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
            "Smart\SurveyForm\Model\FormModel",
            "Smart\SurveyForm\Model\ResourceModel\Form\FormModel"
        );
    }
}
