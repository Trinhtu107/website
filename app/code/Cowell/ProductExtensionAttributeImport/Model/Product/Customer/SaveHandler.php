<?php
/**
 * Cowell Commerce .
 *
 * @category   Cowell
 * @package    Cowell_ProductExtensionAttributeImport
 * @author     Extension Team
 * @copyright  Copyright (c) 2020-2021 Cowell Commerce.
 */

namespace Cowell\ProductExtensionAttributeImport\Model\Product\Customer;

use Magento\Catalog\Api\Data\ProductInterface;
use Cowell\ProductExtensionAttributeImport\Model\ResourceModel\Product\Customer\Link as ProductCustomerLink;
use Magento\Framework\EntityManager\Operation\ExtensionInterface;

class SaveHandler implements ExtensionInterface
{
    /**
     * @var \Magento\Catalog\Model\ResourceModel\Product\Website\Link
     */
    private $productCustomerLink;

    /**
     * SaveHandler constructor.
     * @param ProductCustomerLink $productCustomerLink
     */
    public function __construct(
        ProductCustomerLink $productCustomerLink
    ) {
        $this->productCustomerLink = $productCustomerLink;
    }

    /**
     * Get customer ids from extension attributes and persist them
     * @param ProductInterface $product
     * @param array $arguments
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     * @return ProductInterface
     */
    public function execute($product, $arguments = [])
    {
        $customerIds = [1];
        $extensionAttributes = $product->getExtensionAttributes();
        $customerIds = $extensionAttributes->getWebsiteIds();
        if ($customerIds !== null) {
            $this->productCustomerLink->saveCustomerIds($product->getId(), $customerIds);
        }

        return $product;
    }
}
