<?php

namespace Smart\SurveyForm\Api\Repository;

use Smart\SurveyForm\Model\ResourceModel\Form\Collection;

/**
 * Interface FormRepositoryInterface
 *
 * @package Smart\SurveyForm\Api\Repository
 */
interface FormRepositoryInterface
{
    /**
     * @return Collection
     */
    public function getCollection();

    /**
     * @return mixed
     */
    public function create();

    /**
     * @param $model
     * @return mixed
     */
    public function save($model);

    /**
     * @return mixed
     */
    public function getList();

    /**
     * @param $id
     * @return mixed
     */
    public function get($id);

    /**
     * @param $id
     * @param $post
     * @return mixed
     */
    public function update($id, $post);

    /**
     * Delete
     *
     * @param $model
     * @return mixed
     */
    public function delete($model);
}
