<?php

namespace Aragon\SIAS\Entity;

use Aragon\SIAS\Repository\FormatoRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(name: 'formato')]
#[ORM\Entity(repositoryClass: FormatoRepository::class)]
class Formato
{
    #[ORM\Column(name: "id", type: Types::SMALLINT, options: ["comment" => "Id de la tabla.
Ejemplo: 1"])]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "NONE")]
    private ?int $id = null;

    #[ORM\Column(name: "descripcion", length: 255, options: ["comment" => "Descripcion del formato.
Ejemplo: 'Formato de concentimiento ...'"])]
    private ?string $descripcion = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getDescripcion(): ?string
    {
        return $this->descripcion;
    }

    public function setDescripcion(string $descripcion): static
    {
        $this->descripcion = $descripcion;

        return $this;
    }
}
