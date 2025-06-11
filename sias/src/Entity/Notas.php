<?php

namespace Aragon\SIAS\Entity;

use Aragon\SIAS\Repository\RegistroCitaRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(name: 'notas')]
#[ORM\Entity(repositoryClass: Notas::class)]
class Notas
{
    #[ORM\Column(name: "id", type: Types::BIGINT, options: ["comment" => "Id de la tabla.
Ejemplo: 1"])]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "SEQUENCE")]
    #[ORM\SequenceGenerator(sequenceName: "notas_id_seq", allocationSize: 1, initialValue: 1)]
    private ?string $id = null;
    
    #[ORM\Column(name: "descripcion", type: Types::TEXT, options: ["comment" => "Captura u observaciones que realizo el Psicólogo.
Ejemplo: 'El alumno muestra ...'"])]
    private ?string $descripcion = null;
    
    #[ORM\JoinColumn(name: 'registro_cita_id', referencedColumnName: 'id', nullable: false)]
    #[ORM\ManyToOne(targetEntity: RegistroCita::class, inversedBy: 'notas')]
    private ?RegistroCita $registroCita = null;
        
    #[ORM\Column(name: "created_at", type: Types::DATETIME_MUTABLE, options: ["comment" => "Fecha y hora en que el Psicólogo realizo la captura de la nota.
Ejemplo: '2025-01-01 12:00:00'"])]
    private ?\DateTimeInterface $createdAt = null;

    
    public function getId(): ?string
    {
        return $this->id;
    }

    public function getDescripcion(): ?string
    {
        return $this->descripcion;
    }

    public function setDescripcion(?string $descripcion): static
    {
        $this->descripcion = $descripcion;
        
        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getRegistroCita(): ?RegistroCita
    {
        return $this->registroCita;
    }

    public function setRegistroCita(?RegistroCita $registroCita): static
    {
        $this->registroCita = $registroCita;

        return $this;
    }
}
