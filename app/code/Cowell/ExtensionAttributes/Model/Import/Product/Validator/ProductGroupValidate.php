<?php


namespace Cowell\ExtensionAttributes\Model\Import\Product\Validator;


use Magento\CatalogImportExport\Model\Import\Product\RowValidatorInterface;
use Magento\CatalogImportExport\Model\Import\Product\Validator\AbstractImportValidator;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\Url\Validator;

class ProductGroupValidate extends AbstractImportValidator implements RowValidatorInterface
{
    private $validator;

    public function __construct(Validator $validator = null)
    {
        $this->validator = $validator ?: ObjectManager::getInstance()->get(Validator::class);
    }

    public function isValid($value)
    {
        $this->_clearMessages();
        if (!empty($value['date_start'])) {
            $productGroup = strtolower($value['date_start']);
            if (!is_numeric($productGroup)) {
                $this->_addMessages(['start expected not null']);
                return false;
            }
        }
        if (!empty($value['date_end'])) {
            $productGroup = strtolower($value['date_end']);
            if (!is_numeric($productGroup)) {
                $this->_addMessages(['end expected not null']);
                return false;
            }
        }

        return true;
    }
}
