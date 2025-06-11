<?php

namespace Aragon\SIAS\Entity;

use Aragon\SIAS\Repository\UsuarioAragonRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(name: 'usuario_aragon')]
#[ORM\Entity(repositoryClass: UsuarioAragonRepository::class)]
class UsuarioAragon
{
    #[ORM\Column(name: "id", length: 18, options: ["comment" => "Número de cuenta del alumno o el CURP del Psicólogo.
Ejemplo: '304456982'"])]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "NONE")]
    private ?string $id = null;
    
    #[ORM\Column(name: "apellidos", length: 32, nullable: true, options: ["comment" => "Apellidos del alumno.
Ejemplo: 'LOPEZ'"])]
    private ?string $apellidos = null;

    #[ORM\Column(name: "nombre", length: 32, nullable: true, options: ["comment" => "Nombre del alumno.
Ejemplo: 'PEDRO'"])]
    private ?string $nombre = null;

    #[ORM\Column(name: "username", length: 64, options: ["comment" => "Correo del alumno.
Ejemplo: 'juan@aragon.unam.mx'"])]
    private ?string $username = null;

    #[ORM\Column(name: "estatus_email_dominio", length: 255, nullable: true, options: ["comment" => "Estatus del alumno."])]
    private ?string $estatusEmailDominio = null;
    
    #[ORM\Column(name: "fecha_inicio_acceso", type: Types::DATETIME_MUTABLE, nullable: true, options: ["comment" => "Fecha y hora de inicio donde el Psicólogo puede entrar al sistema.
Ejemplo: '2025-01-01 12:00:00'"])]
    private ?\DateTimeInterface $fechaInicioAcceso = null;

    #[ORM\Column(name: "fecha_fin_acceso", type: Types::DATETIME_MUTABLE, nullable: true, options: ["comment" => "Fecha y hora del ultimo día donde el Psicólogo puede entrar al sistema.
Ejemplo: '2025-01-01 12:00:00'"])]
    private ?\DateTimeInterface $fechaFinAcceso = null;

    #[ORM\Column(name: "fecha_alta", type: Types::DATE_MUTABLE, options: ["comment" => "Fecha en que se registro el alumno.
Ejemplo: '2025-01-01'"])]
    private ?\DateTimeInterface $fechaAlta = null;

    #[ORM\Column(name: "is_active", options: ["comment" => "Campo que indica si el usuario esta activo y servira para los Psicólogos.
Ejemplo: true"])]
    private ?bool $isActive = null;

    #[ORM\Column(name: "update_at_email_dominio", type: Types::DATETIME_MUTABLE, nullable: true, options: ["comment" => "Fecha y hora en que se actualizo el estatus del alumno.
Ejemplo: '2025-01-01 12:00:00'"])]
    private ?\DateTimeInterface $updateAtEmailDominio = null;
    
    #[ORM\OneToOne(targetEntity: FichaIdentificacionAlumno::class, mappedBy: 'usuarioAragon')]
    private ?FichaIdentificacionAlumno $fichaIdentificacionAlumno;
    
    /**
     * Muchos usuario tienen muchos roles.
     *
     * @var \Doctrine\Common\Collections\Collection<int, RolAragon>
     */
    #[ORM\JoinTable(name: 'usuario_aragon_has_role')]
    #[ORM\JoinColumn(name: 'usuario_aragon_id', referencedColumnName: 'id')]
    #[ORM\InverseJoinColumn(name: 'rol_aragon_id', referencedColumnName: 'id', unique: true)]
    #[ORM\ManyToMany(targetEntity: RolAragon::class, cascade:['persist' ])]
    private Collection $roles;
    
