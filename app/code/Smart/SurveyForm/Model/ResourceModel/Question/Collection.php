<?php

namespace Smart\SurveyForm\Model\ResourceModel\Question;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

/**
 * Class Collection
 *
 * @package Smart\SurveyForm\Model\ResourceModel\Question
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
            \Smart\SurveyForm\Model\QuestionModel::class,
            \Smart\SurveyForm\Model\ResourceModel\Question\QuestionModel::class
        );
    }
}
