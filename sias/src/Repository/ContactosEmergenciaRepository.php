<?php

namespace Aragon\SIAS\Repository;

use Aragon\SIAS\Entity\ContactosEmergencia;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ContactosEmergencia>
 *
 * @method ContactosEmergencia|null find($id, $lockMode = null, $lockVersion = null)
 * @method ContactosEmergencia|null findOneBy(array $criteria, array $orderBy = null)
 * @method ContactosEmergencia[]    findAll()
 * @method ContactosEmergencia[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ContactosEmergenciaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ContactosEmergencia::class);
    }

    public function findContactosByAlumno(int $alumnoId): array
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.usuarioAragon = :alumnoId')
            ->setParameter('alumnoId', $alumnoId)
            ->getQuery()
            ->getResult();
    }
}
