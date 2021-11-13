<?php

namespace Smart\SurveyForm\Controller\Index;

use Magento\Customer\Model\Session;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\View\Result\Page;
use Magento\Framework\View\Result\PageFactory;
use Smart\SurveyForm\Model\ResourceModel\Answer\CollectionFactory as AnswerCollectionFactory;

/**
 * Class View
 *
 * @package Smart\SurveyForm\Controller\Index
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
     * @var AnswerCollectionFactory
     */
    protected $answerCollectionFactory;

    /**
     * View constructor.
     * @param AnswerCollectionFactory $answerCollectionFactory
     * @param Session $session
     * @param PageFactory $resultPageFactory
     * @param Context $context
     */
    public function __construct(
        AnswerCollectionFactory $answerCollectionFactory,
        Session $session,
        PageFactory $resultPageFactory,
        Context $context
    ) {
        $this->session = $session;
        $this->answerCollectionFactory = $answerCollectionFactory;
        $this->resultPageFactory = $resultPageFactory;
        parent::__construct($context);
    }

    /**
     * @return ResponseInterface|ResultInterface|Page
     */
    public function execute()
    {
        $customerSession = $this->session;
        $formId = $this->_request->getParam('id');
        if ($customerSession->isLoggedIn()) {
            $customerId = $customerSession->getCustomerId();
            if (!$this->checkFormStatus($customerId, $formId)) {
                return $this->resultPageFactory->create();
            } else {
                return $this->resultRedirectFactory->create()->setPath('*/*/');
            }
        } else {
            return $this->resultRedirectFactory->create()->setPath('customer/account/login');
        }
    }

    /**
     * @param $customerId
     * @param $formId
     * @return bool
     */
    protected function checkFormStatus($customerId, $formId)
    {
        $collection = $this->answerCollectionFactory->create();
        $collection->addFieldToFilter('customer', ['eq' => $customerId]);
        $collection->addFieldToSelect('form_id');
        $arr= [];
        $i = 0;
        foreach ($collection->getItems() as $value) {
            if ($value['form_id'] != '') {
                $arr[$i] = $value['form_id'];
                $i++;
            }
        }
        return in_array($formId, $arr);
    }
}
