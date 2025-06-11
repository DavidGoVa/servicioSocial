<?php

namespace Aragon\SIAS\Entity;

use Aragon\SIAS\Repository\CarreraRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(name: 'carrera')]
#[ORM\Entity(repositoryClass: CarreraRepository::class)]
class Carrera
{
    #[ORM\Column(name: "id", type: Types::SMALLINT, options: ["comment" => "Id  de la tabla.
Ejemplo: 1"])]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "NONE")]
    private ?int $id = null;

    #[ORM\Column(name: "plantel_id", length: 4, options: ["comment" => "Plantel al que pertenece la carrera.
Ejemplo: '0411'"])]
    private ?string $plantel = null;

    #[ORM\Column(name: "nombre", length: 64, options: ["comment" => "Nombre de la carrera.
Ejemplo: 'INGENIERIA EN COMPUTACION'"])]
    private ?string $nombre = null;

    #[ORM\Column(name: "abreviatura", length: 45, options: ["comment" => "Abreviatura de la carrera.
Ejemplo: 'ICO'"])]
    private ?string $abreviatura = null;

    #[ORM\Column(name: "sistema", length: 255, options: ["comment" => "Sistema al que pertenece la carrera (ESC|SUA)
Ejemplo: 'ESC'"])]
    private ?string $sistema = null;

    #[ORM\Column(name: "clave", length: 3, options: ["comment" => "Clave de la carrera.
Ejemplo: 110"])]
    private ?string $clave = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getPlantel(): ?string
    {
        return $this->plantel;
    }

    public function setPlantel(string $plantel): static
    {
        $this->plantel = $plantel;

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

    public function getAbreviatura(): ?string
    {
        return $this->abreviatura;
    }

    public function setAbreviatura(string $abreviatura): static
    {
        $this->abreviatura = $abreviatura;

        return $this;
    }

    public function getSistema(): ?string
    {
        return $this->sistema;
    }

    public function setSistema(string $sistema): static
    {
        $this->sistema = $sistema;

        return $this;
    }

    public function getClave(): ?string
    {
        return $this->clave;
    }

    public function setClave(string $clave): static
    {
        $this->clave = $clave;

        return $this;
    }
}
