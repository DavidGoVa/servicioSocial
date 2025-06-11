<?php

namespace Aragon\SIAS\Repository;

use Aragon\SIAS\Entity\Carrera;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Carrera>
 *
 * @method Carrera|null find($id, $lockMode = null, $lockVersion = null)
 * @method Carrera|null findOneBy(array $criteria, array $orderBy = null)
 * @method Carrera[]    findAll()
 * @method Carrera[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CarreraRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Carrera::class);
    }
}
