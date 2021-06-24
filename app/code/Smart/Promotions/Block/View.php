<?php

namespace Smart\Promotions\Block;

use Magento\Cms\Model\Template\FilterProvider;
use Magento\Framework\App\Request\Http;
use Magento\Framework\DataObject\IdentityInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Stdlib\DateTime\TimezoneInterface;
use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\Template;
use Magento\Store\Model\StoreManagerInterface;
use Smart\Promotions\Model\Promotions;
use Smart\Promotions\Model\ResourceModel\Promotions\CollectionFactory;

/**
 * Class Promotions
 *
 * @package Smart\Promotions\Block
 */
class View extends Template implements IdentityInterface
{
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
     * @var Http
     */
    protected $request;
    /**
     * @var FilterProvider
     */
    protected $filterProvider;
    /**
     * @var TimezoneInterface
     */
    protected $date;

    /**
     * View constructor.
     * @param FilterProvider $filterProvider
     * @param Http $request
     * @param TimezoneInterface $date
     * @param Promotions $promotionsModel
     * @param CollectionFactory $collectionFactory
     * @param Template\Context $context
     * @param array $data
     */
    public function __construct(
        FilterProvider $filterProvider,
        Http $request,
        TimezoneInterface $date,
        Promotions $promotionsModel,
        CollectionFactory $collectionFactory,
        Template\Context $context,
        array $data = []
    )
    {
        $this->date = $date;
        $this->filterProvider = $filterProvider;
        $this->request = $request;
        $this->collectionFactory = $collectionFactory;
        $this->promotionsModel = $promotionsModel;
        $this->storeManager = $context->getStoreManager();
        parent::__construct($context, $data);
    }

    /**
     * Flush cache id
     *
     * @return array|string[]
     */
    public function getIdentities()
    {
        $identities[] = Promotions::CACHE_TAG . '_' . $this->getPromotionsId();
        return $identities;
    }

    /**
     * Get Promotion id
     *
     * @return int|mixed
     */
    public function getPromotionsId()
    {
        return ($this->request->getParam('id')) ? $this->request->getParam('id') : '';
    }

    /**
     * @return mixed
     */
    public function getPromoCollection()
    {
        $collection = $this->collectionFactory->create();
        $collection->addFieldToFilter('id', $this->getPromotionsId());
        return $collection;
    }

    /**
     * Get image URL
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
     * Get content type wysiwyg
     *
     * @param string $content
     * @return string
     * @throws \Exception
     */
    public function getContentWYSIWYG($content)
    {
        return $this->filterProvider->getBlockFilter()->filter($content);
    }

    /**
     * Format Date
     *
     * @param string $date
     * @return string
     */
    public function formatDatePromotion($date)
    {
        return date('d-m-Y', strtotime($date));
    }

    /**
     * Prepare Layout
     *
     * @return $this|Template
     */
    protected function _prepareLayout()
    {
        parent::_prepareLayout();
        return $this;
    }
}
