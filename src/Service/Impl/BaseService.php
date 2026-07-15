<?php
namespace App\Service\Impl;

use App\Dto\BaseDto;
use App\Entity\BaseEntity;
use App\Repository\BaseRepository;
use App\Service\IBaseService;
use App\Utils\Hydrator;
use App\Utils\Mapper;

abstract class BaseService implements IBaseService
{
   public function __construct(
       private BaseRepository $repository
   ){}

    public function addEntity(BaseEntity $entity): BaseEntity
    {
        $entity = $this->generateIdValue($entity);
        return $this->repository->save($entity);
    }

    public function addEntityDto(BaseDto $dto,String $dtoClassName,String $dtoResultClassName): BaseDto
    {
        $entity = Mapper::map($dto,$dtoClassName);
        $entity = $this->addEntity($entity);
        return Mapper::map($entity,$dtoResultClassName);
    }

    public function updateEntity(BaseEntity $entity): BaseEntity
    {
        $criteria = ["id" => $entity->getId()];

        $foundEntity = $this->getEntity($criteria);
        $foundEntity = Hydrator::hydrate($foundEntity, $entity);

        return $this->repository->save($foundEntity);
    }


    public function deleteEntity(BaseEntity $entity): BaseEntity
    {
        return new BaseEntity();
        // TODO: Implement deleteEntity() method.
    }

    public function getEntities(string $dtoClassName, ?array $criteria = []): array
    {
        $offset = 0;
        $pageSize = 10;
        if (empty($criteria)) {
            $offset = 0;
            $pageSize = 10;
            $criteria = [];
        }
        $entities = $this->repository->findBy($criteria,['id'=>'desc'],$pageSize,$offset);
        return $this->convertMap($entities,$dtoClassName);
    }

    public function convertMap(array $entities,string $dtoClassName): array
    {
        return array_map(function($entity) use ($dtoClassName) {
            return Mapper::map($entity, $dtoClassName);
        }, $entities);
    }

    public function getEntity(array $criteria = []): ?BaseEntity
    {
        $result = $this->repository->findOneBy($criteria);

        return $result;
    }



    public function generateIdValue(BaseEntity $entity): BaseEntity
    {
        $className = (new \ReflectionClass($entity))->getShortName();
        $prefix = strtolower($className);

        $setterMethod = sprintf("set%s%s", ucfirst($prefix), "Id");

        $uniquePart = hexdec(uniqid());

        // 3. On vérifie si le setter existe et on l'appelle dynamiquement
        if (method_exists($entity, $setterMethod)) {
            $entity->$setterMethod($uniquePart);
        } else {
            throw new \Exception(sprintf("La méthode %s() n'existe pas sur l'entité %s", $setterMethod, $className));
        }

        return $entity;
    }


}
