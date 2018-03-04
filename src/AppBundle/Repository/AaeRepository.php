<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Aae;
use AppBundle\Entity\Operation;
use Doctrine\ORM\EntityRepository;

/**
 * AaeRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class AaeRepository extends EntityRepository
{
    /**
     * @param Operation $operation
     * @param $measureNumber
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findOneByMeasureNumberAndOperation(Operation $operation, $measureNumber){
        $this->getEntityManager()
            ->getRepository(Aae::class)
            ->createQueryBuilder('a')
            ->where('a.measureNumber = :measureNumber')
            ->andWhere('a.operation = :operation')
            ->setParameters(
                [
                    'measureNumber' => $measureNumber,
                    'operation' => $operation,
                ]
            )
            ->getQuery()
            ->getOneOrNullResult();
    }
}
