<?php

namespace Smart\SurveyForm\Model\ResourceModel\Question;

/**
 * Class QuestionModel
 *
 * @package Smart\SurveyForm\Model\ResourceModel
 */
class QuestionModel extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    /**
     * Mixin
     */
    public function _construct()
    {
        $this->_init("smart_survey_question", "id");
    }
}
