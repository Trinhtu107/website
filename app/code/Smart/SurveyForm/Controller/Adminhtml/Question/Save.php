<?php
declare(strict_types=1);

namespace Smart\SurveyForm\Controller\Adminhtml\Question;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\Result\Json;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\Controller\Result\Redirect;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Registry;
use Magento\Framework\UrlInterface;
use Smart\SurveyForm\Api\Repository\QuestionRepositoryInterface;
use Smart\SurveyForm\Model\QuestionModelFactory;
use Smart\SurveyForm\Model\ResourceModel\Question\CollectionFactory;
use Psr\Log\LoggerInterface;

/**
 * Class for saving of customer address
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class Save extends Action implements HttpPostActionInterface
{
    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    public const ADMIN_RESOURCE = 'Smart_SurveyForm::manage';
    /**
     * @var DataPersistorInterface
     */
    private $dataPersistor;
    /**
     * @var QuestionModelFactory
     */
    protected $questionModelFactory;
    /**
     * @var QuestionRepositoryInterface
     */
    protected $questionRepository;
    /**
     * @var CollectionFactory
     */
    protected $collectionFactory;
    protected $resultJsonFactory;
    protected $logger;
    /**
     * Save constructor.
     * @param Context $context
     * @param Registry $registry
     * @param DataPersistorInterface $dataPersistor
     * @param CollectionFactory $collectionFactory
     * @param UrlInterface $url
     * @param QuestionModelFactory $questionModelFactory
     * @param QuestionRepositoryInterface $questionRepository
     */
    public function __construct(
        Action\Context $context,
        Registry $registry,
        DataPersistorInterface $dataPersistor,
        CollectionFactory $collectionFactory,
        UrlInterface $url,
        QuestionModelFactory $questionModelFactory,
        QuestionRepositoryInterface $questionRepository,
        JsonFactory $resultJsonFactory,
        LoggerInterface $logger
    ) {
        parent::__construct($context);
        $this->resultJsonFactory = $resultJsonFactory;
        $this->collectionFactory = $collectionFactory;
        $this->url = $url;
        $this->questionModelFactory = $questionModelFactory;
        $this->questionRepository = $questionRepository;
        $this->dataPersistor = $dataPersistor;
        $this->registry = $registry;
        $this->logger = $logger;

    }

    /**
     * Execute Function
     *
     * @return Json
     */
    public function execute(): Json
    {
        $formId = $this->getRequest()->getParam('form_id', false);
        $id = $this->getRequest()->getParam('id', false);
        $data = $this->getRequest()->getParams();
        $error = false;
        try {
            $model = $this->questionModelFactory->create();
            $lengthSelect = (isset($data['data']['type_selected'])) ?
                count($data['data']['type_selected']) : 0;
            $lengthMulti = (isset($data['data']['type_multiselect'])) ?
                count($data['data']['type_multiselect']) : 0;
            if (!$id) {
                $select = '';
                if (($lengthSelect > 0) || ($lengthMulti > 0)) {
                    if ($data['type_id'] == 2) {
                        $select = $data['data']['type_selected'][0]['answer_select'];
                        for ($i = 1; $i < $lengthSelect; $i++) {
                            $select = $select . ' || ' . $data['data']['type_selected'][$i]['answer_select'];
                        }
                    }
                    if ($data['type_id'] == 3) {
                        $select = $data['data']['type_multiselect'][0]['answer_multi'];
                        for ($i = 1; $i < $lengthMulti; $i++) {
                            $select = $select . ' || ' . $data['data']['type_multiselect'][$i]['answer_multi'];
                        }
                    }
                }
                $answer = $select;
                $model->setQuestion($data['question']);
                $model->setActive($data['active']);
                $model->setTypeId($data['type_id']);
                $model->setFormId($formId);
                $model->setAnswer($answer);
                try {
                    $model->save();
                    $message = __('New survey form has been added.');
                } catch (\Exception $e) {
                    $this->messageManager->addError($e->getMessage());
                }
                $id = $model->getId();
            } else {
                $select = '';
                if ($data['type_id'] == 2) {
                    $select = $data['data']['type_selected'][0]['answer_select'];
                    for ($i = 1; $i < $lengthSelect; $i++) {
                        $select = $select . ' || ' . $data['data']['type_selected'][$i]['answer_select'];
                    }
                }
                if ($data['type_id'] == 3) {
                    $select = $data['data']['type_multiselect'][0]['answer_multi'];
                    for ($i = 1; $i < $lengthMulti; $i++) {
                        $select = $select . ' || ' . $data['data']['type_multiselect'][$i]['answer_multi'];
                    }
                }

                $answer = $select;
                $dataNew = $model->load($id);
                $dataNew->setQuestion($data['question']);
                $dataNew->setActive($data['active']);
                $dataNew->setTypeId($data['type_id']);
                $model->setFormId($formId);
                $dataNew->setAnswer($answer);
                try {
                    $dataNew->save();
                    $message = __('Survey form has been updated.');
                } catch (\Exception $e) {
                    $this->messageManager->addError($e->getMessage());
                }
            }
        } catch (NoSuchEntityException $e) {
            $this->logger->critical($e);
            $error = true;
            $message = __('There is no customer with such id.');
        } catch (LocalizedException $e) {
            $error = true;
            $message = __($e->getMessage());
            $this->logger->critical($e);
        } catch (\Exception $e) {
            $error = true;
            $message = __('We can\'t change customer address right now.');
            $this->logger->critical($e);
        }

        $id = empty($id) ? null : $id;
        $resultJson = $this->resultJsonFactory->create();
        $resultJson->setData(
            [
                'message' => $message,
                'error' => $error,
                'data' => [
                    'id' => $id
                ]
            ]
        );

        return $resultJson;
    }
}
