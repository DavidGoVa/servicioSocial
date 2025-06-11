<?php

namespace Aragon\SIAS\Repository;

use Aragon\SIAS\Entity\Archivo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Archivo>
 *
 * @method Archivo|null find($id, $lockMode = null, $lockVersion = null)
 * @method Archivo|null findOneBy(array $criteria, array $orderBy = null)
 * @method Archivo[]    findAll()
 * @method Archivo[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArchivoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Archivo::class);
    }

     public function findArchivosByAlumno(int $alumnoId): array
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.usuarioAragon = :alumnoId')
            ->setParameter('alumnoId', $alumnoId)
            ->getQuery()
            ->getResult();
    }
}
