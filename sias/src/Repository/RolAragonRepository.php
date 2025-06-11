<?php

namespace Aragon\SIAS\Repository;

use Aragon\SIAS\Entity\RolAragon;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<RolAragon>
 *
 * @method RolAragon|null find($id, $lockMode = null, $lockVersion = null)
 * @method RolAragon|null findOneBy(array $criteria, array $orderBy = null)
 * @method RolAragon[]    findAll()
 * @method RolAragon[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RolAragonRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RolAragon::class);
    }
}
