<?php
namespace Smart\Promotions\Block;

use Magento\Framework\DataObject\IdentityInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\Template;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Theme\Block\Html\Pager;
use Smart\Promotions\Model\Promotions;
use Smart\Promotions\Model\ResourceModel\Promotions\Collection;
use Smart\Promotions\Model\ResourceModel\Promotions\CollectionFactory;

/**
 * Class Promotions
 *
 * @package Smart\Promotions\Block
 */
class ListPromotions extends Template implements IdentityInterface
{
    const PROMO_URL = "sm_promo/index/view";

    /**
     * @var Promotions
     */
    protected $promotionsModel;
    /**
     * @var CollectionFactory
     */
    protected $collectionFactory;
    /**
     * @var StoreManagerInterface
     */
    protected $storeManager;
    /**
     * @var \Magento\Customer\Model\Session
     */
    private $customer;

    /**
     * ListPromotions constructor.
     * @param Promotions $promotionsModel
     * @param CollectionFactory $collectionFactory
     * @param Template\Context $context
     * @param array $data
     */
    public function __construct(
        Promotions $promotionsModel,
        CollectionFactory $collectionFactory,
        Template\Context $context,
        \Magento\Customer\Model\Session $customer,
        array $data = []
    ) {
        $this->collectionFactory = $collectionFactory;
        $this->promotionsModel = $promotionsModel;
        $this->storeManager = $context->getStoreManager();
        parent::__construct($context, $data);
        $this->customer = $customer;
    }

    /**
     * Flush cache
     *
     * @return array|string[]
     */
    public function getIdentities()
    {
        $identities = [];
        $identities[] = Promotions::CACHE_TAG;
        return $identities;
    }

    /**
     * Get data Collection
     *
     * @return Collection
     */
    public function getCountCollection($customer = null)
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $objDate = $objectManager->create('\Magento\Framework\Stdlib\DateTime\DateTime');
        $now = $objDate->gmtDate('Y-m-d');
        $page = ($this->getRequest()->getParam('p')) ? $this->getRequest()->getParam('p') : 1;
        $limit = ($this->getRequest()->getParam('limit')) ? $this->getRequest()->getParam('limit') : 3;
        $collection = $this->collectionFactory->create();
        $collection->addFieldToFilter('active', 1);
        $collection->addFieldToFilter('start_date',['lteq' => $now]);
        $collection->addFieldToFilter(
            ['end_date','end_date'],
            [
                ['gteq' => $now],
                ['null' => true]
            ]
        );
        $collection->setOrder('id', 'ASC');
        $collection->setPageSize($limit);
        $collection->setCurPage($page);
        return $collection;
    }

    /**
     * Prepare Layout
     *
     * @return $this|Template
     */
    protected function _prepareLayout()
    {
        parent::_prepareLayout();
        $this->pageConfig->getTitle()->set(__('Chương Trình Khuyến Mãi'));
        if ($this->getCountCollection()) {
            try {
                $pager = $this->getLayout()->createBlock(Pager::class, 'promotions.form')
                    ->setAvailableLimit([3 => 3, 6 => 6, 9 => 9, 12 => 12])
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
     * Get Image URL
     *
     * @param string $icon
     * @return string
     * @throws NoSuchEntityException
     */
    public function getImageUrl($icon)
    {
        $mediaUrl = $this->storeManager
            ->getStore()
            ->getBaseUrl(UrlInterface::URL_TYPE_MEDIA);
        return $mediaUrl . 'smart/survey/image/' . $icon;
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
