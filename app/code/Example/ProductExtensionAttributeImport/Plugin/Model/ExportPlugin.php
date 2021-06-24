<?php
/**
 * Example Commerce .
 *
 * @category   Example
 * @package    Example_ProductExtensionAttributeImport
 * @author     Extension Team
 * @copyright  Copyright (c) 2020-2021 Example Commerce.
 */

namespace Example\ProductExtensionAttributeImport\Plugin\Model;

use Magento\ImportExport\Model\Export;
use Magento\Catalog\Model\ResourceModel\Eav\AttributeFactory;
use Magento\Framework\Data\Collection;
use Magento\Eav\Api\Data\AttributeInterface;

class ExportPlugin
{
    private $attributeFactory;

    /**
     * ExportPlugin constructor.
     *
     * @param AttributeFactory $attributeFactory
     */
    public function __construct(AttributeFactory $attributeFactory)
    {
        $this->attributeFactory = $attributeFactory;
    }

    /**
     * User plugin to add product_customer to filter attribute collection
     *
     * @param Export $subject
     * @param Collection $result
     * @param Collection $collection
     * @return Collection
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function afterFilterAttributeCollection(
        Export $subject,
        Collection $result,
        Collection $collection
    ) {
        if ($subject->getEntity() !== 'catalog_product') {
            return $result;
        }

        $result->clear();
        $eavAttribute = $this->attributeFactory->create();
        $eavData = [
            AttributeInterface::ATTRIBUTE_CODE => 'customer_ids',
            AttributeInterface::FRONTEND_LABEL => 'Customer Ids',
            AttributeInterface::FRONTEND_INPUT => 'text'
        ];
        $eavAttribute->setData($eavData);
        $result->addItem($eavAttribute);

        return $result;
    }
}
