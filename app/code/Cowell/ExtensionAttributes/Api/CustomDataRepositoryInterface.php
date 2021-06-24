<?php

namespace Cowell\ExtensionAttributes\Api;

interface CustomDataRepositoryInterface
{
    public function save($customData);

//    public function getById($entityId);

    public function getByProductId($entityId);

    public function deleteOldByProductId($entityId);

}
