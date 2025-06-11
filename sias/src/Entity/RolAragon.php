<?php

namespace Aragon\SIAS\Entity;

use Aragon\SIAS\Repository\RolAragonRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(name: 'rol_aragon')]
#[ORM\Entity(repositoryClass: RolAragonRepository::class)]
class RolAragon
{
    public const ROLE_SUPER                     = 1;
    public const ROLE_JDIVISION                 = 2;
    public const ROLE_JCARRERA                  = 3;
    public const ROLE_PROFESOR                  = 4;
    public const ROLE_ALUMNO                    = 5;
    public const ROLE_ARAGON_SWITCHER           = 6;
    public const ROLE_ADMIN_PRESTAMOS_EQUIPOS   = 7;
    public const ROLE_ARAGON_PUBLISHER          = 8;
    public const ROLE_ARAGON_CUENTAS_TEMPORALES = 9;
    public const ROLE_PSICOLOGO                 = 10;
    public const ROLE_PSICOLOGO_TEMPORAL        = 11;
    
    
    #[ORM\Column(name: "id", type: Types::SMALLINT, options: ["comment" => "Id de la tabla.
Ejemplo: 1"])]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "NONE")]
    private ?int $id = null;

    #[ORM\Column(name: "nombre", length: 64, options: ["comment" => "Nombre del ROL.
Ejemplo: 'ROLE_ADMIN_PSICOLOGO'"])]
    private ?string $nombre = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getNombre(): ?string
    {
        return $this->nombre;
    }

    public function setNombre(string $nombre): static
    {
        $this->nombre = $nombre;

        return $this;
    }
}
