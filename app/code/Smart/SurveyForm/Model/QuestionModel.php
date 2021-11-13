<?php

namespace Smart\SurveyForm\Model;

use Magento\Framework\DataObject\IdentityInterface;
use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Registry;

/**
 * Class QuestionModel
 *
 * @package Smart\SurveyForm\Model
 */
class QuestionModel extends AbstractModel implements IdentityInterface
{
    const CACHE_TAG = 'smart_question';
    const ID = 'id';
    const QUESTION = 'question';
    const ACTIVE = 'active';
    const TYPE_ID = 'type_id';
    const ANSWER = 'answer';
    const FORM_ID = 'form_id';


    /**
     * Question constructor.
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
        $this->_init(\Smart\SurveyForm\Model\ResourceModel\Question\QuestionModel::class);
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
     * @param mixed $questionId
     * @return AbstractModel|QuestionModel
     */
    public function setId($questionId)
    {
        return $this->setData(self::ID, $questionId);
    }

    /**
     * @return mixed
     */
    public function getFormId()
    {
        return $this->getData(self::FORM_ID);
    }

    /**
     * @param mixed $questionId
     * @return AbstractModel|QuestionModel
     */
    public function setFormId($questionId)
    {
        return $this->setData(self::FORM_ID, $questionId);
    }

    /**
     * @return mixed
     */
    public function getQuestion()
    {
        return $this->getData(self::QUESTION);
    }

    /**
     * @param $questionId
     * @return QuestionModel
     */
    public function setQuestion($questionId)
    {
        return $this->setData(self::QUESTION, $questionId);
    }

    /**
     * @return mixed
     */
    public function getActive()
    {
        return $this->getData(self::ACTIVE);
    }

    /**
     * @param $questionId
     * @return QuestionModel
     */
    public function setActive($questionId)
    {
        return $this->setData(self::ACTIVE, $questionId);
    }

    /**
     * @return mixed
     */
    public function getTypeId()
    {
        return $this->getData(self::TYPE_ID);
    }

    /**
     * @param $questionId
     * @return QuestionModel
     */
    public function setTypeId($questionId)
    {
        return $this->setData(self::TYPE_ID, $questionId);
    }

    /**
     * @return mixed
     */
    public function getAnswer()
    {
        return $this->getData(self::ANSWER);
    }

    /**
     * @param $answerId
     * @return QuestionModel
     */
    public function setAnswer($answerId)
    {
        return $this->setData(self::ANSWER, $answerId);
    }
}
