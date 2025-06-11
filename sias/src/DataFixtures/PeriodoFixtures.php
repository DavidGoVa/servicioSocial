<?php

namespace Aragon\SIAS\DataFixtures;

use Aragon\SIAS\Entity\Config;
use Aragon\SIAS\Entity\Periodo;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class PeriodoFixtures extends Fixture
{

    const PERIODO_REFERENCE = 'periodo-reference';

    /**
     * @inheritDoc
     */
    public function load(ObjectManager $manager): void
    {
        $periodo = new Periodo();

        $periodo->setIsActive(true)
            ->setCicloEscolar($manager->getRepository(Config::class)->find(1)->getCicloActual())
            ->setFechaInicio(new \DateTime())
            ->setFechaFin((new \DateTime())->modify('+1 year'))
            ->setRegistrados(0);
        $manager->persist($periodo);
        $manager->flush();

        $this->addReference(self::PERIODO_REFERENCE, $periodo);
    }
}