<?php

namespace Example\ExtensionAttributes\Model;

use Example\ExtensionAttributes\Model\ResourceModel\CustomData as ResourceModel;
use Example\ExtensionAttributes\Model\CustomDataFactory as ModelFactory;
use Example\ExtensionAttributes\Model\ResourceModel\CustomData\CollectionFactory;
use Example\ExtensionAttributes\Api\CustomDataRepositoryInterface;

class CustomDataRepository implements CustomDataRepositoryInterface
{
    protected $modelFactory;

    protected $resourceModel;

    protected $collectionFactory;

    public function __construct(
        ModelFactory $modelFactory,
        ResourceModel $resourceModel,
        CollectionFactory $collectionFactory
    )
    {
        $this->modelFactory = $modelFactory;
        $this->resourceModel = $resourceModel;
        $this->collectionFactory = $collectionFactory;
    }
    public function getByProductId($entityId)
    {
        $products = $this->collectionFactory->create()
            ->addFieldToFilter('product_id',$entityId);
        $data = [];
        if (!empty($products)){
            foreach ($products->getData() as $item){
                unset($item['id']);
                $data[] = $item;
            }
        }
        return $data;
    }

    public function save($customData)
    {
        if (!empty($customData)){
            foreach ($customData as $data){
                $this->modelFactory->create()->setData($data)->save();
            }
        }
        return $this;
    }
    public function deleteOldByProductId($entityId)
    {
        $products = $this->collectionFactory->create()
            ->addFieldToFilter('product_id',$entityId);
        if (!empty($products)){
            foreach ($products as $item){
                $this->modelFactory->create()->load($item->getId())->delete();
            }
        }
        return $this;
    }
//    public function getById($entityId)
//    {
//        // TODO: Implement getById() method.
//    }
}
