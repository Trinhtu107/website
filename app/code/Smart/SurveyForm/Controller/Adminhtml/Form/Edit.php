<?php

namespace Smart\SurveyForm\Controller\Adminhtml\Form;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\View\Result\Page;
use Magento\Backend\Model\View\Result\Redirect;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Registry;
use Magento\Framework\View\Result\PageFactory;

/**
 * Class Edit
 *
 * @package Smart\SurveyForm\Controller\Adminhtml\Form
 */
class Edit extends Action
{
    const ADMIN_RESOURCE = 'smart_survey_form::save';
    /**
     * @var bool|PageFactory
     */
    protected $resultPageFactory = false;
    /**
     * @var Registry
     */
    protected $coreRegistry;

    /**
     * Edit constructor.
     * @param Context $context
     * @param Registry $coreRegistry
     * @param PageFactory $resultPageFactory
     */
    public function __construct(
        Context $context,
        Registry $coreRegistry,
        PageFactory $resultPageFactory
    ) {
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
        $model = $this->_objectManager->create('Smart\SurveyForm\Model\FormModel');

        // 2. Initial checking
        if ($id) {
            $model->load($id);
            if (!$model->getId()) {
                $this->messageManager->addErrorMessage(__('This Form not exists.'));
                /** @var Redirect $resultRedirect */
                $resultRedirect = $this->resultRedirectFactory->create();
                return $resultRedirect->setPath('*/*/');
            }
        }
        $this->coreRegistry->register('smart_survey_form_list', $model);

        // 5. Build edit form
        /** @var Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $this->initPage($resultPage)->addBreadcrumb(
            $id ? __('Edit Form') : __('New Survey Form'),
            $id ? __('Edit Form') : __('New Survey Form')
        );
        $resultPage->getConfig()->getTitle()->prepend(__('Edit Form'));
        $resultPage->getConfig()->getTitle()->prepend($model->getId() ? __('Edit Form') : __('New Survey Form'));
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
