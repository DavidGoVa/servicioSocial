<?php

namespace Aragon\SIAS\DataFixtures;

class ReferencesHelper
{
    const USERS_NUMBER = 40;
    const APPOINTMENT_NUMBER = (self::USERS_NUMBER/2)*3; // Se divide entre dos ya que la mitad de los usuarios seran psicologos

    /**
     * @param int $count
     * @return string[]
     */
    public static function getAlumnosReferences(int $count):array{
        $references = [];
        for($i = 1; $i < $count; $i++){
            if($i % 2 == 0){
                $references[] = UsuarioAragonFixtures::ALUMNOS_REFERENCE."6".str_pad($i, 9, "0", STR_PAD_LEFT);
            }
        }
        return $references;
    }

    /**
     * @param int $count
     * @return string[]
     */
    public static function getPsicologosReferences(int $count):array{
        $references = [];
        for($i = 1; $i < $count; $i++){
            if($i % 2 != 0){
                $references[] = UsuarioAragonFixtures::PSICOLOGOS_REFERENCE."6".str_pad($i, 6, "0", STR_PAD_LEFT);
            }
        }
        return $references;
    }
}