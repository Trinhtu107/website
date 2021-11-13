<?php

namespace Smart\SurveyForm\Model\ResourceModel\Answer;

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

    public function _construct()
    {
        $this->_init(
            "Smart\SurveyForm\Model\AnswerModel",
            "Smart\SurveyForm\Model\ResourceModel\Answer\AnswerModel"
        );
    }
}