    /**
     * Muchos alumnos tienen muchas carreras.
     * 
     * @var \Doctrine\Common\Collections\Collection<int, Carrera>
     */
    #[ORM\JoinTable(name: 'usuario_has_carrera')]
    #[ORM\JoinColumn(name: 'usuario_aragon_id', referencedColumnName: 'id')]
    #[ORM\InverseJoinColumn(name: 'carrera_id', referencedColumnName: 'id', unique: true)]
    #[ORM\ManyToMany(targetEntity: Carrera::class)]
    private Collection $carreras;
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->carreras = new ArrayCollection();
        $this->roles    = new ArrayCollection();
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function setId(string $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getFechaFinAcceso(): ?\DateTimeInterface
    {
        return $this->fechaFinAcceso;
    }

    public function setFechaFinAcceso(?\DateTimeInterface $fechaFinAcceso): static
    {
        $this->fechaFinAcceso = $fechaFinAcceso;

        return $this;
    }

    public function getFechaAlta(): ?\DateTimeInterface
    {
        return $this->fechaAlta;
    }

    public function setFechaAlta(\DateTimeInterface $fechaAlta): static
    {
        $this->fechaAlta = $fechaAlta;

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

    public function getUpdateAtEmailDominio(): ?\DateTimeInterface
    {
        return $this->updateAtEmailDominio;
    }

    public function setUpdateAtEmailDominio(?\DateTimeInterface $updateAtEmailDominio): static
    {
        $this->updateAtEmailDominio = $updateAtEmailDominio;

        return $this;
    }

    public function getFechaInicioAcceso(): ?\DateTimeInterface
    {
        return $this->fechaInicioAcceso;
    }

    public function setFechaInicioAcceso(?\DateTimeInterface $fechaInicioAcceso): static
    {
        $this->fechaInicioAcceso = $fechaInicioAcceso;

        return $this;
    }

    public function getApellidos(): ?string
    {
        return $this->apellidos;
    }

    public function setApellidos(?string $apellidos): static
    {
        $this->apellidos = $apellidos;

        return $this;
    }

    public function getNombre(): ?string
    {
        return $this->nombre;
    }

    public function setNombre(?string $nombre): static
    {
        $this->nombre = $nombre;

        return $this;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): static
    {
        $this->username = $username;

        return $this;
    }

    public function getEstatusEmailDominio(): ?string
    {
        return $this->estatusEmailDominio;
    }

    public function setEstatusEmailDominio(?string $estatusEmailDominio): static
    {
        $this->estatusEmailDominio = $estatusEmailDominio;

        return $this;
    }

    public function isActive(): ?bool
    {
        return $this->isActive;
    }

    public function getFichaIdentificacionAlumno(): ?FichaIdentificacionAlumno
    {
        return $this->fichaIdentificacionAlumno;
    }

    public function setFichaIdentificacionAlumno(?FichaIdentificacionAlumno $fichaIdentificacionAlumno): static
    {
        // unset the owning side of the relation if necessary
        if ($fichaIdentificacionAlumno === null && $this->fichaIdentificacionAlumno !== null) {
            $this->fichaIdentificacionAlumno->setUsuarioAragon(null);
        }

        // set the owning side of the relation if necessary
        if ($fichaIdentificacionAlumno !== null && $fichaIdentificacionAlumno->getUsuarioAragon() !== $this) {
            $fichaIdentificacionAlumno->setUsuarioAragon($this);
        }

        $this->fichaIdentificacionAlumno = $fichaIdentificacionAlumno;

        return $this;
    }

    /**
     * @return Collection<int, RolAragon>
     */
    public function getRoles(): Collection
    {
        return $this->roles;
    }

    public function addRole(RolAragon $role): static
    {
        if (!$this->roles->contains($role)) {
            $this->roles->add($role);
        }

        return $this;
    }

    public function removeRole(RolAragon $role): static
    {
        $this->roles->removeElement($role);

        return $this;
    }

    /**
     * @return Collection<int, Carrera>
     */
    public function getCarreras(): Collection
    {
        return $this->carreras;
    }

    public function addCarrera(Carrera $carrera): static
    {
        if (!$this->carreras->contains($carrera)) {
            $this->carreras->add($carrera);
        }

        return $this;
    }

    public function removeCarrera(Carrera $carrera): static
    {
        $this->carreras->removeElement($carrera);

        return $this;
    }
}
