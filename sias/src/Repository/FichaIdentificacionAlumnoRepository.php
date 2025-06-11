<?php

namespace Aragon\SIAS\Repository;

use Aragon\SIAS\Entity\FichaIdentificacionAlumno;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<FichaIdentificacionAlumno>
 *
 * @method FichaIdentificacionAlumno|null find($id, $lockMode = null, $lockVersion = null)
 * @method FichaIdentificacionAlumno|null findOneBy(array $criteria, array $orderBy = null)
 * @method FichaIdentificacionAlumno[]    findAll()
 * @method FichaIdentificacionAlumno[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FichaIdentificacionAlumnoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FichaIdentificacionAlumno::class);
    }
}
