<?php

namespace App\Service;

use App\Dto\BaseDto;
use App\Entity\BaseEntity;

interface IBaseService
{
    /**
     * @param BaseEntity $entity
     * @return BaseEntity
     */
    function addEntity(BaseEntity $entity) : BaseEntity;

    /**
     * @param BaseEntity $entity
     * @return BaseEntity
     */
    function updateEntity(BaseEntity $entity) : BaseEntity;

    /**
     * @param BaseEntity $entity
     * @return BaseEntity
     */
    function deleteEntity(BaseEntity $entity) : BaseEntity;

    /**
     * @param array $criteria
     * @return BaseEntity|null
     */
    function getEntity(array $criteria = []) : ?BaseEntity;

    /**
     * @param array $entities
     * @param string $dtoClassName
     * @return array
     */
    public function convertMap(array $entities,string $dtoClassName): array;

    /**
     * @param string $dtoClassName
     * @param array|null $criteria
     * @return array
     */
    function getEntities(string $dtoClassName, ?array $criteria = []) : array;


    /**
     * @param BaseEntity $entity
     * @return BaseEntity
     */
    public function generateIdValue(BaseEntity $entity): BaseEntity;

    /**
     * @param BaseDto $dto
     * @param String $dtoClassName
     * @param String $dtoResultClassName
     * @return BaseDto
     */
    public function addEntityDto(BaseDto $dto,String $dtoClassName,String $dtoResultClassName): BaseDto;
}
