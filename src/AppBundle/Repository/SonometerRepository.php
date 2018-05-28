<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Operation;
use AppBundle\Entity\Sonometer;
use Doctrine\ORM\EntityRepository;

/**
 * Class SonometerRepository
 * @package AppBundle\Repository
 */
class SonometerRepository extends EntityRepository
{
    /**
     * @param Operation $operation
     * @return mixed
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findFirstByOperationAgency(Operation $operation){
        return $this->getEntityManager()
            ->getRepository(Sonometer::class)
            ->createQueryBuilder('s')
            ->where('s.agency = :operationAgency')
            ->setMaxResults(1)
            ->setParameters(
                [
                    'operationAgency' => $operation->getAgency(),
                ]
            )
            ->getQuery()
            ->getResult();
    }

}
