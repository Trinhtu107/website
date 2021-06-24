<?php

namespace Smart\SurveyForm\Model;

use Magento\Framework\DataObject\IdentityInterface;
use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Registry;

/**
 * Class FormModel
 *
 * @package Smart\SurveyForm\Model
 */
class FormModel extends AbstractModel implements IdentityInterface
{
    const CACHE_TAG = 'smart_form';
    const ID = 'id';
    const ACTIVE = 'active';
    const TITLE = 'title';
    const THUMBNAIL_IMAGE = 'thumbnail_image';
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';
    const START_DATE = 'start_date';
    const END_DATE = 'end_date';
    const DESCRIPTION = 'description';
    const TH_POINT = 'th_point';

    /**
     * Form constructor.
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
        $this->_init(\Smart\SurveyForm\Model\ResourceModel\Form\FormModel::class);
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
     * @param int $formId
     * @return AbstractModel|FormModel
     */
    public function setId($formId)
    {
        return $this->setData(self::ID, $formId);
    }

    /**
     * @return mixed
     */
    public function getActive()
    {
        return $this->getData(self::ACTIVE);
    }

    /**
     * @param int $formId
     * @return FormModel
     */
    public function setActive($formId)
    {
        return $this->setData(self::ACTIVE, $formId);
    }

    /**
     * @return mixed
     */
    public function getThumbnailImage()
    {
        return $this->getData(self::THUMBNAIL_IMAGE);
    }

    /**
     * @param int $formId
     * @return FormModel
     */
    public function setThumbnailImage($formId)
    {
        return $this->setData(self::THUMBNAIL_IMAGE, $formId);
    }

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->getData(self::TITLE);
    }

    /**
     * @param int $formId
     * @return FormModel
     */
    public function setTitle($formId)
    {
        return $this->setData(self::TITLE, $formId);
    }

    /**
     * @return mixed
     */
    public function getCreatedAt()
    {
        return $this->getData(self::CREATED_AT);
    }

    /**
     * @param int $formId
     * @return FormModel
     */
    public function setCreatedAt($formId)
    {
        return $this->setData(self::CREATED_AT, $formId);
    }

    /**
     * @return mixed
     */
    public function getUpdatedAt()
    {
        return $this->getData(self::UPDATED_AT);
    }

    /**
     * @param int $formId
     * @return FormModel
     */
    public function setUpdatedAt($formId)
    {
        return $this->setData(self::UPDATED_AT, $formId);
    }

    /**
     * @return mixed
     */
    public function getStartDate()
    {
        return $this->getData(self::START_DATE);
    }

    /**
     * @param $date
     * @return FormModel
     */
    public function setStartDate($date)
    {
        return $this->setData(self::START_DATE, $date);
    }

    /**
     * @return mixed
     */
    public function getEndDate()
    {
        return $this->getData(self::END_DATE);
    }

    /**
     * @param $date
     * @return FormModel
     */
    public function setEndDate($date)
    {
        return $this->setData(self::END_DATE, $date);
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->getData(self::DESCRIPTION);
    }

    /**
     * @param $description
     * @return FormModel
     */
    public function setDescription($description)
    {
        return $this->setData(self::DESCRIPTION, $description);
    }

    /**
     * @return mixed
     */
    public function getThPoint()
    {
        return $this->getData(self::TH_POINT);
    }

    /**
     * @param $thPoint
     * @return FormModel
     */
    public function setThPoint($thPoint)
    {
        return $this->setData(self::TH_POINT, $thPoint);
    }
}
