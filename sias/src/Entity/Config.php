<?php

namespace Aragon\SIAS\Entity;

use Aragon\SIAS\Repository\ConfigRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(name: 'config')]
#[ORM\Entity(repositoryClass: ConfigRepository::class)]
class Config
{
    #[ORM\Id]
    #[ORM\Column(name: "id", type: Types::SMALLINT, options: ["comment" => "Id de la tabla.
Ejemplo: 1"])]
    #[ORM\GeneratedValue(strategy: "NONE")]
    private ?int $id = null;

    #[ORM\Column(name: "ciclo_actual", length: 5, options: ["comment" => "Id del ciclo escolar actual.
Ejemplo: '20252'"])]
    private ?string $cicloActual = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getCicloActual(): ?string
    {
        return $this->cicloActual;
    }

    public function setCicloActual(string $cicloActual): static
    {
        $this->cicloActual = $cicloActual;

        return $this;
    }
}
