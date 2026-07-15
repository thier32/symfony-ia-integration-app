<?php
namespace App\Repository;

use App\Entity\BaseEntity;
use App\Entity\Book;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Book>
 */
class BaseRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, $this->getClass());
    }

    public function getClass() : string
    {
        return "";
    }

    /**
     * Sauvegarde ou met à jour une entité.
     */
    public function save(BaseEntity $entity, bool $flush = true): BaseEntity
    {
        $this->getEntityManager()->persist($entity);
        if ($flush) {
            $this->getEntityManager()->flush();
        }

        return $entity;
    }
}
