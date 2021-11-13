<?php
/**
 * Example Commerce .
 *
 * @category   Example
 * @package    Example_ProductExtensionAttributeImport
 * @author     Extension Team
 * @copyright  Copyright (c) 2020-2021 Example Commerce.
 */

namespace Example\ProductExtensionAttributeImport\Observer;

use Amazon\Login\Model\CustomerLink;
use Magento\CatalogImportExport\Model\Import\Product\SkuProcessor;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\UrlRewrite\Model\Exception\UrlAlreadyExistsException;
use Example\ProductExtensionAttributeImport\Model\ResourceModel\Product\Customer\Link;

class AfterImportDataObserver implements ObserverInterface
{
    const PRODUCT_CUSTOMER = 'customer_ids';

    /**
     * @var SkuProcessor
     */
    private $skuProcessor;

    /**
     * @var Link
     */
    private $customerLink;

    /**
     * AfterImportDataObserver constructor.
     *
     * @param SkuProcessor $skuProcessor
     * @param CustomerLink $customerLink
     */
    public function __construct(SkuProcessor  $skuProcessor, Link  $customerLink)
    {
        $this->skuProcessor = $skuProcessor;
        $this->customerLink = $customerLink;
    }

    /**
     * Action after data import. Save product customer and remove old if exist.
     *
     * @param Observer $observer
     * @return void
     * @throws LocalizedException
     * @throws UrlAlreadyExistsException
     */
    public function execute(Observer $observer)
    {
        if ($products = $observer->getEvent()->getBunch()) {
            foreach ($products as $product) {
                if ($this->skuProcessor->getNewSku($product['sku']) === null || !isset($product[self::PRODUCT_CUSTOMER])) {
                    continue;
                }
                $productId = $this->skuProcessor->getNewSku($product['sku'])['entity_id'];
                $this->customerLink->saveCustomerIds($productId, explode(',', $product[self::PRODUCT_CUSTOMER]));
            }
        }

        return $this;
    }
}
