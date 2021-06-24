<?php

namespace Cowell\ExtensionAttributes\Api\Data;

interface CustomDataInterface extends \Magento\Framework\Api\ExtensibleDataInterface
{
    const PRODUCT_ID = 'product_id';
    const START_DATE = 'start_date';
    const END_DATE = 'end_date';
    const CUSTOMER_ID = 'customer_id';
    const RULE_PRICE = 'rule_price';
    /**
     * @return int
     */
    public function getProductId();

    /**
     * @param int $productId
     * @return mixed
     */
    public function setProductId($productId);

    /**
     * @return int
     */
    public function getStartDate();

    /**
     * @param int $startDate
     * @return $this
     */
    public function setStartDate($startDate);

    /**
     * @return int
     */
    public function getEndDate();

    /**
     * @param int $endDate
     * @return $this
     */
    public function setEndDate($endDate);

    /**
     * @return int
     */
    public function getCustomerId();

    /**
     * @param int $customerId
     * @return $this
     */
    public function setCustomerId($customerId);

    /**
     * @return double
     */
    public function getRulePrice();

    /**
     * @param double $rulePrice
     * @return $this
     */
    public function setRulePrice($rulePrice);

    /**
     * @return Cowell\ExtensionAttributes\Api\Data\CustomDataExtensionInterface|null
     */
    public function getExtensionAttributes();


    /**
     * @param Cowell\ExtensionAttributes\Api\Data\CustomDataExtensionInterface $extensionAttributes
     * @return self
     */
    public function setExtensionAttributes
    (
        Cowell\ExtensionAttributes\Api\Data\CustomDataExtensionInterface $extensionAttributes
    );
}
