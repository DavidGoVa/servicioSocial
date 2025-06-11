<?php

namespace Aragon\SIAS\Repository;

use Aragon\SIAS\Entity\TipoPrograma;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<TipoPrograma>
 *
 * @method TipoPrograma|null find($id, $lockMode = null, $lockVersion = null)
 * @method TipoPrograma|null findOneBy(array $criteria, array $orderBy = null)
 * @method TipoPrograma[]    findAll()
 * @method TipoPrograma[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TipoProgramaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TipoPrograma::class);
    }
}
