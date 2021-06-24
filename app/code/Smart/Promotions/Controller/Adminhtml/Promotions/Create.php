<?php

namespace Smart\Promotions\Controller\Adminhtml\Promotions;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\Result\Redirect;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\View\Result\PageFactory;

/**
 * Class Create
 *
 * @package Smart\Promotions\Controller\Adminhtml\Promotions
 */
class Create extends Action
{
    /**
     * @var bool|PageFactory
     */
    protected $resultPageFactory = false;

    /**
     * Create constructor.
     * @param Context $context
     * @param PageFactory $resultPageFactory
     */
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory
    ) {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
    }

    /**
     * Execute
     *
     * @return ResponseInterface|Redirect|ResultInterface
     */
    public function execute()
    {
        return $this->resultRedirectFactory->create()->setPath('*/*/edit');
    }
}
