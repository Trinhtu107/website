<?php

namespace Cowell\ExtensionAttributes\Controller\Demo;

use Magento\Catalog\Api\ProductRepositoryInterface;


class Index extends \Magento\Framework\App\Action\Action
{
    public $_productloader;
    /**
     * @var
     */
    private $logger;

    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $pageFactory,
        ProductRepositoryInterface $_productloader

    ) {
        $this->_productloader = $_productloader;
        $this->_pageFactory = $pageFactory;
        return parent::__construct($context);
    }

    public function execute()
    {
        $data = $this->_productloader->get('Demo1');
        var_dump($data->getExtensionAttributes()->getDateEnd());
        var_dump($data->getExtensionAttributes()->getDateStart());die();
    }
}
