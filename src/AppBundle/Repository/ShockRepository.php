<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Operation;
use AppBundle\Entity\Shock;
use Doctrine\ORM\EntityRepository;

/**
 * \class ShockRepository
 * @ingroup Acoustique
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ShockRepository extends EntityRepository
{
    /**
     * @param Operation $operation
     * @param $idOfSheet
     * @return mixed
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findOneByIdOfSheetAndOperation(Operation $operation, $idOfSheet){
        return $this->getEntityManager()
            ->getRepository(Shock::class)
            ->createQueryBuilder('a')
            ->where('a.idOfSheet = :idOfSheet')
            ->andWhere('a.operation = :operation')
            ->setParameters(
                [
                    'idOfSheet' => $idOfSheet,
                    'operation' => $operation,
                ]
            )
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * @param Operation $operation
     * @return mixed
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findOneByOperation(Operation $operation){
        return $this->getEntityManager()
            ->getRepository(Shock::class)
            ->createQueryBuilder('a')
            ->where('a.operation = :operation')
            ->setParameter('operation', $operation)
            ->getQuery()
            ->getOneOrNullResult();
    }
    /**
     * @param Operation $operation
     * @return mixed
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findAllByOperation(Operation $operation){
        return $this->getEntityManager()
            ->getRepository(Shock::class)
            ->createQueryBuilder('a')
            ->where('a.operation = :operation')
            ->setParameter('operation', $operation)
            ->getQuery()
            ->getResult();
    }
}
