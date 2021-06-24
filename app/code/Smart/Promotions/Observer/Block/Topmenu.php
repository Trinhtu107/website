<?php
/**
 * User: tutv
 * Date: 16/06/2021 16:42
 */

namespace Smart\Promotions\Observer\Block;

use Magento\Framework\Event\Observer as EventObserver;
use Magento\Framework\Data\Tree\Node;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\UrlInterface;

class Topmenu implements ObserverInterface
{
    const ROUTER = 'sm_promo';

    private $url;

    public function __construct(
        UrlInterface $url
    ) {
        $this->url = $url;
    }

    /**
     * @param EventObserver $observer
     * @return $this
     */
    public function execute(EventObserver $observer)
    {
        /** @var \Magento\Framework\Data\Tree\Node $menu */
        $menu = $observer->getMenu();
        $tree = $menu->getTree();
        $data = [
            'name'      => __('Promotions'),
            'id'        => 'promotions-id',
            'url'       => $this->url->getUrl(self::ROUTER),
            'is_active' => false
        ];
        $node = new Node($data, 'id', $tree, $menu);
        $menu->addChild($node);
        return $this;
    }
}
