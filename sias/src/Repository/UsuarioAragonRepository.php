<?php

namespace Aragon\SIAS\Repository;

use Aragon\SIAS\Entity\UsuarioAragon;
use Aragon\SIAS\Entity\RolAragon;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<UsuarioAragon>
 *
 * @method UsuarioAragon|null find($id, $lockMode = null, $lockVersion = null)
 * @method UsuarioAragon|null findOneBy(array $criteria, array $orderBy = null)
 * @method UsuarioAragon[]    findAll()
 * @method UsuarioAragon[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UsuarioAragonRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UsuarioAragon::class);
    }

    public function findPsicologos(): array
    {
        return $this->createQueryBuilder('u')
            ->innerJoin('u.roles', 'r')
            ->where('r.id IN (:roles)')
            ->setParameter('roles', [RolAragon::ROLE_PSICOLOGO, RolAragon::ROLE_PSICOLOGO_TEMPORAL])
            ->getQuery()
            ->getResult();
    }
}
