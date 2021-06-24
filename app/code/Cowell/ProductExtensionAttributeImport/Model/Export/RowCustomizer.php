<?php
/**
 * Cowell Commerce .
 *
 * @category   Cowell
 * @package    Cowell_ProductExtensionAttributeImport
 * @author     Extension Team
 * @copyright  Copyright (c) 2020-2021 Cowell Commerce.
 */

namespace Cowell\ProductExtensionAttributeImport\Model\Export;

use Magento\CatalogImportExport\Model\Export\RowCustomizerInterface;

class RowCustomizer implements RowCustomizerInterface
{
    const PRODUCT_CUSTOMER_FIELD_NAME = 'customer_ids';
    const PRODUCT_CUSTOMER_TABLE_NAME = 'catalog_product_customer';

    /**
     * prouduct customers data
     *
     * @var array
     */
    private $rowCustomers = [];

    /**
     * Prepare data for export
     *
     * @param mixed $collection
     * @param int[] $productIds
     * @return mixed
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function prepareData($collection, $productIds)
    {
        $this->addCustomerToResult($collection, $productIds);

        return;
    }

    /**
     * Set headers columns
     *
     * @param array $columns
     * @return mixed
     */
    public function addHeaderColumns($columns)
    {
        $columns = array_merge(
            $columns,
            [
                self::PRODUCT_CUSTOMER_FIELD_NAME
            ]
        );
        return $columns;
    }

    /**
     * Add data for export
     *
     * @param array $dataRow
     * @param int $productId
     * @return mixed
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function addData($dataRow, $productId)
    {
        if (isset($this->rowCustomers[$productId])) {
            $dataRow[self::PRODUCT_CUSTOMER_FIELD_NAME] = $this->rowCustomers[$productId];
        }

        return $dataRow;
    }

    /**
     * Calculate the largest links block
     *
     * @param array $additionalRowsCount
     * @param int $productId
     * @return mixed
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function getAdditionalRowsCount($additionalRowsCount, $productId)
    {
        return $additionalRowsCount;
    }

    /**
     * Process adding product customer to result collection
     *
     * @param mixed $collection
     * @param int[] $productIds
     *
     * @return mixed
     */
    private function addCustomerToResult($collection, array $productIds)
    {
        $productCustomers = [];

        if (!empty($productIds)) {
            $select = $collection->getConnection()->select()->from(
                ['product_customer' => self::PRODUCT_CUSTOMER_TABLE_NAME]
            )->where(
                'product_customer.product_id IN (?)',
                $productIds
            );

            $data = $collection->getConnection()->fetchAll($select);
            foreach ($data as $row) {
                $productCustomers[$row['product_id']][] = $row['customer_id'];
            }
        }

        foreach ($productIds as $productId) {
            if (isset($productCustomers[$productId])) {
                $this->rowCustomers[$productId] = implode(',', $productCustomers[$productId]);
            }
        }
        return $this;
    }
}
