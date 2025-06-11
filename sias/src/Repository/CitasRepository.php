<?php

namespace Aragon\SIAS\Repository;

use Aragon\SIAS\Entity\Citas;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Citas>
 *
 * @method Citas|null find($id, $lockMode = null, $lockVersion = null)
 * @method Citas|null findOneBy(array $criteria, array $orderBy = null)
 * @method Citas[]    findAll()
 * @method Citas[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CitasRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Citas::class);
    }
}
