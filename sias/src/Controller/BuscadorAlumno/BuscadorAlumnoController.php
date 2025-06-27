<?php

namespace Aragon\SIAS\Controller\BuscadorAlumno;

use Aragon\SIAS\Entity\UsuarioAragon;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use IntlDateFormatter;


/**
 * Controlador que contiene la logica de labusqueda de alumnos
 */
#[Route(path: '/admin')]
class BuscadorAlumnoController extends AbstractController
{
    /**
     * Pagina de inicio de la busqueda de alumnos.
     *
     * @return Response
     */
    #[Route(path: '/buscar-alumno', name: 'app_buscar_alumno')]
    public function buscarAlumno(EntityManagerInterface $entityManager): Response
    {
        return $this->render('buscador/index.html.twig', []);
    }

    #[Route('/get-alumnos', name: 'backend-get-alumnos', methods: ['GET'])]
    public function getPeriodos(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $busqueda = $request->query->get('busqueda');

        // ✅ Si el input está vacío, devolvemos una lista vacía
        if ($busqueda === '') {
            return $this->json([]);
        }

        // 🔍 Buscar alumnos por nombre o cuenta
        $alumnos = $entityManager->getRepository(UsuarioAragon::class)->buscarAlumnosAvanzado($busqueda);

        // 🧱 Mapear resultados
        $data = array_map(function ($alumno) {
            return [
                'numero_de_cuenta' => $alumno->getId(),
                'nombre'           => $alumno->getNombre(),
                'apellidos'        => $alumno->getApellidos(),
                'username'         => $alumno->getUsername(),
                'fechaAlta'        => $alumno->getFechaAlta()?->format('d/m/Y'),
                'isActive'         => $alumno->getIsActive(),
                'carrera'          => $alumno->getCarreras()->first()?->getNombre(),
            ];
        }, $alumnos);

        return $this->json($data);
    }

} 
