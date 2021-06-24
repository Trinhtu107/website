<?php

namespace Cowell\ExtensionAttributes\Model;

use Cowell\ExtensionAttributes\Model\ResourceModel\CustomData as ResourceModel;
use Magento\Framework\DataObject;
use Cowell\ExtensionAttributes\Api\Data\CustomDataInterface;

class CustomData extends \Magento\Catalog\Model\AbstractModel implements CustomDataInterface
{
    /**
     * Resource initialization
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(ResourceModel::class);
    }

    public function getProductId(){
        return $this->getData(self::PRODUCT_ID);
    }

    public function setProductId($productId){
        return $this->setData(self::PRODUCT_ID,$productId);
    }

    public function getCustomerId(){
        return $this->getData(self::CUSTOMER_ID);
    }

    public function setCustomerId($customerId){
        return $this->setData(self::CUSTOMER_ID,$customerId);
    }

//    public function getEndDate(){
//        return $this->getData(self::END_DATE);
//    }
//
//    public function setEndDate($endDate){
//        return $this->setData(self::END_DATE,$endDate);
//    }
//
//    public function getRulePrice(){
//        return $this->getData(self::RULE_PRICE);
//    }
//
//    public function setRulePrice($rulePrice){
//        return $this->setData(self::RULE_PRICE,$rulePrice);
//    }
//
//    public function getStartDate(){
//        return $this->getData(self::START_DATE);
//    }
//
//    public function setStartDate($startDate){
//        return $this->setData(self::START_DATE,$startDate);
//    }

}
