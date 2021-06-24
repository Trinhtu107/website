<?php

namespace Smart\Promotions\Model\Data;

use Magento\Framework\App\ObjectManager;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\AuthorizationInterface;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Ui\DataProvider\Modifier\PoolInterface;
use Smart\Promotions\Model\ResourceModel\Promotions\CollectionFactory;

/**
 * Class DataProvider
 *
 * @package Smart\Promotions\Model\Data
 */
class DataProvider extends \Magento\Ui\DataProvider\ModifierPoolDataProvider
{
    /**
     * @var \Magento\Cms\Model\ResourceModel\Page\Collection
     */
    protected $collection;

    /**
     * @var DataPersistorInterface
     */
    protected $dataPersistor;

    /**
     * @var array
     */
    protected $loadedData;

    /**
     * @var AuthorizationInterface
     */
    private $auth;
    /**
     * @var StoreManagerInterface
     */
    protected $storeManager;
    /**
     * @var \Magento\Customer\Model\ResourceModel\Group\Collection
     */
    private $customerGroup;
    /**
     * @var \Magento\Customer\Model\CustomerFactory
     */
    private $customerFactory;

    /**
     * DataProvider constructor.
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param CollectionFactory $promotionCollectionFactory
     * @param DataPersistorInterface $dataPersistor
     * @param StoreManagerInterface $storeManager
     * @param array $meta
     * @param array $data
     * @param PoolInterface|null $pool
     * @param AuthorizationInterface|null $auth
     */
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        CollectionFactory $promotionCollectionFactory,
        DataPersistorInterface $dataPersistor,
        StoreManagerInterface $storeManager,
        \Magento\Customer\Model\ResourceModel\Group\Collection $customerGroup,
        \Magento\Customer\Model\CustomerFactory $customerFactory,
        array $meta = [],
        array $data = [],
        PoolInterface $pool = null,
        ?AuthorizationInterface $auth = null
    )
    {
        $this->collection = $promotionCollectionFactory->create();
        $this->dataPersistor = $dataPersistor;
        $this->storeManager = $storeManager;
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data, $pool);
        $this->auth = $auth ?? ObjectManager::getInstance()->get(AuthorizationInterface::class);
        $this->meta = $this->prepareMeta($this->meta);
        $this->customerGroup = $customerGroup;
        $this->customerFactory = $customerFactory;
    }

    /**
     * Prepares Meta
     *
     * @param array $meta
     * @return array
     */
    public function prepareMeta(array $meta)
    {
        return $meta;
    }

    /**
     * Get Data
     *
     * @return array
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getData()
    {
        if (isset($this->loadedData)) {
            return $this->loadedData;
        }
        $items = $this->collection->getItems();
        foreach ($items as $promotion) {
            $this->loadedData[$promotion->getId()] = $promotion->getData();
            if ($promotion->getImage()) {
                $this->loadedData[$promotion->getId()]['image'] = [
                    [
                        'name' => $promotion->getImage(),
                        'url' => $this->getMediaUrl() . $promotion->getImage(),
                        'type' => 'image',
                    ],
                ];
            }
            if ($promotion->getImageDetail()) {
                $this->loadedData[$promotion->getId()]['imageDetail'] = [
                    [
                        'name' => $promotion->getImageDetail(),
                        'url' => $this->getMediaUrl() . $promotion->getImageDetail(),
                        'type' => 'image',
                    ],
                ];
            }
            if ($promotion->getCustomerId()) {
                $customerIds = $promotion->getData('customer_id');
                if ($customerIds) {
                    $customerIds = explode(',', $customerIds);
                    $customerModel = $this->customerFactory->create();
                    foreach ($customerIds as $id) {
                        $customer = $customerModel->load($id);
                        if ($customer->getId()) {
                            $this->loadedData[$promotion->getId()]['customer_for_promotion'][] = [
                                'entity_id' => $id,
                                'name' => $customer->getData('firstname') . $customer->getData('middlename') . $customer->getData('lastname'),
                                'email' => $customer->getData('email'),
                                'dob' => $customer->getData('dob'),
                                'gender' => $customer->getData('gender'),
                            ];
                        }
                    }
                }
            }
        }
        $data = $this->dataPersistor->get('smart_promotions_table');
        if (!empty($data)) {
            $promotion = $this->collection->getNewEmptyItem();
            $promotion->setData($data);
            $this->loadedData[$promotion->getId()] = $promotion->getData();
            $this->dataPersistor->clear('smart_promotions_table');
        }
        return $this->loadedData;
    }

    /**
     * Get Media Url
     *
     * @return string
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getMediaUrl()
    {
        $mediaUrl = $this->storeManager->getStore()
                ->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA) . 'smart/survey/image/';
        return $mediaUrl;
    }
}
