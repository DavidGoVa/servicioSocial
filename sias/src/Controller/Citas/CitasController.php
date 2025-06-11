<?php

namespace Aragon\SIAS\Controller\Citas;

use Aragon\SIAS\Entity\Citas;
use Aragon\SIAS\Entity\RegistroCita;
use Aragon\SIAS\Entity\Periodo;
use Aragon\SIAS\Entity\UsuarioAragon;
use Aragon\SIAS\Repository\PeriodoRepository;
use Aragon\SIAS\Repository\RegistroCitaRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use IntlDateFormatter;


/**
 * Controlador que contiene la logica de las citas que va dar de alta el Psicólogo.
 */
#[Route(path: '/admin')]
class CitasController extends AbstractController
{
    /**
     * Pagina de inicio de las citas que programo el Psicólogo.
     *
     * @return Response
     */
    #[Route(path: '/citas', name: 'backend_citas_index')]
    public function citasIndex(EntityManagerInterface $entityManager): Response
    {
        $ciclos = $entityManager->getRepository(Periodo::class)->findTodosLosCiclos();
        $citas = $entityManager->getRepository(Citas::class)->findAll();
        $psicologos = $entityManager->getRepository(UsuarioAragon::class)->findPsicologos();
        $ultimoCiclo = $entityManager->getRepository(Periodo::class)->findUltimoPeriodoPorCiclo();

        return $this->render('Citas/index.html.twig', [
            'ciclos' => $ciclos,
            'psicologos' => $psicologos
        ]);
    }

    #[Route('/citas/asignar-psicologo/{id}', name: 'asignar_psicologo', methods: ['PATCH'])]
    public function asignarPsicologo(
        int $id,
        Request $request,
        EntityManagerInterface $entityManager
    ): Response {
        $registroCita = $entityManager->getRepository(RegistroCita::class)->find($id);
        if (!$registroCita) {
            return new JsonResponse(['error' => 'cita-notfound'], 404);
        }

        $psicologoId = $request->request->get('psicologo_id');
        $psicologo = $entityManager->getRepository(UsuarioAragon::class)->find($psicologoId);

        if (!$psicologo) {
            return new JsonResponse(['error' => 'psicologo-notfound'], 404);
        }

        $registroCita->setPsicologo($psicologo);
        $registroCita->setEstado("ACEPTADA");
        $entityManager->flush();

        return new JsonResponse(['message' => 'status-aceptada'], 200);
    }

    #[Route('/citas/rechazar-cita/{id}', name: 'rechazar_cita', methods: ['PATCH'])]
public function rechazarCita(
    int $id,
    Request $request,
    EntityManagerInterface $entityManager
): Response {
    $registroCita = $entityManager->getRepository(RegistroCita::class)->find($id);
    if (!$registroCita) {
        return new JsonResponse(['error' => 'cita-notfound'], 404);
    }

    $registroCita->setPsicologo(null);
    $registroCita->setEstado("RECHAZADA");
    $entityManager->flush();

    return new JsonResponse(['message' => 'status-rechazada'], 200);
}


    #[Route('/get-periodos-by-ciclo', name: 'api_ciclos', methods: ['GET'])]
    public function getPeriodos(Request $request, PeriodoRepository $periodoRepository): JsonResponse
    {
        $ciclo = $request->query->get('ciclo');

        if (!$ciclo) {
            return $this->json(['error' => 'Parámetro "ciclo" requerido.'], 400);
        }

        $periodos = $periodoRepository->findPeriodoByCicloEscolar($ciclo);

        $data = array_map(function ($periodo) {
            return [
                'id' => $periodo->getId(),
                'fechaInicio' => $periodo->getFechaInicio()->format('d/m/Y'),
                'fechaFin' => $periodo->getFechaFin()->format('d/m/Y'),
            ];
        }, $periodos);

        return $this->json($data);
    }
    #[Route('/get-citas', name: 'api_citas', methods: ['GET'])]
    public function getCitas(Request $request, RegistroCitaRepository $registroCitaRepository): JsonResponse
    {
        try {
            $periodo = $request->query->get('periodo');
            $estado = $request->query->get('estado');

            if (!$periodo) {
                return $this->json(['error' => 'Parámetro "periodo" requerido.'], 400);
            }
            if (!$estado) {
                return $this->json(['error' => 'Parámetro "estado" requerido.'], 400);
            }

            $citas = $registroCitaRepository->findPorConfirmar($estado, (int)$periodo);

            $data = array_map(function ($cita) {
                return [
                    'id' => $cita->getId(),
                    'usuario_aragon_id' => $cita->getUsuarioAragon()->getId(),
                    'usuario_aragon_nombre' => $cita->getUsuarioAragon()->getNombre(),
                    'usuario_aragon_apellidos' => $cita->getUsuarioAragon()->getApellidos(),
                    'fecha_cita' => $cita->getCitas()->getFechaAtencion()->format('Y-m-d'),
                    'hora_inicio_cita' => $cita->getCitas()->getHoraInicio()->format('H:i:s'),
                    'hora_final_cita' => $cita->getCitas()->getHoraFin()->format('H:i:s'),
                    'estado' => $cita->getEstado(),
                    'usuario_psicologo_id' => $cita->getPsicologo()->getId(),
                    'usuario_psicologo_nombre' => $cita->getPsicologo()->getNombre(),
                    'usuario_psicologo_apellidos' => $cita->getPsicologo()->getApellidos(),
                    'created_at' => $cita->getCreatedAt()->format('Y-m-d H:i:s'),
                ];
            }, $citas);

            return $this->json($data);
        } catch (\Throwable $e) {
            return $this->json([
                'error' => true,
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(), // Puedes comentar esto en producción
            ], 500);
        }
    }
}
