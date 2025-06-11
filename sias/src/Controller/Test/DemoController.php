<?php

namespace Aragon\SIAS\Controller\Test;

use Aragon\SIAS\Entity\Citas;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 * Controlador de prueba para ver los ejemplos de estilos utilizando el paquete
 * de templates.
 */
#[Route(path: '/pruebas')]
class DemoController extends AbstractController
{
    /**
     * Pagina de inicio de demo de templates
     *
     * @return Response
     */
    #[Route(path: '/admin', name: 'test_pruebas_admin_index')]
    public function adminIndex(EntityManagerInterface $entityManager): Response
    {
        $citas = $entityManager->getRepository(Citas::class)->findAll();
        
        return $this->render('Test/index.html.twig', ['citas' => $citas]);
    }
    
    /**
     * Pagina de inicio de demo de templates
     *
     * @return Response
     */
    #[Route(path: '/alumno', name: 'test_pruebas_alumno_index')]
    public function alumnoIndex(EntityManagerInterface $entityManager): Response
    {
        $citas = $entityManager->getRepository(Citas::class)->findAll();
        
        return $this->render('Test/alumnoIndex.html.twig', ['citas' => $citas]);
    }
}
