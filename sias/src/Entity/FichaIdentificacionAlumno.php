<?php

namespace Aragon\SIAS\Entity;

use Aragon\SIAS\Repository\FichaIdentificacionAlumnoRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(name: 'ficha_identificacion_alumno')]
#[ORM\UniqueConstraint(name: 'unique_ficha_numero_seguro', columns: ['no_seguro_social'])]
#[ORM\UniqueConstraint(name: 'unique_ficha_usuario', columns: ['usuario_aragon_id'])]
#[ORM\Entity(repositoryClass: FichaIdentificacionAlumnoRepository::class)]
class FichaIdentificacionAlumno
{
    #[ORM\Column(name: "id", type: Types::BIGINT, options: ["comment" => "Id de la tabla.
Ejemplo: 1"])]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "IDENTITY")]
    #[ORM\SequenceGenerator(sequenceName: "ficha_identificacion_alumno_id_seq", allocationSize: 1, initialValue: 1)]
    private ?string $id = null;

    #[ORM\Column(name: "fecha_nacimiento", type: Types::DATE_MUTABLE, options: ["comment" => "Fecha de nacimiento del alumno.
Ejemplo: '2025-02-29'"])]
    private ?\DateTimeInterface $fechaNacimiento = null;

    #[ORM\Column(name: "realizo_form_inicio", options: ["comment" => "Campo que me indica si el alumno lleno el formulario de inicio.
Ejemplo: true"])]
    private ?bool $realizoFormInicio = null;

    #[ORM\Column(name: "unidad_medica_familiar", length: 128, options: ["comment" => "Unidad médica familiar.
Ejemplo: 'UNIDAD ZARAGOZA'"])]
    private ?string $unidadMedicaFamiliar = null;

    #[ORM\Column(name: "telefono_celular", length: 16, nullable: true, options: ["comment" => "Telefono celular del alumno.
Ejemplo: '55 1234 5678'"])]
    private ?string $telefonoCelular = null;

    #[ORM\Column(name: "turno", length: 16, options: ["comment" => "Turno del alumno.
Ejemplo: 'MATUTINO'"])]
    private ?string $turno = null;

    #[ORM\Column(name: "sexo", length: 255, options: ["comment" => "Sexo del alumno ('M'|'F').
Ejemplo: 'M'"])]
    private ?string $sexo = null;

    #[ORM\Column(name: "domicilio", length: 128, options: ["comment" => "Domicilio del alumno.
Ejemplo: 'AVENIDA 7'"])]
    private ?string $domicilio = null;

    #[ORM\Column(name: "nivel_depresion", length: 32, nullable: true, options: ["comment" => "Nivel de depresión del alumno."])]
    private ?string $nivelDepresion = null;

    #[ORM\Column(name: "nivel_ansiedad", length: 32, nullable: true, options: ["comment" => "Nivel de ansiedad del alumno."])]
    private ?string $nivelAnsiedad = null;

    #[ORM\Column(name: "nivel_estres", length: 32, nullable: true, options: ["comment" => "Nivel de estrés del alumno."])]
    private ?string $nivelEstres = null;

    #[ORM\Column(name: "nivel_riesgo_suicida", length: 32, nullable: true, options: ["comment" => "Nivel de riesgo suicida del alumno."])]
    private ?string $nivelRiesgoSuicida = null;

    #[ORM\Column(name: "resumen_clinico", type: Types::TEXT, nullable: true, options: ["comment" => "Resumen clinico del alumno."])]
    private ?string $resumenClinico = null;

    #[ORM\OneToOne(targetEntity: UsuarioAragon::class, inversedBy: 'fichaIdentificacionAlumno')]
    #[ORM\JoinColumn(name: 'usuario_aragon_id', referencedColumnName: 'id', nullable: false)]
    private ?UsuarioAragon $usuarioAragon = null;

    #[ORM\Column(name: "no_seguro_social", length: 13, options: ["comment" => "Número del seguro social del alumno.
Ejemplo: 'XXXXXXXXXXXXX'"])]
    private ?string $noSeguroSocial = null;

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getFechaNacimiento(): ?\DateTimeInterface
    {
        return $this->fechaNacimiento;
    }

    public function setFechaNacimiento(\DateTimeInterface $fechaNacimiento): static
    {
        $this->fechaNacimiento = $fechaNacimiento;

        return $this;
    }

    public function getRealizoFormInicio(): ?bool
    {
        return $this->realizoFormInicio;
    }

    public function setRealizoFormInicio(bool $realizoFormInicio): static
    {
        $this->realizoFormInicio = $realizoFormInicio;

        return $this;
    }

    public function getUnidadMedicaFamiliar(): ?string
    {
        return $this->unidadMedicaFamiliar;
    }

    public function setUnidadMedicaFamiliar(string $unidadMedicaFamiliar): static
    {
        $this->unidadMedicaFamiliar = $unidadMedicaFamiliar;

        return $this;
    }

    public function getTelefonoCelular(): ?string
    {
        return $this->telefonoCelular;
    }

    public function setTelefonoCelular(?string $telefonoCelular): static
    {
        $this->telefonoCelular = $telefonoCelular;

        return $this;
    }

    public function getTurno(): ?string
    {
        return $this->turno;
    }

    public function setTurno(string $turno): static
    {
        $this->turno = $turno;

        return $this;
    }

    public function getSexo(): ?string
    {
        return $this->sexo;
    }

    public function setSexo(string $sexo): static
    {
        $this->sexo = $sexo;

        return $this;
    }

    public function getDomicilio(): ?string
    {
        return $this->domicilio;
    }

    public function setDomicilio(string $domicilio): static
    {
        $this->domicilio = $domicilio;

        return $this;
    }

    public function getNivelDepresion(): ?string
    {
        return $this->nivelDepresion;
    }

    public function setNivelDepresion(?string $nivelDepresion): static
    {
        $this->nivelDepresion = $nivelDepresion;

        return $this;
    }

    public function getNivelAnsiedad(): ?string
    {
        return $this->nivelAnsiedad;
    }

    public function setNivelAnsiedad(?string $nivelAnsiedad): static
    {
        $this->nivelAnsiedad = $nivelAnsiedad;

        return $this;
    }

    public function getNivelEstres(): ?string
    {
        return $this->nivelEstres;
    }

    public function setNivelEstres(?string $nivelEstres): static
    {
        $this->nivelEstres = $nivelEstres;

        return $this;
    }

    public function getNivelRiesgoSuicida(): ?string
    {
        return $this->nivelRiesgoSuicida;
    }

    public function setNivelRiesgoSuicida(?string $nivelRiesgoSuicida): static
    {
        $this->nivelRiesgoSuicida = $nivelRiesgoSuicida;

        return $this;
    }

    public function getResumenClinico(): ?string
    {
        return $this->resumenClinico;
    }

    public function setResumenClinico(?string $resumenClinico): static
    {
        $this->resumenClinico = $resumenClinico;

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

    public function getNoSeguroSocial(): ?string
    {
        return $this->noSeguroSocial;
    }

    public function setNoSeguroSocial(string $noSeguroSocial): static
    {
        $this->noSeguroSocial = $noSeguroSocial;

        return $this;
    }

    public function isRealizoFormInicio(): ?bool
    {
        return $this->realizoFormInicio;
    }
}
