<?php

namespace Aragon\SIAS\DataFixtures;

use Aragon\SIAS\Entity\Citas;
use Aragon\SIAS\Entity\Periodo;
use Aragon\SIAS\Entity\TipoPrograma;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Random\RandomException;

class CitaFixtures extends Fixture implements DependentFixtureInterface
{
    const CITA_REFERENCE = 'cita-';

    /**
     * @param ObjectManager $manager
     * @return void
     * @throws RandomException
     * @throws \DateMalformedStringException
     */
    public function load(ObjectManager $manager): void
    {
        $periodo = $this->getReference(PeriodoFixtures::PERIODO_REFERENCE,Periodo::class);
        for($i=0; $i < ReferencesHelper::APPOINTMENT_NUMBER; $i++){
            $citaTmp = new Citas();
            $date = (new \DateTime())->modify('+'.random_int(1,3).' day');
            $horaFinal = $date->add(new \DateInterval('PT'.random_int(1,2).'H'));
            $tipoPrograma = $manager->getRepository(TipoPrograma::class)->find(random_int(1,3));
            $citaTmp->setFechaAtencion($date)
                ->setCreatedAt($date)
                ->setHoraFin($horaFinal)
                ->setHoraInicio($date)
                ->setTipoPrograma($tipoPrograma)
                ->setPeriodo($periodo);
            $manager->persist($citaTmp);

            $this->addReference(self::CITA_REFERENCE.$i, $citaTmp);

            $manager->flush();
        }
    }

    public function getDependencies(): array
    {
        return [PeriodoFixtures::class];
    }
}