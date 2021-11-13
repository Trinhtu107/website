<?php

namespace Smart\Promotions\Controller\Adminhtml\Promotions;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\View\Result\Page;
use Magento\Backend\Model\View\Result\Redirect;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Registry;
use Magento\Framework\View\Result\PageFactory;
use Smart\Promotions\Model\PromotionsFactory;

/**
 * Class Edit
 *
 * @package Smart\Promotions\Controller\Adminhtml\Promotions
 */
class Edit extends Action
{
    /**
     * @var bool|PageFactory
     */
    protected $resultPageFactory = false;
    /**
     * @var Registry
     */
    protected $coreRegistry;
    /**
     * @var PromotionsFactory
     */
    protected $promotionsModel;

    /**
     * Edit constructor.
     * @param PromotionsFactory $promotionsModel
     * @param Context $context
     * @param Registry $coreRegistry
     * @param PageFactory $resultPageFactory
     */
    public function __construct(
        PromotionsFactory $promotionsModel,
        Context $context,
        Registry $coreRegistry,
        PageFactory $resultPageFactory
    ) {
        $this->promotionsModel = $promotionsModel;
        $this->coreRegistry = $coreRegistry;
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
    }

    /**
     * Execute
     *
     * @return Page|Redirect|ResponseInterface|ResultInterface
     */
    public function execute()
    {

        $id = $this->getRequest()->getParam('id');
        $model = $this->promotionsModel->create();

        if ($id) {
            $model->load($id);
            if (!$model->getId()) {
                $this->messageManager->addErrorMessage(__('This Promotions not exists.'));
                /** @var Redirect $resultRedirect */
                $resultRedirect = $this->resultRedirectFactory->create();
                return $resultRedirect->setPath('*/*/');
            }
        }
        $this->coreRegistry->register('smart_promotions_table', $model);

        /** @var Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $this->initPage($resultPage)->addBreadcrumb(
            $id ? __('Edit Promotions') : __('New Promotions'),
            $id ? __('Edit Promotions') : __('New Promotions')
        );
        $resultPage->getConfig()->getTitle()->prepend(__('Edit Promotions'));
        $resultPage->getConfig()->getTitle()->prepend($model->getId() ? __('Edit Promotions') : __('New Promotions'));
        return $resultPage;
    }

    /**
     * Check page
     *
     * @param $resultPage
     * @return mixed
     */
    public function initPage($resultPage)
    {
        $resultPage->setActiveMenu(self::ADMIN_RESOURCE)
            ->addBreadcrumb(__('New'), __('New'))
            ->addBreadcrumb(__('Edit'), __('Edit'));
        return $resultPage;
    }
}
