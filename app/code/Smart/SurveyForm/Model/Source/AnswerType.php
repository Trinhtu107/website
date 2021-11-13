<?php

namespace Smart\SurveyForm\Model\Source;

/**
 * Class AnswerType
 *
 * @package Smart\SurveyForm\Model\Source
 */
class AnswerType implements \Magento\Framework\Option\ArrayInterface
{
    /**
     * Array Answer type
     *
     * @return array
     */
    public function toOptionArray()
    {
        return [
            ['value' => 1, 'label' => __('Text')],
            ['value' => 2, 'label' => __('Select')],
            ['value' => 3, 'label' => __('Multiselect')],
        ];
    }
}
