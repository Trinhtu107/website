<?php

namespace Smart\Promotions\Controller\Adminhtml\Promotions;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\Result\Redirect;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Registry;
use Magento\Framework\UrlInterface;
use Smart\Promotions\Model\PromotionsFactory;
use Smart\Promotions\Model\ResourceModel\Promotions\CollectionFactory;

/**
 * Class Save
 *
 * @package Smart\Promotions\Controller\Adminhtml\Promotions
 */
class Save extends Action implements HttpPostActionInterface
{
    /**
     * @var DataPersistorInterface
     */
    private $dataPersistor;

    /**
     * @var PromotionsFactory
     */
    protected $promoFactory;

    /**
     * @var CollectionFactory
     */
    protected $collectionFactory;

    /**
     * Save constructor.
     * @param Context $context
     * @param Registry $registry
     * @param DataPersistorInterface $dataPersistor
     * @param CollectionFactory $collectionFactory
     * @param UrlInterface $url
     * @param PromotionsFactory $promoFactory
     */
    public function __construct(
        Context $context,
        Registry $registry,
        DataPersistorInterface $dataPersistor,
        CollectionFactory $collectionFactory,
        UrlInterface $url,
        PromotionsFactory $promoFactory
    ) {
        $this->collectionFactory = $collectionFactory;
        $this->url = $url;
        $this->promoFactory = $promoFactory;
        $this->dataPersistor = $dataPersistor;
        $this->registry = $registry;
        parent::__construct($context);
    }

    /**
     * Execute Function
     *
     * @return ResponseInterface|Redirect|ResultInterface
     * @throws \Exception
     */
    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();
        $data = $this->prepareData($this->getRequest()->getParams());
        if ($data) {
            $model = $this->promoFactory->create();
            $id = $this->getRequest()->getParam('id');
            $idCustomer = [];
            if(isset($data['customer_for_promotion'])){
                foreach ($data['customer_for_promotion'] as $customer){
                    $idCustomer[] = $customer['entity_id'];
                }
                $data['customer_id'] = implode(",",$idCustomer);
            }
            $data['customer_group_ids'] = implode(",", $data['customer_group_ids']);

            if (!$id) {
                $model->setData($data);
                try {
                    $model->save();
                    $this->messageManager->addSuccess(__('Insert Record Successfully !'));
                    $this->dataPersistor->clear('smart_promotions_table');
                } catch (\Exception $e) {
                    $this->messageManager->addError($e->getMessage());
                }
            } else {
                if ($this->getRequest()->getParam('back')) {
                    return $resultRedirect->setPath(
                        '*/*/edit',
                        ['id' => $model->getId()]
                    );
                }
                $dataNew = $model->load($id);
                $dataNew->setData($data);
                try {
                    $dataNew->save();
                    $this->messageManager->addSuccess(__('Update success!'));
                    $this->dataPersistor->clear('smart_promotions_table');
                } catch (\Exception $e) {
                    $this->messageManager->addError($e->getMessage());
                }
            }
        }
        return $resultRedirect->setPath('*/*/');
    }

    /**
     * @param $data
     * @return mixed
     */
    protected function prepareData($data)
    {
        $data['image'] = $data["image"][0]['name'] ?? null;
        $data['imageDetail'] = $data["imageDetail"][0]['name'] ?? null;
        $data['customer_id'] = $data['customer_id'] ?? null;
        $data['budget'] = (int)$data['budget'] ?? null;
        return $data;
    }
}
