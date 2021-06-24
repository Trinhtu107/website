<?php

namespace Smart\SurveyForm\Model\EditQuestion;

use Magento\Framework\App\ObjectManager;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\AuthorizationInterface;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Ui\DataProvider\Modifier\PoolInterface;
use Smart\SurveyForm\Model\ResourceModel\Question\CollectionFactory;

/**
 * Class DataProvider
 *
 * @package Smart\SurveyForm\Model\EditQuestion
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
    /*
     * @var ContextInterface
     */
    private $context;

    /**
     * DataProvider constructor.
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param CollectionFactory $pageCollectionFactory
     * @param DataPersistorInterface $dataPersistor
     * @param StoreManagerInterface $storeManager
     * @param ContextInterface $context
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
        ContextInterface $context,
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
        $this->context = $context;
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
    public function getData(): array
    {
        if (isset($this->loadedData)) {
            return $this->loadedData;
        }
        $items = $this->collection->getItems();
        foreach ($items as $page) {
            $this->loadedData[$page->getId()] = $page->getData();
            $this->loadedData[$page->getId()]['show_elm_select'] = false;
            $this->loadedData[$page->getId()]['show_elm_multi'] = false;
            if ($page->getAnswer()) {
                $arr = explode(" || ", $page->getAnswer());
                if ($page->getTypeId() == "2") {
                    $this->loadedData[$page->getId()]['show_elm_select'] = true;
                    for ($i = 0; $i < count($arr); $i++) {
                        $this->loadedData[$page->getId()]['data']['type_selected'][$i]['record'] = $i;
                        $this->loadedData[$page->getId()]['data']['type_selected'][$i]['answer_select'] = $arr[$i];
                    }
                }
                if ($page->getTypeId() == "3") {
                    $this->loadedData[$page->getId()]['show_elm_multi'] = true;
                    for ($i = 0; $i < count($arr); $i++) {
                        $this->loadedData[$page->getId()]['data']['type_multiselect'][$i]['record'] = $i;
                        $this->loadedData[$page->getId()]['data']['type_multiselect'][$i]['answer_multi'] = $arr[$i];
                    }
                }

            }
        }

        if (null === $this->loadedData) {
            $this->loadedData[''] = $this->getDefaultData();
        }

        $data = $this->dataPersistor->get('smart_survey_question');
        if (!empty($data)) {
            $page = $this->collection->getNewEmptyItem();
            $page->setData($data);
            $this->loadedData[$page->getId()] = $page->getData();
            $this->dataPersistor->clear('smart_survey_question');
        }

        return $this->loadedData;
    }

    /**
     * Get data default
     *
     * @return array
     */
    private function getDefaultData(): array
    {
        $formId = $this->context->getRequestParam('form_id');
        $data = [
            'form_id' => $formId,
            'show_elm_select' => false,
            'show_elm_multi' => false
        ];

        return $data;
    }
}
