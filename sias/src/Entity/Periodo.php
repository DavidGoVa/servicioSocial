<?php

namespace Aragon\SIAS\Entity;

use Aragon\SIAS\Repository\PeriodoRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(name: 'periodo')]
#[ORM\Entity(repositoryClass: PeriodoRepository::class)]
class Periodo
{
    #[ORM\Column(name: "id", type: Types::BIGINT, options: ["comment" => "Id de la tabla.
Ejemplo: 1"])]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "SEQUENCE")]
    #[ORM\SequenceGenerator(sequenceName: "periodo_id_seq", allocationSize: 1, initialValue: 1)]
    private ?string $id = null;

    #[ORM\Column(name: "fecha_fin", type: Types::DATETIME_MUTABLE, options: ["comment" => "Fecha y hora del ultimo día donde el alumno podra realizar su cita.
Ejemplo: '2025-01-01 12:00:00'"])]
    private ?\DateTimeInterface $fechaFin = null;

    #[ORM\Column(name: "registrados", type: Types::SMALLINT, options: ["comment" => "Número de alumnos que han registrado su cita.
Ejemplo: 25"])]
    private ?int $registrados = null;

    #[ORM\Column(name: "fecha_inicio", type: Types::DATETIME_MUTABLE, options: ["comment" => "Fecha y hora donde el alumno podra empezar a realizar su cita.
Ejemplo: '2025-01-01 12:00:00'"])]
    private ?\DateTimeInterface $fechaInicio = null;

    #[ORM\Column(name: "is_active", options: ["comment" => "Campo que indica si el periodo esta activo o no.
Ejemplo: true"])]
    private ?bool $isActive = null;

    #[ORM\Column(name: "ciclo_escolar", length: 5, options: ["comment" => "Id del ciclo escolar.
Ejemplo: '20252'"])]
    private ?string $cicloEscolar = null;

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getFechaFin(): ?\DateTimeInterface
    {
        return $this->fechaFin;
    }

    public function setFechaFin(\DateTimeInterface $fechaFin): static
    {
        $this->fechaFin = $fechaFin;

        return $this;
    }

    public function getRegistrados(): ?int
    {
        return $this->registrados;
    }

    public function setRegistrados(int $registrados): static
    {
        $this->registrados = $registrados;

        return $this;
    }

    public function getFechaInicio(): ?\DateTimeInterface
    {
        return $this->fechaInicio;
    }

    public function setFechaInicio(\DateTimeInterface $fechaInicio): static
    {
        $this->fechaInicio = $fechaInicio;

        return $this;
    }

    public function getIsActive(): ?bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): static
    {
        $this->isActive = $isActive;

        return $this;
    }

    public function getCicloEscolar(): ?string
    {
        return $this->cicloEscolar;
    }

    public function setCicloEscolar(string $cicloEscolar): static
    {
        $this->cicloEscolar = $cicloEscolar;

        return $this;
    }

    public function isActive(): ?bool
    {
        return $this->isActive;
    }
}
