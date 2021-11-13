<?php

namespace Example\ExtensionAttributes\Observer;

use Magento\Catalog\Model\ProductFactory;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Example\ExtensionAttributes\Model\ExampleAttributesFactory;
use Example\ExtensionAttributes\Model\ResourceModel\ExampleAttributes\CollectionFactory;

use Zend\Log\Logger;
use Zend\Log\Writer\Stream;
use Psr\Log\LoggerInterface;

class AfterImportDataObserver implements ObserverInterface
{
    private $catalogProductFactory;

    private $productRepository;

    private $exampleAttributesFactory;
    /**
     * @var CollectionFactory
     */
    private $collectionFactory;

    public function __construct(
        ProductFactory $catalogProductFactory,
        ProductRepositoryInterface $productRepository,
        ExampleAttributesFactory $exampleAttributesFactory,
        CollectionFactory $collectionFactory
    ) {
        $this->catalogProductFactory = $catalogProductFactory;
        $this->productRepository = $productRepository;
        $this->exampleAttributesFactory = $exampleAttributesFactory;
        $this->collectionFactory = $collectionFactory;
    }

    public function execute(Observer $observer)
    {
        $writer = new Stream(BP . '/var/log/checkoutData.log');
        $logger = new Logger();
        $logger->addWriter($writer);

        $this->import = $observer->getEvent()->getAdapter();
        if ($products = $observer->getEvent()->getBunch()) {
            foreach ($products as $product) {
                try {
                    if(!empty($this->productRepository->get($product['sku']))){
                        $id = $this->productRepository->get($product['sku'])->getId();
                        $model = $this->exampleAttributesFactory->create();
                        $check = $this->collectionFactory->create()
                            ->addFieldToFilter('product_id',$id);
                        if ($check->getId()){
                            $model->load($check->getId());
                            $model->setDateStart($product['date_start']);
                            $model->setDateEnd($product['date_end']);
                            $model->save();
                        }else{
                            $model->setData('product_id',$id);
                            $model->setData('date_start',$product['date_start']);
                            $model->setData('date_end',$product['date_end']);
                            $model->save();
                        }
                    }

                } catch (\ErrorException $e){
                    $logger->info('fail '. $e->getMessage());
                }

            }
        }
    }
}
