<?php

namespace Smart\SurveyForm\Controller\Adminhtml\Form;

use Exception;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\Result\Redirect;
use Magento\Framework\Controller\ResultInterface;
use Magento\Ui\Component\MassAction\Filter;
use Smart\SurveyForm\Model\ResourceModel\Form\CollectionFactory;
use Smart\SurveyForm\Model\ResourceModel\Question\CollectionFactory as QuestionFactory;

/**
 * Class MassDelete
 *
 * @package Smart\SurveyForm\Controller\Adminhtml\Form
 */
class MassDelete extends Action
{
    /**
     * @var Filter
     */
    private $filter;
    /**
     * @var CollectionFactory
     */
    private $collectionFactory;
    /**
     * @var QuestionFactory
     */
    protected $questionFactory;

    /**
     * MassDelete constructor.
     *
     * @param Filter $filter
     * @param CollectionFactory $collectionFactory
     * @param QuestionFactory $questionFactory
     * @param Context $context
     */
    public function __construct(
        Filter $filter,
        CollectionFactory $collectionFactory,
        QuestionFactory $questionFactory,
        Context $context
    ) {
        $this->questionFactory = $questionFactory;
        $this->filter = $filter;
        $this->collectionFactory = $collectionFactory;
        parent::__construct($context);
    }

    /**
     * @return ResponseInterface|Redirect|ResultInterface
     */
    public function execute()
    {
        try {
            $logCollection = $this->filter->getCollection($this->collectionFactory->create());
            $itemsDeleted = 0;
            foreach ($logCollection as $item) {
                $item->delete();
                $this->deleteQuestion($item->getId());
                $itemsDeleted++;
            }

            $this->messageManager->addSuccess(__('A total of %1 Form were deleted.', $itemsDeleted));
        } catch (Exception $e) {
            $this->messageManager->addError($e->getMessage());
        }
        $resultRedirect = $this->resultRedirectFactory->create();
        return $resultRedirect->setPath('*/*/');
    }

    /**
     * Delete question of form
     *
     * @param int $formId
     */
    protected function deleteQuestion($formId)
    {
        $questionCollection = $this->questionFactory->create();
        $question = $questionCollection->addFieldToFilter('form_id', ['eq' => $formId])
            ->addFieldToSelect('*');
        foreach ($question as $item) {
            $item->delete();
        }
    }

}
