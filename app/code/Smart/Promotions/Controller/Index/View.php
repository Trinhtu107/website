<?php

namespace Smart\Promotions\Controller\Index;

use Magento\Customer\Model\Session;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\View\Result\Page;
use Magento\Framework\View\Result\PageFactory;
use Smart\Promotions\Model\PromotionsFactory;

/**
 * Class View
 *
 * @package Smart\Promotions\Controller\Index
 */
class View extends Action
{
    /**
     * @var PageFactory
     */
    protected $resultPageFactory;
    /**
     * @var Session
     */
    protected $session;
    /**
     * @var PromotionsFactory
     */
    protected $promotionsModel;

    /**
     * View constructor.
     * @param PromotionsFactory $promotionsModel
     * @param Session $session
     * @param PageFactory $resultPageFactory
     * @param Context $context
     */
    public function __construct(
        PromotionsFactory $promotionsModel,
        Session $session,
        PageFactory $resultPageFactory,
        Context $context
    ) {
        $this->promotionsModel = $promotionsModel;
        $this->session = $session;
        $this->resultPageFactory = $resultPageFactory;
        parent::__construct($context);
    }

    /**
     * Execute
     *
     * @return ResponseInterface|\Magento\Framework\Controller\Result\Redirect|ResultInterface|Page
     */
    public function execute()
    {
        $customerSession = $this->session;
        if ($customerSession->isLoggedIn()) {
            $id = $this->getRequest()->getParam('id');
            $model = $this->promotionsModel->create();
            if ($id) {
                $model->load($id);
                if (!$model->getId()) {
                    $this->messageManager->addErrorMessage(__('This Promotions not exists.'));
                    return $this->resultRedirectFactory->create()->setPath('*/*/');
                } else {
                    return $this->resultPageFactory->create();
                }
            } else {
                return $this->resultRedirectFactory->create()->setPath('*/*/');
            }
        }
        return $this->resultRedirectFactory->create()->setPath('customer/account/login');
    }
}
