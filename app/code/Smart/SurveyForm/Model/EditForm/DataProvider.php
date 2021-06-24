<?php

namespace Smart\SurveyForm\Model\EditForm;

use Magento\Framework\App\ObjectManager;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\AuthorizationInterface;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Ui\DataProvider\Modifier\PoolInterface;
use Smart\SurveyForm\Model\ResourceModel\Form\CollectionFactory;

/**
 * Class DataProvider
 *
 * @package Smart\SurveyForm\Model\EditForm
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
     * DataProvider constructor.
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param CollectionFactory $pageCollectionFactory
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
        CollectionFactory $pageCollectionFactory,
        DataPersistorInterface $dataPersistor,
        StoreManagerInterface $storeManager,
        array $meta = [],
        array $data = [],
        PoolInterface $pool = null,
        ?AuthorizationInterface $auth = null
    ) {
        $this->collection = $pageCollectionFactory->create();
        $this->dataPersistor = $dataPersistor;
        $this->storeManager = $storeManager;
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data, $pool);
        $this->auth = $auth ?? ObjectManager::getInstance()->get(AuthorizationInterface::class);
        $this->meta = $this->prepareMeta($this->meta);
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
     * Get data
     *
     * @return array
     */
    public function getData()
    {
        if (isset($this->loadedData)) {
            return $this->loadedData;
        }
        $items = $this->collection->getItems();
        foreach ($items as $page) {
            $this->loadedData[$page->getId()]['general'] = $page->getData();
            if ($page->getThumbnailImage()) {
                $this->loadedData[$page->getId()]['general']['thumbnail_image'] = [
                    [
                        'name' => $page->getThumbnailImage(),
                        'url'  => $this->getMediaUrl() . $page->getThumbnailImage(),
                        'type' => 'image',
                    ],
                ];
            }
        }

        $data = $this->dataPersistor->get('smart_survey_form_list');
        if (!empty($data)) {
            $page = $this->collection->getNewEmptyItem();
            $page->setData($data);
            $this->loadedData[$page->getId()]['general'] = $page->getData();
            $this->dataPersistor->clear('smart_survey_form_list');
        }

        return $this->loadedData;
    }
    public function getMediaUrl()
    {
        $mediaUrl = $this->storeManager->getStore()
                ->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA).'smart/survey/image/';
        return $mediaUrl;
    }
}
