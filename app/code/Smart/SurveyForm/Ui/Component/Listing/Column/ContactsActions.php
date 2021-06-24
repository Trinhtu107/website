<?php
declare(strict_types=1);

namespace Smart\SurveyForm\Ui\Component\Listing\Column;

use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;

/**
 * Class ContactsActions
 *
 * @package Smart\SurveyForm\Ui\Component\Listing\Column
 */
class ContactsActions extends \Magento\Ui\Component\Listing\Columns\Column
{
    const QUESTION_PATH_DELETE = 'smart_survey/question/delete';
    /**
     * @var UrlInterface
     */
    protected $urlBuilder;

    /**
     * ContactsActions constructor.
     * @param ContextInterface $context
     * @param UiComponentFactory $uiComponentFactory
     * @param UrlInterface $urlBuilder
     * @param array $components
     * @param array $data
     */
    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        UrlInterface $urlBuilder,
        array $components = [],
        array $data = []
    ) {
        $this->urlBuilder = $urlBuilder;
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

    /**
     * Prepare source
     *
     * @param array $dataSource
     * @return array
     */
    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as &$item) {
                $item[$this->getData('name')]['edit'] = [
                    'callback' => [
                        [
                            'provider' => 'smart_form_listing_edit.areas.form.form'
                                . '.question_update_modal.update_question_form_loader',
                            'target' => 'destroyInserted',
                        ],
                        [
                            'provider' => 'smart_form_listing_edit.areas.form.form'
                                . '.question_update_modal',
                            'target' => 'openModal',
                        ],
                        [
                            'provider' => 'smart_form_listing_edit.areas.form.form'
                                . '.question_update_modal.update_question_form_loader',
                            'target' => 'render',
                            'params' => [
                                'id' => $item['id'],
                            ],
                        ]
                    ],
                    'href' => '#',
                    'label' => __('Edit'),
                    'hidden' => false
                ];
            }
        }

        return $dataSource;
    }
}
