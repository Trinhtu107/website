<?php

namespace Smart\SurveyForm\Block\Adminhtml\Question;

use Smart\SurveyForm\Ui\Component\Listing\Column\ContactsActions;
use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;

/**
 * Class DeleteButton
 *
 * @package Smart\SurveyForm\Block\Adminhtml\Question
 */
class DeleteQuestionButton extends GenericButton implements ButtonProviderInterface
{
    /**
     * Get delete button data.
     *
     * @return array
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getButtonData()
    {
        $data = [];
        if ($this->getId()) {
            $data = [
                'label' => __('Delete'),
                'on_click' => '',
                'data_attribute' => [
                    'mage-init' => [
                        'Magento_Ui/js/form/button-adapter' => [
                            'actions' => [
                                [
                                    'targetName' => 'smart_question_listing_edit.smart_question_listing_edit',
                                    'actionName' => 'deleteQuestion',
                                    'params' => [
                                        $this->getDeleteUrl(),
                                    ],

                                ]
                            ],
                        ],
                    ],
                ],
                'sort_order' => 20
            ];
        }
        return $data;
    }

    /**
     * Get delete button url.
     *
     * @return string
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    private function getDeleteUrl(): string
    {
        return $this->getUrl(
            ContactsActions::QUESTION_PATH_DELETE,
            ['id' => $this->getId()]
        );
    }
}
