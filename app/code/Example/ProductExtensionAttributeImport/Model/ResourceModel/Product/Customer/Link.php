<?php
/**
 * Example Commerce .
 *
 * @category   Example
 * @package    Example_ProductExtensionAttributeImport
 * @author     Extension Team
 * @copyright  Copyright (c) 2020-2021 Example Commerce.
 */

namespace Example\ProductExtensionAttributeImport\Model\ResourceModel\Product\Customer;

use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Framework\App\ResourceConnection;

class Link
{
    /**
     * @var ResourceConnection
     */
    private $resourceConnection;

    /**
     * Link constructor.
     * @param ResourceConnection $resourceConnection
     */
    public function __construct(
        ResourceConnection $resourceConnection
    ) {
        $this->resourceConnection = $resourceConnection;
    }

    /**
     * Retrieve associated with product customer ids
     *
     * @param int $productId
     * @return array
     */
    public function getCustomerIdsByProductId($productId)
    {
        $connection = $this->resourceConnection->getConnection();

        $select = $connection->select()->from(
            $this->getProductCustomerTable(),
            'customer_id'
        )->where(
            'product_id = ?',
            (int) $productId
        );

        return $connection->fetchCol($select);
    }

    /**
     * save product customer table
     *
     * @param int $productId
     * @param array $customerIds
     * @return bool
     */
    public function saveCustomerIds(int $productId, array $customerIds)
    {
        $connection = $this->resourceConnection->getConnection();

        $oldCustomerIds = $this->getCustomerIdsByProductId($productId);
        $insert = array_diff($customerIds, $oldCustomerIds);
        $delete = array_diff($oldCustomerIds, $customerIds);

        if (!empty($insert)) {
            $data = [];
            foreach ($insert as $customerId) {
                $data[] = ['product_id' => $productId, 'customer_id' => (int)$customerId];
            }
            $connection->insertMultiple($this->getProductCustomerTable(), $data);
        }

        if (!empty($delete)) {
            foreach ($delete as $customerId) {
                $condition = ['product_id = ?' => $productId, 'customer_id = ?' => (int)$customerId];
                $connection->delete($this->getProductCustomerTable(), $condition);
            }
        }
    }

    /**
     * Get Product customer table
     *
     * @return string
     */
    private function getProductCustomerTable()
    {
        return $this->resourceConnection->getTableName('catalog_product_customer');
    }
}
