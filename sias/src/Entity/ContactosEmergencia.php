<?php

namespace Aragon\SIAS\Entity;

use Aragon\SIAS\Repository\ContactosEmergenciaRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(name: 'contactos_emergencia')]
#[ORM\Entity(repositoryClass: ContactosEmergenciaRepository::class)]
class ContactosEmergencia
{
    #[ORM\Column(name: "id", type: Types::BIGINT, options: ["comment" => "Id de la tabla.
Ejemplo: 1"])]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "IDENTITY")]
    #[ORM\SequenceGenerator(sequenceName: "contactos_emergencia_id_seq", allocationSize: 1, initialValue: 1)]
    private ?string $id = null;

    #[ORM\Column(name: "created_at", type: Types::DATETIME_MUTABLE, options: ["comment" => "Fecha y hora en que se dio de alta el registro.
Ejemplo: '2025-01-01 12:00:00'"])]
    private ?\DateTimeInterface $createdAt = null;

    #[ORM\Column(name: "nombres", length: 32, options: ["comment" => "Nombre del familiar del alumno.
Ejemplo: 'JUAN'"])]
    private ?string $nombres = null;

    #[ORM\Column(name: "primer_apellido", length: 32, nullable: true, options: ["comment" => "Primer apellido del familiar del alumno.
Ejemplo: 'LOPEZ'"])]
    private ?string $primerApellido = null;

    #[ORM\Column(name: "segundo_apellido", length: 32, nullable: true, options: ["comment" => "Segundo apellido del familiar del alumno.
Ejemplo: 'LOPEZ'"])]
    private ?string $segundoApellido = null;

    #[ORM\Column(name: "telefono", length: 32, nullable: true, options: ["comment" => "Teléfono del familiar del alumno.
Ejemplo: '55 1234 5678'"])]
    private ?string $telefono = null;

    #[ORM\Column(name: "parentesco", length: 255, options: ["comment" => "Parentesco del familiar del alumno.
Ejemplo: 'PADRE'"])]
    private ?string $parentesco = null;

    #[ORM\JoinColumn(name: 'usuario_aragon_id', referencedColumnName: 'id', nullable: false)]
    #[ORM\ManyToOne(targetEntity: UsuarioAragon::class)]
    private ?UsuarioAragon $usuarioAragon = null;

    public function getId(): ?string
    {
        return $this->id;
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

    public function getNombres(): ?string
    {
        return $this->nombres;
    }

    public function setNombres(string $nombres): static
    {
        $this->nombres = $nombres;

        return $this;
    }

    public function getPrimerApellido(): ?string
    {
        return $this->primerApellido;
    }

    public function setPrimerApellido(?string $primerApellido): static
    {
        $this->primerApellido = $primerApellido;

        return $this;
    }

    public function getSegundoApellido(): ?string
    {
        return $this->segundoApellido;
    }

    public function setSegundoApellido(?string $segundoApellido): static
    {
        $this->segundoApellido = $segundoApellido;

        return $this;
    }

    public function getTelefono(): ?string
    {
        return $this->telefono;
    }

    public function setTelefono(?string $telefono): static
    {
        $this->telefono = $telefono;

        return $this;
    }

    public function getParentesco(): ?string
    {
        return $this->parentesco;
    }

    public function setParentesco(string $parentesco): static
    {
        $this->parentesco = $parentesco;

        return $this;
    }

    public function getUsuarioAragon(): ?UsuarioAragon
    {
        return $this->usuarioAragon;
    }

    public function setUsuarioAragon(UsuarioAragon $usuarioAragon): static
    {
        $this->usuarioAragon = $usuarioAragon;

        return $this;
    }
}
