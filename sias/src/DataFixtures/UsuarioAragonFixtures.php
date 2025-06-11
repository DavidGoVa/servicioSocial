<?php

namespace Aragon\SIAS\DataFixtures;


use Aragon\SIAS\Entity\Carrera;
use Aragon\SIAS\Entity\RolAragon;
use Aragon\SIAS\Entity\UsuarioAragon;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class UsuarioAragonFixtures extends Fixture
{
    const FAKE_PEOPLE_API_URL = "https://fakerapi.it/api/v2/persons?_quantity=".ReferencesHelper::USERS_NUMBER;
    const ALUMNOS_REFERENCE = 'usuario-aragon-alumno-';
    const PSICOLOGOS_REFERENCE = 'usuario-aragon-psicologo-';
    /**
     * @param ObjectManager $manager
     * @return void
     */
    public function load(ObjectManager $manager): void
    {
        $httpClient = HttpClient::create();
        try{
            $response = $httpClient->request('GET', self::FAKE_PEOPLE_API_URL);
            $statusCode = $response->getStatusCode();
            if($statusCode == 200){
                $data = json_decode($response->getContent())->data;
                $rolAlumno = $manager->getRepository(RolAragon::class)->find(RolAragon::ROLE_ALUMNO);
                $rolPsicologo = $manager->getRepository(RolAragon::class)->find(RolAragon::ROLE_PSICOLOGO);

                foreach ($data as $person){
                    $tmpUserAragon = new UsuarioAragon();
                    $isAlumno = intval($person->id) % 2 == 0;
                    $tmpUserAragon->setId("6".str_pad($person->id,$isAlumno ? 9 : 6,0, STR_PAD_LEFT));
                    $tmpUserAragon->setNombre($person->firstname);
                    $tmpUserAragon->setApellidos($person->lastname);
                    $tmpUserAragon->setUsername("{$person->firstname}{$person->lastname}@test.ara.unam.mx");
                    $tmpCarrera = $manager->getRepository(Carrera::class)->find(random_int(1,3));
                    $tmpUserAragon->addCarrera($tmpCarrera);
                    $tmpUserAragon->addRole( $isAlumno ? $rolAlumno : $rolPsicologo);
                    $tmpUserAragon->setFechaAlta(new \DateTime());
                    $tmpUserAragon->setIsActive(true);

                    $manager->persist($tmpUserAragon);

                    if($isAlumno){
                        $this->addReference(self::ALUMNOS_REFERENCE.$tmpUserAragon->getId(), $tmpUserAragon);
                    }else{
                        $this->addReference(self::PSICOLOGOS_REFERENCE.$tmpUserAragon->getId(), $tmpUserAragon);
                    }
                }

                $manager->flush();


            }

        } catch (TransportExceptionInterface $e) {
            // Network error, DNS, timeout
            throw new \RuntimeException('Network error occurred: ' . $e->getMessage(), 0, $e);
        } catch (ClientExceptionInterface $e) {
            // 4xx errors
            throw new \RuntimeException('Client error: ' . $e->getMessage(), 0, $e);
        } catch (ServerExceptionInterface $e) {
            // 5xx errors
            throw new \RuntimeException('Server error: ' . $e->getMessage(), 0, $e);
        } catch (RedirectionExceptionInterface $e) {
            // 3xx redirection errors
            throw new \RuntimeException('Redirection error: ' . $e->getMessage(), 0, $e);
        } catch (\Exception $e) {
            // Fallback
            throw new \RuntimeException('Unexpected error: ' . $e->getMessage(), 0, $e);
        } catch (DecodingExceptionInterface $e) {
            throw new \RuntimeException('Decoding error: ' . $e->getMessage(), 0, $e);
        }

    }
}