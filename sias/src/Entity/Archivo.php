<?php

namespace Aragon\SIAS\Entity;

use Aragon\SIAS\Repository\ArchivoRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(name: 'archivo')]
#[ORM\Entity(repositoryClass: ArchivoRepository::class)]
class Archivo
{
    #[ORM\Column(name: "id", type: Types::BIGINT, options: ["comment" => "Id de la tabla.
Ejemplo: 1"])]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "IDENTITY")]
    #[ORM\SequenceGenerator(sequenceName: "archivo_id_seq", allocationSize: 1, initialValue: 1)]
    private ?string $id = null;

    #[ORM\Column(name: "created_at", type: Types::DATETIME_MUTABLE, options: ["comment" => "Fecha y hora en la que se subio el documento.
Ejemplo: '2025-01-01 12:00:00'"])]
    private ?\DateTimeInterface $createdAt = null;

    #[ORM\JoinColumn(name: 'usuario_aragon_id', referencedColumnName: 'id', nullable: false)]
    #[ORM\ManyToOne(targetEntity: UsuarioAragon::class)]
    private ?UsuarioAragon $usuarioAragon = null;

    #[ORM\Column(name: "tipo_documento", length: 32, options: ["comment" => "Tipo de documento. (RECETA, DIAGNOSTICO, INFORME)
Ejemplo: 'RECETA'"])]
    private ?string $tipoDocumento = null;

    #[ORM\Column(name: "archivo", length: 64, options: ["comment" => "Nombre del archivo con hash.
Ejemplo: '098f6bcd4621d373cade4e832627b4f6'"])]
    private ?string $archivo = null;

    #[ORM\Column(name: "extension", length: 5, options: ["comment" => "Extensión del archivo.
Ejemplo: 'pdf'"])]
    private ?string $extension = null;

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

    public function getUsuarioAragon(): ?UsuarioAragon
    {
        return $this->usuarioAragon;
    }

    public function setUsuarioAragon(UsuarioAragon $usuarioAragon): static
    {
        $this->usuarioAragon = $usuarioAragon;

        return $this;
    }

    public function getTipoDocumento(): ?string
    {
        return $this->tipoDocumento;
    }

    public function setTipoDocumento(string $tipoDocumento): static
    {
        $this->tipoDocumento = $tipoDocumento;

        return $this;
    }

    public function getArchivo(): ?string
    {
        return $this->archivo;
    }

    public function setArchivo(string $archivo): static
    {
        $this->archivo = $archivo;

        return $this;
    }

    public function getExtension(): ?string
    {
        return $this->extension;
    }

    public function setExtension(string $extension): static
    {
        $this->extension = $extension;

        return $this;
    }
}
