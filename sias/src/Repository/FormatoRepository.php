<?php

namespace Aragon\SIAS\Repository;

use Aragon\SIAS\Entity\Formato;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Formato>
 *
 * @method Formato|null find($id, $lockMode = null, $lockVersion = null)
 * @method Formato|null findOneBy(array $criteria, array $orderBy = null)
 * @method Formato[]    findAll()
 * @method Formato[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FormatoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Formato::class);
    }
}
