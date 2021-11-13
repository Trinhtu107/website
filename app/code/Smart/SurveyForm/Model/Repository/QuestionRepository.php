<?php

namespace Smart\SurveyForm\Model\Repository;

use Magento\Framework\Exception\InputException;
use Magento\Framework\Filter\FilterManager;
use Smart\SurveyForm\Api\Repository\QuestionRepositoryInterface;
use Smart\SurveyForm\Api\Repository\QuestionRepositoryInterfaceFactory;
use Smart\SurveyForm\Model\ResourceModel\Question\CollectionFactory;

/**
 * Class QuestionRepository
 *
 * @package Smart\SurveyForm\Model\Repository
 */
class QuestionRepository implements QuestionRepositoryInterface
{
    /**
     * @var QuestionRepositoryInterfaceFactory
     */
    private $factory;

    /**
     * @var CollectionFactory
     */
    private $collectionFactory;

    /**
     * @var FilterManager
     */
    private $filterManager;

    /**
     * QuestionRepository constructor.
     * @param QuestionRepositoryInterfaceFactory $factory
     * @param CollectionFactory $collectionFactory
     * @param FilterManager $filterManager
     */
    public function __construct(
        QuestionRepositoryInterfaceFactory $factory,
        CollectionFactory $collectionFactory,
        FilterManager $filterManager
    ) {
        $this->factory           = $factory;
        $this->collectionFactory = $collectionFactory;
        $this->filterManager     = $filterManager;
    }

    /**
     * {@inheritdoc}
     */
    public function getCollection()
    {
        return $this->collectionFactory->create();
    }


    /**
     * @inheritdoc
     */
    public function getList()
    {
        /** @var \Smart\Blog\Model\ResourceModel\Post\Collection $collection */
        $collection = $this->getCollection();

        return $collection->getItems();
    }

    /**
     * {@inheritdoc}
     */
    public function create()
    {
        return $this->factory->create();
    }

    /**
     * {@inheritdoc}
     */
    public function get($id)
    {
        $post = $this->create();

        $post->getResource()->load($post, $id);

        if ($post->getId()) {
            return $post;
        }

        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function update($id, $post)
    {
        $model = $this->create();
        $model->getResource()->load($model, $id);

        if (!$model->getId()) {
            throw new InputException(__("The question doesn't exist."));
        }

        $json = json_decode(file_get_contents("php://input"));

        foreach ($json->post as $k => $v) {
            $model->setData($k, $post->getData($k));
        }

        $model->getResource()->save($model);

        return $model;
    }

    /**
     * {@inheritdoc}
     */
    public function delete($model)
    {
        return $model->getResource()->delete($model);
    }

    /**
     * {@inheritdoc}
     */
    public function save($model)
    {
        $model->getResource()->save($model);

        return $model;
    }
}
