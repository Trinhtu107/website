<?php

namespace Smart\Promotions\Controller\Index;

use Magento\Customer\Model\Session;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\Result\Redirect;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\UrlInterface;
use Magento\Framework\View\Result\Page;
use Magento\Framework\View\Result\PageFactory;

class Index extends Action
{
    const PROMO_PATH = "sm_promo/index/";
    /**
     * @var Session
     */
    protected $session;
    /**
     * @var PageFactory
     */
    protected $resultPageFactory;
    /**
     * @var UrlInterface
     */
    protected $urlInterface;

    /**
     * Index constructor.
     * @param Session $session
     * @param PageFactory $resultPageFactory
     * @param UrlInterface $urlInterface
     * @param Context $context
     */
    public function __construct(
        Session $session,
        PageFactory $resultPageFactory,
        UrlInterface $urlInterface,
        Context $context
    ) {
        $this->urlInterface = $urlInterface;
        $this->session = $session;
        $this->resultPageFactory = $resultPageFactory;
        parent::__construct($context);
    }

    /**
     * @return ResponseInterface|Redirect|ResultInterface|Page
     */
    public function execute()
    {
        $customerSession = $this->session;
        if ($customerSession->isLoggedIn()) {
            return $this->resultPageFactory->create();
        }
        $url = $this->urlInterface->getUrl(self::PROMO_PATH);
        $login_url = $this->urlInterface
            ->getUrl(
                'customer/account/login',
                ['referer' => base64_encode($url)]
            );
        $resultRedirect = $this->resultRedirectFactory->create();
        $resultRedirect->setUrl($login_url);
        return $resultRedirect;
    }
}
