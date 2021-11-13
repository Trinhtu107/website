<?php
/**
 * Example Commerce .
 *
 * @category   Example
 * @package    Example_ProductExtensionAttributeImport
 * @author     Extension Team
 * @copyright  Copyright (c) 2020-2021 Example Commerce.
 */

namespace Example\ProductExtensionAttributeImport\Model\Import\Product\Validator;

use Magento\CatalogImportExport\Model\Import\Product\RowValidatorInterface;
use Magento\CatalogImportExport\Model\Import\Product\Validator\AbstractImportValidator;
use Magento\Framework\App\ResourceConnection;

class Customer extends AbstractImportValidator implements RowValidatorInterface
{
    const COL_PRODUCT_CUSTOMERS = 'customer_ids';
    const SEPARATOR = ',';
    const ERROR_INVALID_CUSTOMER = 'Invalid value in customer_ids column (customer_id does not exist)';

    /**
     * @var ResourceConnection
     */
    private $resourceConnection;

    /**
     * @var array||null
     */
    private $currentCustomerIds = null;

    /**
     * Customer constructor.
     *
     * @param ResourceConnection $resourceConnection
     */
    public function __construct(ResourceConnection $resourceConnection)
    {
        $this->resourceConnection = $resourceConnection;
    }

    /**
     * {@inheritdoc}
     */
    public function isValid($value)
    {
        $this->_clearMessages();

        if (empty($value[self::COL_PRODUCT_CUSTOMERS])) {
            return true;
        }

        $customerIds = explode(self::SEPARATOR, $value[self::COL_PRODUCT_CUSTOMERS]);

        foreach ($customerIds as $customerId) {
            if (!in_array($customerId, $this->getCurrentCustomerIds())) {
                $this->_addMessages([self::ERROR_INVALID_CUSTOMER]);
                return false;
            }
        }
        return true;
    }

    /**
     * Get curent customer ids
     *
     * @return array
     */
    public function getCurrentCustomerIds()
    {
        if ($this->currentCustomerIds === null) {
            $connection = $this->resourceConnection->getConnection();
            $customerEntityTable = $connection->getTableName('customer_entity');
            $select = $connection->select()->from(
                $customerEntityTable,
                'entity_id'
            );
            $this->currentCustomerIds = $connection->fetchCol($select);
        }

        return $this->currentCustomerIds;
    }
}
