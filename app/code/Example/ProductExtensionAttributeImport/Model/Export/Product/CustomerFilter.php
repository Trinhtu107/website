<?php

namespace Example\ProductExtensionAttributeImport\Model\Export\Product;

use Magento\Catalog\Model\ResourceModel\Product\Collection;
use Magento\CatalogImportExport\Model\Export\ProductFilterInterface;
use Magento\Framework\App\ResourceConnection;

class CustomerFilter implements ProductFilterInterface
{
    const NAME = 'customer_ids';

    /**
     * @var ResourceConnection
     */
    private $resourceConnection;

    /**
     * CustomerFilter constructor.
     *
     * @param ResourceConnection $resourceConnection
     */
    public function __construct(ResourceConnection $resourceConnection)
    {
        $this->resourceConnection = $resourceConnection;
    }

    /**
     * @inheritDoc
     */
    public function filter(Collection $collection, array $filters): Collection
    {
        $value = trim($filters[self::NAME] ?? '');
        if ($value) {
            return $this->addCustomersFilter($collection, explode(',', $value));
        }

        return $collection;
    }

    /**
     * Filter Product by Customers
     *
     * @param Collection $collection
     * @param array $customersFilter
     * @return Collection
     */
    private function addCustomersFilter(Collection $collection, array $customersFilter)
    {
        $connection = $this->resourceConnection->getConnection();
        $customerSelect = $connection->select()->from(
            ['cus' => $connection->getTableName('catalog_product_customer')],
            'cus.product_id'
        )->where($connection->prepareSqlCondition('cus.customer_id', ['in' => $customersFilter]));
        $selectCondition = [
            'in' => $customerSelect
        ];
        $collection->getSelect()->where($connection->prepareSqlCondition('e.entity_id', $selectCondition));

        return $collection;
    }
}
