<?php

namespace Smart\Promotions\Model;

use Magento\Framework\DataObject\IdentityInterface;
use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Registry;

/**
 * Class PromotionsModel
 *
 * @package Smart\Promotions\Model
 */
class Promotions extends AbstractModel implements IdentityInterface
{
    const CACHE_TAG = 'smart_promotions';
    const ID = 'id';
    const ACTIVE = 'active';
    const TITLE = 'title';
    const IMAGE = 'image';
    const IMAGE_DETAIL = 'image_detail';
    const CONTENT = 'content';
    const START = 'start_date';
    const END = 'end_date';

    /**
     * PromotionsModel constructor.
     * @param \Magento\Framework\Model\Context $context
     * @param Registry $registry
     * @param \Magento\Framework\Model\ResourceModel\AbstractResource|null $resource
     * @param \Magento\Framework\Data\Collection\AbstractDb|null $resourceCollection
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\Model\Context $context,
        Registry $registry,
        \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,
        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,
        array $data = []
    ) {
        parent::__construct(
            $context,
            $registry,
            $resource,
            $resourceCollection,
            $data
        );
    }

    /**
     * @inheritDoc
     */
    protected function _construct()
    {
        $this->_init(\Smart\Promotions\Model\ResourceModel\Promotions::class);
    }

    /**
     * Retrieve identities
     *
     * @return array
     */
    public function getIdentities()
    {
        $identities = [self::CACHE_TAG . '_' . $this->getId()];
        $identities[] = self::CACHE_TAG;

        return $identities;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->getData(self::ID);
    }

    /**
     * @param int $id
     * @return AbstractModel|Promotions
     */
    public function setId($id)
    {
        return $this->setData(self::ID, $id);
    }

    /**
     * @return mixed
     */
    public function getActive()
    {
        return $this->getData(self::ACTIVE);
    }

    /**
     * @param int $id
     * @return Promotions
     */
    public function setActive($id)
    {
        return $this->setData(self::ACTIVE, $id);
    }

    /**
     * @return mixed
     */
    public function getImage()
    {
        return $this->getData(self::IMAGE);
    }

    /**
     * @param int $id
     * @return Promotions
     */
    public function setImage($id)
    {
        return $this->setData(self::IMAGE, $id);
    }

    /**
     * @return mixed
     */
    public function getImageDetail()
    {
        return $this->getData(self::IMAGE_DETAIL);
    }

    /**
     * @param int $id
     * @return Promotions
     */
    public function setImageDetail($id)
    {
        return $this->setData(self::IMAGE_DETAIL, $id);
    }

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->getData(self::TITLE);
    }

    /**
     * @param int $id
     * @return Promotions
     */
    public function setTitle($id)
    {
        return $this->setData(self::TITLE, $id);
    }

    /**
     * @return mixed
     */
    public function getStartDate()
    {
        return $this->getData(self::START);
    }

    /**
     * @param int $id
     * @return Promotions
     */
    public function setStartDate($id)
    {
        return $this->setData(self::START, $id);
    }

    /**
     * @return mixed
     */
    public function getEndDate()
    {
        return $this->getData(self::END);
    }

    /**
     * @param int $id
     * @return Promotions
     */
    public function setEndDate($id)
    {
        return $this->setData(self::END, $id);
    }
    /**
     * @return mixed
     */
    public function getContent()
    {
        return $this->getData(self::CONTENT);
    }

    /**
     * @param int $id
     * @return Promotions
     */
    public function setContent($id)
    {
        return $this->setData(self::CONTENT, $id);
    }
}
