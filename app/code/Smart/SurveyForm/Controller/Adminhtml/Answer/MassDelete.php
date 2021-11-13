<?php

namespace Smart\SurveyForm\Controller\Adminhtml\Answer;

use Exception;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\Result\Redirect;
use Magento\Framework\Controller\ResultInterface;
use Magento\Ui\Component\MassAction\Filter as FilterAlias;
use Smart\SurveyForm\Model\ResourceModel\Answer\CollectionFactory;

/**
 * Class MassDelete
 *
 * @package Smart\SurveyForm\Controller\Adminhtml\Answer
 */
class MassDelete extends Action
{
    /**
     * @var FilterAlias
     */
    protected $filter;
    /**
     * @var CollectionFactory
     */
    protected $collectionFactory;

    /**
     * MassDelete constructor.
     *
     * @param FilterAlias $filter
     * @param CollectionFactory $collectionFactory
     * @param Context $context
     */
    public function __construct(
        FilterAlias $filter,
        CollectionFactory $collectionFactory,
        Context $context
    ) {
        $this->filter = $filter;
        $this->collectionFactory = $collectionFactory;
        parent::__construct($context);
    }

    /**
     * Execute
     *
     * @return ResponseInterface|Redirect|ResultInterface
     */
    public function execute()
    {
        try {
            $logCollection = $this->filter->getCollection($this->collectionFactory->create());
            $itemsDeleted = 0;
            foreach ($logCollection as $item) {
                $item->delete();
                $itemsDeleted++;
            }
            $this->messageManager->addSuccess(__('A total of %1 question were deleted.', $itemsDeleted));
        } catch (Exception $e) {
            $this->messageManager->addError($e->getMessage());
        }
        $resultRedirect = $this->resultRedirectFactory->create();
        return $resultRedirect->setPath('*/*/');
    }
}
