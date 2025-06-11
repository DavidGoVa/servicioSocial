<?php

namespace Aragon\SIAS\Entity;

use Aragon\SIAS\Repository\RegistroCitaRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(name: 'registro_cita')]
#[ORM\Entity(repositoryClass: RegistroCitaRepository::class)]
class RegistroCita
{
    #[ORM\Column(name: "id", type: Types::BIGINT, options: ["comment" => "Id de la tabla.
Ejemplo: 1"])]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "IDENTITY")]
    #[ORM\SequenceGenerator(sequenceName: "registro_cita_id_seq", allocationSize: 1, initialValue: 1)]
    private ?string $id = null;

    #[ORM\Column(name: "estado", length: 255, options: ["comment" => "Estado de la cita.
Ejemplo: 'CANCELADA'"])]
    private ?string $estado = null;
    
    #[ORM\Column(name: "asistio", type: Types::BOOLEAN, options: ["comment" => "Campo que me indica si el alumno asistio a la cita.
Ejemplo: true"])]
    private ?bool $asistio = false;
        
    #[ORM\Column(name: "created_at", type: Types::DATETIME_MUTABLE, options: ["comment" => "Fecha y hora que el alumno hizo el registro de la cita.
Ejemplo: '2025-01-01 12:00:00'"])]
    private ?\DateTimeInterface $createdAt = null;

    #[ORM\JoinColumn(name: 'usuario_aragon_id', referencedColumnName: 'id', nullable: false)]
    #[ORM\ManyToOne(targetEntity: UsuarioAragon::class)]
    private ?UsuarioAragon $usuarioAragon = null;

    #[ORM\JoinColumn(name: 'psicologo_id', referencedColumnName: 'id', nullable: false)]
    #[ORM\ManyToOne(targetEntity: UsuarioAragon::class)]
    private ?UsuarioAragon $psicologo = null;
    
    #[ORM\JoinColumn(name: 'cita_id', referencedColumnName: 'id', nullable: false)]
    #[ORM\ManyToOne(targetEntity: Citas::class, inversedBy: 'registrosCitas')]
    private ?Citas $citas = null;
    
    #[ORM\OneToMany(targetEntity: Notas::class, mappedBy: 'registroCita')]
    private Collection $notas;

    public function __construct() {
        $this->notas = new ArrayCollection();
    }
    
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

    public function getUsuarioAragon(): UsuarioAragon
    {
        return $this->usuarioAragon;
    }

    public function setUsuarioAragon(UsuarioAragon $usuarioAragon): static
    {
        $this->usuarioAragon = $usuarioAragon;

        return $this;
    }

    public function getPsicologo(): ?UsuarioAragon
    {
        return $this->psicologo;
    }

    public function setPsicologo(?UsuarioAragon $psicologo): static
    {
        $this->psicologo = $psicologo;

        return $this;
    }

    public function getEstado(): ?string
    {
        return $this->estado;
    }

    public function setEstado(string $estado): static
    {
        $this->estado = $estado;

        return $this;
    }
    
    public function getAsistio(): ?bool
    {
        return $this->asistio;
    }

    public function setAsistio(?bool $asistio): static
    {
        $this->asistio = $asistio;
        
        return $this;
    }

    
    public function getCitas(): ?Citas
    {
        return $this->citas;
    }

    public function setCitas(?Citas $citas): static
    {
        $this->citas = $citas;

        return $this;
    }

    /**
     * @return Collection<int, Notas>
     */
    public function getNotas(): Collection
    {
        return $this->notas;
    }

    public function addNota(Notas $nota): static
    {
        if (!$this->notas->contains($nota)) {
            $this->notas->add($nota);
            $nota->setRegistroCita($this);
        }

        return $this;
    }

    public function removeNota(Notas $nota): static
    {
        if ($this->notas->removeElement($nota)) {
            // set the owning side to null (unless already changed)
            if ($nota->getRegistroCita() === $this) {
                $nota->setRegistroCita(null);
            }
        }

        return $this;
    }
}
