<?php
namespace Smart\SurveyForm\Block\ListForm;

use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\Template;
use Smart\SurveyForm\Model\FormModel;
use Smart\SurveyForm\Model\QuestionModel;
use Smart\SurveyForm\Model\ResourceModel\Answer\CollectionFactory as AnswerCollectionFactory;
use Smart\SurveyForm\Model\ResourceModel\Form\CollectionFactory as FormCollectionFactory;
use Smart\SurveyForm\Model\ResourceModel\Question\CollectionFactory;

/**
 * Class SurveyForm
 *
 * @package Smart\SurveyFrom\Block\ListForm
 */
class View extends Template implements \Magento\Framework\DataObject\IdentityInterface
{
    const SAVE_IMAGE = "smart/tmp/image/";

    const URL_SAVE = "surveyform/index/post";
    /**
     * @var \Magento\Framework\App\Request\Http
     */
    protected $request;
    /**
     * @var CollectionFactory
     */
    protected $collectionFactory;
    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;
    /**
     * @var FormCollectionFactory
     */
    protected $formCollectionFactory;
    /**
     * @var AnswerCollectionFactory
     */
    protected $answerCollectionFactory;

    /**
     * View constructor.
     * @param Template\Context $context
     * @param \Magento\Framework\App\Request\Http $request
     * @param FormCollectionFactory $formCollectionFactory
     * @param AnswerCollectionFactory $answerCollectionFactory
     * @param CollectionFactory $collectionFactory
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Framework\App\Request\Http $request,
        FormCollectionFactory $formCollectionFactory,
        AnswerCollectionFactory $answerCollectionFactory,
        CollectionFactory $collectionFactory,
        array $data = []
    ) {
        $this->formCollectionFactory = $formCollectionFactory;
        $this->answerCollectionFactory = $answerCollectionFactory;
        $this->storeManager = $context->getStoreManager();
        $this->collectionFactory = $collectionFactory;
        $this->request = $request;
        parent::__construct($context, $data);
    }

    /**
     * @return int|mixed
     */
    public function getFormId()
    {
        return ($this->request->getParam('id')) ? $this->request->getParam('id') : 1;
    }

    /**
     * @return mixed
     */
    public function getTitle()
    {
        $formCollection = $this->formCollectionFactory->create();
        $formCollection->addFieldToFilter('id', ['eq' => (int)$this->getFormId()])
            ->addFieldToSelect("title");
        return $formCollection->getData()[0]['title'];
    }

    /**
     * @return \Smart\SurveyForm\Model\ResourceModel\Question\Collection
     */
    public function getCollection()
    {
        $collection = $this->collectionFactory->create();
        $collection->addFieldToFilter('form_id', ['eq' => (int)$this->getFormId()])
            ->addFieldToSelect("*");
        return $collection;
    }

    /**
     * @param $answer
     * @return array
     */
    public function getAnswer($answer)
    {
        return explode(" || ", $answer);
    }

    /**
     * @param $icon
     * @return string
     * @throws NoSuchEntityException
     */
    public function getImageUrl($icon)
    {
        $mediaUrl = $this->storeManager
            ->getStore()
            ->getBaseUrl(UrlInterface::URL_TYPE_MEDIA);
        $imageUrl = $mediaUrl . (self::SAVE_IMAGE) . $icon;
        return $imageUrl;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        $formCollection = $this->formCollectionFactory->create();
        $formCollection->addFieldToFilter('id', ['eq' => (int)$this->getFormId()])
            ->addFieldToSelect("description");
        return $formCollection->getData()[0]['description'];
    }

    /**
     * @return string
     */
    public function getUrlPost()
    {
        return $this->getUrl() . (self::URL_SAVE);
    }

    /**
     * @return array|string[]
     */
    public function getIdentities()
    {
        $identities = [];
        $identities[] = QuestionModel::CACHE_TAG;
        $identities[] = FormModel::CACHE_TAG;
        return $identities;
    }

    /**
     * @return $this|Template
     */
    protected function _prepareLayout()
    {
        parent::_prepareLayout();
        $this->pageConfig->getTitle()->set(__('Khảo Sát Chi Tiết'));
        return $this;
    }
}
