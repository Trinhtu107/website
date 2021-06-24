<?php

namespace Smart\SurveyForm\Controller\Index;

use Exception;
use Magento\Customer\Model\Session;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\Result\Redirect;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\View\Result\PageFactory;
use Smart\Customer\Api\Data\CustomerExtensionAttributeInterface as CustomerAttribute;
use Smart\SurveyForm\Model\AnswerModelFactory;
use Smart\SurveyForm\Model\FormModelFactory;
use Smart\SurveyForm\Model\ResourceModel\Question\CollectionFactory;
use Smart\LsSync\Helper\LsrGiftCardHelper;
use Smart\THEcom\Model\Customer\SurveyForm;
use Smart\THEcom\Model\Data\Customer\SurveyFormDataFactory;
use Smart\THEcom\Api\SurveyFormInterface;
use Zend\Log\Logger;
use Zend\Log\Writer\Stream;

class Post extends Action
{
    /**
     * @var PageFactory
     */
    protected $resultPageFactory;
    /**
     * @var AnswerModelFactory
     */
    protected $answerModelFactory;
    /**
     * @var Session
     */
    protected $session;
    /**
     * @var CollectionFactory
     */
    protected $collectionFactory;
    /**
     * @var FormModelFactory
     */
    private $formModelFactory;
    /**
     * @var LsrGiftCardHelper
     */
    private $giftCardHelper;
    /**
     * @var SurveyFormDataFactory
     */
    private $surveyFormDataFactory;
    /**
     * @var SurveyForm
     */
    private $surveyForm;

    /**
     * Post constructor.
     * @param PageFactory $resultPageFactory
     * @param AnswerModelFactory $answerModelFactory
     * @param Session $session
     * @param CollectionFactory $collectionFactory
     * @param Context $context
     * @param FormModelFactory $formModelFactory
     * @param LsrGiftCardHelper $giftCardHelper
     * @param SurveyFormDataFactory $surveyFormDataFactory
     * @param SurveyForm $surveyForm
     */
    public function __construct(
        PageFactory $resultPageFactory,
        AnswerModelFactory $answerModelFactory,
        Session $session,
        CollectionFactory $collectionFactory,
        Context $context,
        FormModelFactory $formModelFactory,
        LsrGiftCardHelper $giftCardHelper,
        SurveyFormDataFactory $surveyFormDataFactory,
        SurveyForm $surveyForm
    ) {
        $this->collectionFactory = $collectionFactory;
        $this->session = $session;
        $this->answerModelFactory = $answerModelFactory;
        $this->resultPageFactory = $resultPageFactory;
        parent::__construct($context);
        $this->formModelFactory = $formModelFactory;
        $this->giftCardHelper = $giftCardHelper;
        $this->surveyFormDataFactory = $surveyFormDataFactory;
        $this->surveyForm = $surveyForm;
    }

    /**
     * @return ResponseInterface|Redirect|ResultInterface
     * @throws Exception
     */
    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();
        $customerSession = $this->session;
        if ($customerSession->isLoggedIn()) {
            $customerId = $customerSession->getCustomerId();
            $model = $this->answerModelFactory->create();
            $postData = $this->getRequest()->getPost();
            if (!empty($postData)) {
                $formModel = $this->formModelFactory->create();
                $formId = $postData->getArrayCopy()['form_id'];
                $thPoint = $formModel->load($formId)->getThPoint();
                $answerArr = [];
                $typeArr = [];
                $status = true;
                foreach ($postData->getArrayCopy() as $key => $value) {
                    $answer = explode('_', $key);
                    if ($answer[0] == "answer") {
                        if ($answer[1] == "text") {
                            $answerArr[(int)$answer[2]] = $value;
                            $typeArr[(int)$answer[2]] = 1;
                        } elseif ($answer[1] == "select") {
                            $typeArr[(int)$answer[2]] = 2;
                            $answerArr[(int)$answer[2]] = $value;
                        } else {
                            $typeArr[(int)$answer[2]] = 3;
                            $answerArr[(int)$answer[2]] = implode(" || ", $value);
                        }
                    }
                }
                foreach ($answerArr as $key => $value) {
                    $model->setData(
                            [
                                "question_id" => $key,
                                "type_id" => $typeArr[$key],
                                "question" => $this->getQuestion($key),
                                "answer" => $value,
                                "customer" => $customerId,
                                "form_id" => $formId
                            ]
                        );
                    $saveData = $model->save();
                    if (!$saveData) {
                        $status = false;
                    }
                }
                if ($status) {
                    $this->messageManager->addSuccess(
                        __('Thanks You!')
                    );
                    $this->surveyForm->giveThPoint($customerId, $formId);
                } else {
                    $this->messageManager->addErrorMessage(__('Insert failure!'));
                }
            }
        } else {
            return $this->resultRedirectFactory->create()->setPath('customer/account/login');
        }
        return $resultRedirect->setPath('*/*/');
    }

    /**
     * @param $questionId
     * @return mixed
     */
    public function getQuestion($questionId)
    {
        $Collection = $this->collectionFactory->create();
        $Collection->addFieldToFilter('id', ['eq' => $questionId])
            ->addFieldToSelect("question");
        return $Collection->getData()[0]['question'];
    }
}
