<?php

namespace Smart\SurveyForm\Model;

use Magento\Framework\DataObject\IdentityInterface;
use Magento\Framework\Model\AbstractModel;

/**
 * Class AnswerModel
 *
 * @package Smart\SurveyForm\Model
 */
class AnswerModel extends AbstractModel implements IdentityInterface
{
    const CACHE_TAG = 'smart_answer';

    /**
     * @inheritDoc
     */
    public function _construct()
    {
        $this->_init(\Smart\SurveyForm\Model\ResourceModel\Answer\AnswerModel::class);
    }

    /**
     * @return array|string[]
     */
    public function getIdentities()
    {
        return [self::CACHE_TAG];
    }
}
