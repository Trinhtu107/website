<?php

namespace Smart\SurveyForm\Block\Adminhtml\Question;

use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;

/**
 * Class SaveButton
 *
 * @package Smart\SurveyForm\Block\Adminhtml\Question
 */
class SaveButton extends GenericButton implements ButtonProviderInterface
{
    /**
     * GetButtonData
     *
     * @return array
     */
    public function getButtonData()
    {
        return [
            'label' => __('Save'),
            'class' => 'save primary',
            'data_attribute' => [
                'mage-init' => ['button' => ['event' => 'save']],
                'form-role' => 'save',
            ],
            'sort_order' => 10
        ];
    }
}
