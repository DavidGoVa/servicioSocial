<?php

namespace Aragon\SIAS\Entity;

use Aragon\SIAS\Repository\TipoProgramaRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(name: 'tipo_programa')]
#[ORM\Entity(repositoryClass: TipoProgramaRepository::class)]
class TipoPrograma
{
    #[ORM\Column(name: "id", type: Types::SMALLINT, options: ["comment" => "Id de la tabla.
Ejemplo: 1"])]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "NONE")]
    private ?int $id = null;

    #[ORM\Column(name: "nombre", length: 32, options: ["comment" => "Nombre de la asesoría.
Ejemplo: 'CABINA TELEFONICA'"])]
    private ?string $nombre = null;

    #[ORM\Column(name: "informacion", type: Types::TEXT, options: ["comment" => "Información de la asesoría.
Ejemplo: 'LOREM ....'"])]
    private ?string $informacion = null;
    
    #[ORM\Column(name: "requiere_cita", type: Types::BOOLEAN, options: ["comment" => "Campo que me indica si el tipo de programa necesita agendar una cita.
Ejemplo: true"])]
    private ?bool $requiereCita = true;

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

    public function getInformacion(): ?string
    {
        return $this->informacion;
    }

    public function setInformacion(string $informacion): static
    {
        $this->informacion = $informacion;

        return $this;
    }
    
    public function getRequiereCita(): ?bool
    {
        return $this->requiereCita;
    }

    public function setRequiereCita(?bool $requiereCita): static
    {
        $this->requiereCita = $requiereCita;
        
        return $this;
    }
}
