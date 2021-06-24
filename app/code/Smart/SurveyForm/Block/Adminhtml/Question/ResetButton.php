<?php

namespace Smart\SurveyForm\Block\Adminhtml\Question;

use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;

/**
 * Class ResetButton
 *
 * @package Smart\SurveyForm\Block\Adminhtml\Question
 */
class ResetButton extends GenericButton implements ButtonProviderInterface
{
    /**
     * GetButtonData
     *
     * @return array
     */
    public function getButtonData()
    {
        return [
            'label' => __('Reset'),
            'on_click' => 'javascript: location.reload();',
            'class' => 'reset',
            'sort_order' => 30
        ];
    }
}
