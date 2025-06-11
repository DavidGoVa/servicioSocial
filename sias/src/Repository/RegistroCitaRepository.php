<?php

namespace Aragon\SIAS\Repository;

use Aragon\SIAS\Entity\RegistroCita;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<RegistroCita>
 *
 * @method Citas|null find($id, $lockMode = null, $lockVersion = null)
 * @method Citas|null findOneBy(array $criteria, array $orderBy = null)
 * @method Citas[]    findAll()
 * @method Citas[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RegistroCitaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RegistroCita::class);
    }

   public function findPorConfirmar(string $estado, int $periodoId): array
{
    try {
        return $this->createQueryBuilder('r')
            ->join('r.citas', 'c')
            ->where('r.estado = :estado')
            ->andWhere('c.periodo = :periodoId')
            ->setParameter('estado', $estado)
            ->setParameter('periodoId', $periodoId)
            ->orderBy('c.fechaAtencion', 'ASC')
            ->getQuery()
            ->getResult();
    } catch (\Throwable $e) {
        throw new \RuntimeException('Error en la consulta findPorConfirmar: ' . $e->getMessage(), 0, $e);
    }
}





public function findCitasByAlumno(int $alumnoId): array
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.usuarioAragon = :alumnoId')
            ->setParameter('alumnoId', $alumnoId)
            ->orderBy('c.createdAt', 'ASC')
            ->getQuery()
            ->getResult();
    }
}
