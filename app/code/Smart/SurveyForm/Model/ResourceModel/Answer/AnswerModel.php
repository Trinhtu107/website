<?php

namespace Smart\SurveyForm\Model\ResourceModel\Answer;

/**
 * Class QuestionModel
 *
 * @package Smart\SurveyForm\Model\ResourceModel
 */
class AnswerModel extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    /**
     * @inheritDoc
     */
    public function _construct()
    {
        $this->_init("smart_survey_answer", "id");
    }
}
