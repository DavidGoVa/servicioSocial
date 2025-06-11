<?php

namespace Aragon\SIAS\DataFixtures;

use Aragon\SIAS\Entity\Citas;
use Aragon\SIAS\Entity\RegistroCita;
use Aragon\SIAS\Entity\UsuarioAragon;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class RegistroCitaFixtures extends Fixture implements DependentFixtureInterface
{

    /**
     * @inheritDoc
     */
    public function load(ObjectManager $manager): void
    {
        $asignedAppoinment = 0;
        $nreference = 0;
        $alumnosRef = ReferencesHelper::getAlumnosReferences(ReferencesHelper::USERS_NUMBER);
        $psicologosRef = ReferencesHelper::getPsicologosReferences(ReferencesHelper::USERS_NUMBER);
        $edoCita = ['ACEPTADA',
            'POR CONFIRMAR',
            'CANCELADA',
            'RECHAZADA'];
        foreach ($alumnosRef as $ref) {
            $alumno = $this->getReference($ref, UsuarioAragon::class);
            while ($nreference < 3) {
                $cita = $this->getReference(CitaFixtures::CITA_REFERENCE . $asignedAppoinment, Citas::class);
                $psicologo = $this->getReference($psicologosRef[array_rand($psicologosRef)], UsuarioAragon::class);
                $registroCita = (new RegistroCita())->setCitas($cita)
                    ->setUsuarioAragon($alumno)
                    ->setPsicologo($psicologo)
                    ->setCreatedAt(new \DateTime())
                    ->setEstado($edoCita[array_rand($edoCita)]);
                $manager->persist($registroCita);
                $manager->flush();
                $nreference++;
                $asignedAppoinment++;
            }
            $nreference = 0;
        }
    }

    public function getDependencies(): array
    {
        return [CitaFixtures::class, UsuarioAragonFixtures::class];
    }
}