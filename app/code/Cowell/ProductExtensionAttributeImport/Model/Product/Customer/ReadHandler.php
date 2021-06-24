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

/**
 * Add websites ids to product extension attributes.
 */
class ReadHandler implements ExtensionInterface
{
    /**
     * @var ProductCustomerLink
     */
    private $productCustomerLink;

    /**
     * ReadHandler constructor.
     * @param ProductCustomerLink $productCustomerLink
     */
    public function __construct(
        ProductCustomerLink $productCustomerLink
    ) {
        $this->productCustomerLink = $productCustomerLink;
    }

    /**
     * Add customer ids to product extension attributes, if no set.
     *
     * @param ProductInterface $product
     * @param array $arguments
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     * @return ProductInterface
     */
    public function execute($product, $arguments = [])
    {
        if ($product->getExtensionAttributes()->getCustomerIds() !== null) {
            return $product;
        }
        $customerIds = $this->productCustomerLink->getCustomerIdsByProductId($product->getId());

        $extensionAttributes = $product->getExtensionAttributes();
        $extensionAttributes->setCustomerIds($customerIds);
        $product->setExtensionAttributes($extensionAttributes);

        return $product;
    }
}
