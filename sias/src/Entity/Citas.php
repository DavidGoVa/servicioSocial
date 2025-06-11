<?php

namespace Aragon\SIAS\Entity;

use Aragon\SIAS\Repository\CitasRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(name: 'citas')]
#[ORM\Entity(repositoryClass: CitasRepository::class)]
class Citas
{
    #[ORM\Column(name: "id", type: Types::BIGINT, options: ["comment" => "Id de la tabla.
Ejemplo: 1"])]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "IDENTITY")]
    #[ORM\SequenceGenerator(sequenceName: "citas_id_seq", allocationSize: 1, initialValue: 1)]
    private ?string $id = null;

    #[ORM\Column(name: "hora_inicio", type: Types::TIME_MUTABLE, options: ["comment" => "Hora de inicio que solicito el alumno.
Ejemplo: '10:00:00'"])]
    private ?\DateTimeInterface $horaInicio = null;

    #[ORM\Column(name: "hora_fin", type: Types::TIME_MUTABLE, options: ["comment" => "Hora de fin que acabara la sesión.
Ejemplo: '13:00:00'"])]
    private ?\DateTimeInterface $horaFin = null;

    #[ORM\Column(name: "created_at", type: Types::DATETIME_MUTABLE, options: ["comment" => "Fecha y hora que el alumno hizo el registro de la cita.
Ejemplo: '2025-01-01 12:00:00'"])]
    private ?\DateTimeInterface $createdAt = null;

    #[ORM\JoinColumn(name: 'tipo_programa_id', referencedColumnName: 'id', nullable: false)]
    #[ORM\ManyToOne(targetEntity: TipoPrograma::class)]
    private ?TipoPrograma $tipoPrograma = null;
    
    #[ORM\JoinColumn(name: 'periodo_id', referencedColumnName: 'id', nullable: false)]
    #[ORM\ManyToOne(targetEntity: Periodo::class)]
    private ?Periodo $periodo = null;

    #[ORM\Column(name: "fecha_atencion", type: Types::DATE_MUTABLE, options: ["comment" => "Fecha que selecciono el alumno para recibir atención Psícologica.
Ejemplo: '2025-01-01'"])]
    private ?\DateTimeInterface $fechaAtencion = null;
    
    #[ORM\OneToMany(targetEntity: RegistroCita::class, mappedBy: 'citas')]
    private Collection $registrosCitas;

    public function __construct() {
        $this->registrosCitas = new ArrayCollection();
    }
    
    public function getId(): ?string
    {
        return $this->id;
    }

    public function getHoraInicio(): ?\DateTimeInterface
    {
        return $this->horaInicio;
    }

    public function setHoraInicio(\DateTimeInterface $horaInicio): static
    {
        $this->horaInicio = $horaInicio;

        return $this;
    }

    public function getHoraFin(): ?\DateTimeInterface
    {
        return $this->horaFin;
    }

    public function setHoraFin(\DateTimeInterface $horaFin): static
    {
        $this->horaFin = $horaFin;

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
    
    public function getPeriodo(): Periodo
    {
        return $this->periodo;
    }
    
    public function setPeriodo(Periodo $periodo): static
    {
        $this->periodo = $periodo;

        return $this;
    }

    public function getTipoPrograma(): TipoPrograma
    {
        return $this->tipoPrograma;
    }

    public function setTipoPrograma(TipoPrograma $tipoPrograma): static
    {
        $this->tipoPrograma = $tipoPrograma;

        return $this;
    }

    public function getFechaAtencion(): ?\DateTimeInterface
    {
        return $this->fechaAtencion;
    }

    public function setFechaAtencion(\DateTimeInterface $fechaAtencion): static
    {
        $this->fechaAtencion = $fechaAtencion;

        return $this;
    }

    /**
     * @return Collection<int, RegistroCita>
     */
    public function getRegistrosCitas(): Collection
    {
        return $this->registrosCitas;
    }

    public function addRegistrosCita(RegistroCita $registrosCita): static
    {
        if (!$this->registrosCitas->contains($registrosCita)) {
            $this->registrosCitas->add($registrosCita);
            $registrosCita->setCitas($this);
        }

        return $this;
    }

    public function removeRegistrosCita(RegistroCita $registrosCita): static
    {
        if ($this->registrosCitas->removeElement($registrosCita)) {
            // set the owning side to null (unless already changed)
            if ($registrosCita->getCitas() === $this) {
                $registrosCita->setCitas(null);
            }
        }

        return $this;
    }
}
