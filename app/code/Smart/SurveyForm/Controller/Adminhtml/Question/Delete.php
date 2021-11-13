<?php
declare(strict_types=1);

namespace Smart\SurveyForm\Controller\Adminhtml\Question;

use Exception;
use Magento\Backend\App\Action;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\Controller\Result\Json;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\Controller\ResultInterface;
use Smart\SurveyForm\Model\QuestionModel;
use Psr\Log\LoggerInterface;

/**
 * Class Delete
 *
 * @package Smart\SurveyForm\Controller\Adminhtml\Question
 */
class Delete extends Action implements HttpPostActionInterface
{
    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    public const ADMIN_RESOURCE = 'Smart_SurveyForm::manage';
    /**
     * @var QuestionModel
     */
    protected $_model;
    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @var JsonFactory
     */
    private $resultJsonFactory;

    /**
     * Delete constructor.
     * @param Action\Context $context
     * @param LoggerInterface $logger
     * @param JsonFactory $resultJsonFactory
     * @param QuestionModel $model
     */
    public function __construct(
        Action\Context $context,
        LoggerInterface $logger,
        JsonFactory $resultJsonFactory,
        QuestionModel $model
    ) {
        parent::__construct($context);
        $this->_model = $model;
        $this->logger = $logger;
        $this->resultJsonFactory = $resultJsonFactory;
    }

    /**
     * Delete action
     *
     * @return Json
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function execute(): Json
    {
        $id = $this->getRequest()->getParam('id');
        $error = false;
        $message = '';
        if ($id) {
            try {
                $model = $this->_model;
                $model->load($id);
                $model->delete();
                $message = __('You deleted the question.');
            } catch (Exception $e) {
                $error = true;
                $message = __('We can\'t delete the question right now.');
                $this->logger->critical($e);
            }
        }
        $resultJson = $this->resultJsonFactory->create();
        $resultJson->setData(
            [
                'message' => $message,
                'error' => $error,
            ]
        );
        return $resultJson;
    }
}
