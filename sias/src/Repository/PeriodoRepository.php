<?php

namespace Aragon\SIAS\Repository;

use Aragon\SIAS\Entity\Periodo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Periodo>
 *
 * @method Periodo|null find($id, $lockMode = null, $lockVersion = null)
 * @method Periodo|null findOneBy(array $criteria, array $orderBy = null)
 * @method Periodo[]    findAll()
 * @method Periodo[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PeriodoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Periodo::class);
    }

    public function findTodosLosCiclos(): array
{
    return $this->createQueryBuilder('p')
        ->select('DISTINCT p.cicloEscolar')
        ->orderBy('p.cicloEscolar', 'DESC')
        ->getQuery()
        ->getSingleColumnResult();
}


    public function findUltimoPeriodoPorCiclo(): ?Periodo
{
    return $this->createQueryBuilder('p')
        ->orderBy('p.cicloEscolar', 'DESC')
        ->setMaxResults(1)
        ->getQuery()
        ->getOneOrNullResult();
}

    public function findPeriodoByCicloEscolar($ciclo): array
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.cicloEscolar = :ciclo')
            ->setParameter('ciclo', $ciclo)
            ->orderBy('p.fechaInicio', 'ASC')
            ->getQuery()
            ->getResult();
    }

}
