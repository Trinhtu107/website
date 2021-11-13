<?php

namespace Smart\SurveyForm\Model\ResourceModel\Form;

/**
 * Class FormModel
 *
 * @package Smart\SurveyForm\Model\ResourceModel
 */
class FormModel extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    /**
     * @inheritDoc
     */
    public function _construct()
    {
        $this->_init("smart_survey_form_list", "id");
    }
}
