<?php

namespace Smart\Promotions\Controller\Adminhtml\Promotions;

use Exception;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\Result\Redirect;
use Magento\Framework\Controller\ResultInterface;
use Magento\Ui\Component\MassAction\Filter;
use Smart\Promotions\Model\ResourceModel\Promotions\CollectionFactory;

/**
 * Class MassDelete
 *
 * @package Smart\Promotions\Controller\Adminhtml\Promotions
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
     * MassDelete constructor.
     * @param Filter $filter
     * @param CollectionFactory $collectionFactory
     * @param Context $context
     */
    public function __construct(
        Filter $filter,
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
            $this->messageManager->addSuccess(__('A total of %1 Promotions were deleted.', $itemsDeleted));
        } catch (Exception $e) {
            $this->messageManager->addError($e->getMessage());
        }
        $resultRedirect = $this->resultRedirectFactory->create();
        return $resultRedirect->setPath('*/*/');
    }
}
