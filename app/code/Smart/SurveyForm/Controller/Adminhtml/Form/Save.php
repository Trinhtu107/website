<?php

namespace Smart\SurveyForm\Controller\Adminhtml\Form;

use Exception;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\Result\Redirect;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Registry;
use Magento\Framework\Stdlib\DateTime\TimezoneInterface;
use Magento\Framework\UrlInterface;
use Smart\SurveyForm\Model\FormModelFactory;
use Smart\SurveyForm\Model\ResourceModel\Form\CollectionFactory;

/**
 * Class Save
 *
 * @package Smart\SurveyForm\Controller\Adminhtml\Form
 */
class Save extends Action implements HttpPostActionInterface
{
    const ADMIN_RESOURCE = 'Smart_Survey::save';
    /**
     * @var DataPersistorInterface
     */
    private $dataPersistor;

    /**
     * @var FormModelFactory
     */
    protected $formModelFactory;

    /**
     * @var CollectionFactory
     */
    protected $collectionFactory;
    /**
     * @var TimezoneInterface
     */
    protected $timezone;
    /**
     * Save constructor.
     * @param Context $context
     * @param Registry $registry
     * @param DataPersistorInterface $dataPersistor
     * @param CollectionFactory $collectionFactory
     * @param UrlInterface $url
     * @param FormModelFactory $formModelFactory
     */
    public function __construct(
        Context $context,
        Registry $registry,
        DataPersistorInterface $dataPersistor,
        CollectionFactory $collectionFactory,
        UrlInterface $url,
        FormModelFactory $formModelFactory,
        TimezoneInterface $timezone
    ) {
        $this->timezone = $timezone;
        $this->collectionFactory = $collectionFactory;
        $this->url = $url;
        $this->formModelFactory = $formModelFactory;
        $this->dataPersistor = $dataPersistor;
        $this->registry = $registry;
        parent::__construct($context);
    }

    /**
     * Execute Function
     *
     * @return ResponseInterface|Redirect|ResultInterface
     */
    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();
        $data = $this->getRequest()->getParams()['general'];
        if ($data) {
            $model = $this->formModelFactory->create();
            $date = $data['start_date'];
            if (!$date) {
                $date = $this->timezone->date()->format('d-m-Y');
            }
            $id = isset($data['id']) ? $data['id'] : null;
            if (!$id) {
                $model->setActive($data['active']);
                $model->setTitle($data['title']);
                $model->setThumbnailImage($data["thumbnail_image"][0]['name']);
                $model->setDescription($data['description']);
                $model->setStartDate($date);
                $model->setEndDate($data['end_date']);
                $model->setThPoint($data['th_point']);
                try {
                    $model->save();
                    $this->messageManager->addSuccess(__('Insert Record Successfully!'));
                    $this->dataPersistor->clear('smart_survey_form_list');
                } catch (Exception $e) {
                    $this->messageManager->addError($e->getMessage());
                }
            } else {
                $dataNew = $model->load($id);
                $dataNew->setActive($data['active']);
                $dataNew->setTitle($data['title']);
                $dataNew->setThumbnailImage($data["thumbnail_image"][0]['name']);
                $dataNew->setDescription($data['description']);
                $dataNew->setStartDate($date);
                $dataNew->setEndDate($data['end_date']);
                $model->setThPoint($data['th_point']);
                try {
                    $dataNew->save();
                    $this->messageManager->addSuccess(__('Update success!'));
                    $this->dataPersistor->clear('smart_promotions_table');
                } catch (Exception $e) {
                    $this->messageManager->addError($e->getMessage());
                }
            }
        }
        if ($this->getRequest()->getParam('back')) {
            return $resultRedirect->setPath(
                '*/*/edit',
                ['id' => $model->getId()]
            );
        }
        return $resultRedirect->setPath('*/*/');
    }
}
