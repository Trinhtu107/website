<?php
namespace Smart\SurveyForm\Block\ListForm;

use Magento\Customer\Model\SessionFactory;
use Magento\Framework\DataObject\IdentityInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Stdlib\DateTime\TimezoneInterface;
use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Theme\Block\Html\Pager;
use Smart\SurveyForm\Model\AnswerModel;
use Smart\SurveyForm\Model\FormModel;
use Smart\SurveyForm\Model\ResourceModel\Answer\CollectionFactory as AnswerCollectionFactory;
use Smart\SurveyForm\Model\ResourceModel\Form\Collection;
use Smart\SurveyForm\Model\ResourceModel\Form\CollectionFactory;

/**
 * Class SurveyForm
 *
 * @package Smart\SurveyFrom\Block\ListForm
 */
class SurveyForm extends Template implements IdentityInterface
{
    /**
     * @var CollectionFactory
     */
    protected $collectionFactory;
    /**
     * @var StoreManagerInterface
     */
    private $storeManager;
    /**
     * @var AnswerCollectionFactory
     */
    protected $answerCollectionFactory;
    /**
     * @var SessionFactory
     */
    protected $customerSessionFactory;
    /**
     * @var AnswerModel
     */
    protected $formModel;
    /**
     * @var TimezoneInterface
     */
    protected $date;

    /**
     * SurveyForm constructor.
     * @param AnswerModel $formModel
     * @param SessionFactory $customerSessionFactory
     * @param CollectionFactory $collectionFactory
     * @param AnswerCollectionFactory $answerCollectionFactory
     * @param TimezoneInterface $date
     * @param Context $context
     * @param array $data
     */
    public function __construct(
        AnswerModel $formModel,
        SessionFactory $customerSessionFactory,
        CollectionFactory $collectionFactory,
        AnswerCollectionFactory $answerCollectionFactory,
        TimezoneInterface $date,
        Context $context,
        array $data = []
    ) {
        $this->date = $date;
        $this->formModel = $formModel;
        $this->customerSessionFactory = $customerSessionFactory;
        $this->answerCollectionFactory = $answerCollectionFactory;
        $this->collectionFactory = $collectionFactory;
        $this->storeManager = $context->getStoreManager();
        parent::__construct($context, $data);
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
        $imageUrl = $mediaUrl . 'smart/survey/image/' . $icon;
        return $imageUrl;
    }

    /**
     * @return Collection
     */
    public function getCountCollection()
    {
        $arr =  array_unique($this->checkFormStatus());
        $date = $this->date->date()->format('Y-m-d');
        $page = ($this->getRequest()->getParam('p')) ? $this->getRequest()->getParam('p') : 1;
        $limit = ($this->getRequest()->getParam('limit')) ? $this->getRequest()->getParam('limit') : 6;
        $collection = $this->collectionFactory->create();
        $collection->addFieldToFilter('active', 1);
        $collection->addFieldToFilter('start_date', ['lteq' => $date]);
        $collection->addFieldToFilter(
            ['end_date', 'end_date'],
            [
                ['null' => true],
                ['gteq' => $date]
            ]
        );
        if ($arr != []) {
            $collection->addFieldToFilter('id', ['nin' => $arr]);
        }
        $collection->setOrder('id', 'ASC');
        $collection->setPageSize($limit);
        $collection->setCurPage($page);
        return $collection;
    }

    /**
     * @return array
     */
    public function checkFormStatus()
    {
        $customerSession = $this->customerSessionFactory->create();
        $arr = [];
        if ($customerSession->isLoggedIn()) {
            $customerId = $customerSession->getCustomerId();
            $collection = $this->answerCollectionFactory->create();
            $collection->addFieldToFilter('customer', ['eq' => $customerId]);
            $collection->addFieldToSelect('form_id');
            $i = 0;
            foreach ($collection->getItems() as $value) {
                if ($value['form_id'] != '') {
                    $arr[$i] = $value['form_id'];
                    $i++;
                }
            }
            return $arr;
        }
        return $arr;
    }

    /**
     * @return $this|Template
     * @throws LocalizedException
     */
    protected function _prepareLayout()
    {
        parent::_prepareLayout();
        $this->pageConfig->getTitle()->set(__('Khảo sát'));
        if ($this->getCountCollection()) {
            try {
                $pager = $this->getLayout()->createBlock(Pager::class, 'survey.form')
                    ->setAvailableLimit([6 => 6, 12 => 12, 18 => 18, 24 => 24])
                    ->setShowPerPage(true)
                    ->setCollection($this->getCountCollection());
            } catch (LocalizedException $e) {
            }

            $this->setChild('pager', $pager);

            $this->getCountCollection()->load();
        }
        return $this;
    }

    /**
     * @return array|string[]
     */
    public function getIdentities()
    {
        $identities = [];
        $identities[] = AnswerModel::CACHE_TAG;
        $identities[] = FormModel::CACHE_TAG;
        return $identities;
    }

    /**
     * @param $time
     * @return string
     */
    public function fomatCreatedDate($time)
    {
        return $this->date->date($time)->format('d-m-Y');
    }

    /**
     * Get Page
     *
     * @return string
     */
    public function getPagerHtml()
    {
        return $this->getChildHtml('pager');
    }
}
