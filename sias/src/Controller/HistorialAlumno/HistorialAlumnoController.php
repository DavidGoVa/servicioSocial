<?php

namespace Aragon\SIAS\Controller\HistorialAlumno;

use Aragon\SIAS\Entity\Citas;
use Aragon\SIAS\Entity\Archivo;
use Aragon\SIAS\Entity\RegistroCita;
use Aragon\SIAS\Entity\ContactosEmergencia;
use Aragon\SIAS\Entity\Notas;
use Aragon\SIAS\Entity\UsuarioAragon;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;


/**
 * Controlador que contiene la logica de las citas que va dar de alta el Psicólogo.
 */
#[Route(path: '/admin')]
class HistorialAlumnoController extends AbstractController
{
    /**
     * Pagina de inicio de las citas que programo el Psicólogo.
     *
     * @return Response
     */
    #[Route(path: '/historial-alumno/{numeroCuenta}', name: 'backend_historial_alumno_index')]
    public function historialAlumnoIndex(
        string $numeroCuenta,
        EntityManagerInterface $entityManager
    ): Response {

        $alumno = $entityManager->getRepository(UsuarioAragon::class)->findOneBy(['id' => $numeroCuenta]);
        $contactos_emergencia = $entityManager->getRepository(ContactosEmergencia::class)->findContactosByAlumno($alumno->getId());
        $registro_citas = $entityManager->getRepository(RegistroCita::class)->findCitasByAlumno($alumno->getId());
        $archivos = $entityManager->getRepository(Archivo::class)->findArchivosByAlumno($alumno->getId());

        if (!$alumno) {
            throw $this->createNotFoundException('Alumno no encontrado.');
        }
        if (!$alumno->getIsActive()) {
            throw $this->createNotFoundException('Alumno NO ACTIVO.');
        }


        return $this->render('HistorialAlumno/index.html.twig', [
            'alumno' => $alumno,
            'contactosEmergencia' => $contactos_emergencia,
            'registroCitas' => $registro_citas,
            'archivos' => $archivos,
        ]);
    }

    #[Route('/historial-alumno/confirmar-cita/{id}', name: 'confirmar-asistencia', methods: ['PATCH'])]
    public function confirmarAsistencia(
        int $id,
        Request $request,
        EntityManagerInterface $entityManager
    ): Response {
        $registroCita = $entityManager->getRepository(RegistroCita::class)->find($id);

        $banderaAsistencia = false;

        if (!$registroCita) {
            return new JsonResponse(['error' => 'cita-notfound'], 404);
        }
        $valorAsistencia = $request->request->get('valorAsistencia');

        if ($valorAsistencia == "si") {
            $banderaAsistencia = true;
        } else if ($valorAsistencia == "no") {
            $banderaAsistencia = false;
        }

        $registroCita->setAsistio($banderaAsistencia);
        $entityManager->flush();

        return new JsonResponse(['message' => 'status-aceptada'], 200);
    }

    #[Route('/historial-alumno/guardar-nota/{id}', name: 'guardar-nota', methods: ['PATCH'])]
    public function guardarNota(
        int $id,
        Request $request,
        EntityManagerInterface $entityManager
    ): Response {
        $cita = $entityManager->getRepository(RegistroCita::class)->find($id);

        if (!$cita) {
            return new JsonResponse(['error' => 'cita-notfound'], 404);
        }

        $idNota = $request->request->get('idNota');
        $contenidoNota = $request->request->get('contenido');

        $nota = $cita->getNotas()->filter(function ($nota) use ($idNota) {
            return $nota->getId() == (int)$idNota;
        })->first();

        if (!$nota) {
            return new JsonResponse(['error' => 'cita-notfound'], 404);
        }

        $nota->setDescripcion($contenidoNota);
        $entityManager->flush();

        return new JsonResponse(['message' => 'status-aceptada'], 200);
    }

    #[Route('/historial-alumno/eliminar-nota/{id}', name: 'eliminar-nota', methods: ['PATCH'])]
    public function eliminarNota(
        int $id,
        Request $request,
        EntityManagerInterface $entityManager
    ): Response {
        $cita = $entityManager->getRepository(RegistroCita::class)->find($id);

        if (!$cita) {
            return new JsonResponse(['error' => 'cita-notfound'], 404);
        }

        $idNota = $request->request->get('idNota');

        $nota = $cita->getNotas()->filter(function ($nota) use ($idNota) {
            return $nota->getId() == (int)$idNota;
        })->first();

        if (!$nota) {
            return new JsonResponse(['error' => 'cita-notfound'], 404);
        }


        $entityManager->remove($nota);

        $entityManager->flush();

        return new JsonResponse(['message' => 'status-aceptada'], 200);
    }

    #[Route('/historial-alumno/guardar-nueva-nota/{id}', name: 'guardar-nueva-nota', methods: ['POST'])]
    public function guardarNuevaNota(
        int $id,
        Request $request,
        EntityManagerInterface $entityManager
    ): Response {
        $cita = $entityManager->getRepository(RegistroCita::class)->find($id);

        if (!$cita) {
            return new JsonResponse(['error' => 'cita-notfound'], 404);
        }

        $contenidoNota = $request->request->get('contenido');

        $nota = new Notas();
        $nota->setDescripcion($contenidoNota);
        $now = new \DateTime();
        $now->setTimezone(new \DateTimeZone('UTC'));
        $nota->setCreatedAt($now);
        $nota->setRegistroCita($cita);

        $cita->addNota($nota);

        $entityManager->persist($nota);
        $entityManager->flush();

        return new JsonResponse([
            'message' => 'status-aceptada',
            'nota' => [
                'id' => $nota->getId(),
                'descripcion' => $nota->getDescripcion(),
                'createdAt' => $nota->getCreatedAt()->format('d/m/Y H:i'),
            ]
        ], 200);
    }

    #[Route('/historial-alumno/subir-documento/', name: 'subir_documento', methods: ['POST'])]
    public function subirDocumento(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $archivo = $request->files->get('archivo');
        $carrera = $request->request->get('carrera');
        $generacion = $request->request->get('generacion');
        $numeroDeCuenta = $request->request->get('numeroDeCuenta');
        $descripcion = $request->request->get('descripcion');

        if (!$archivo) {
            return new JsonResponse(['message' => 'Archivo no encontrado'], 400);
        }

        if (!$carrera || !$generacion || !$numeroDeCuenta || !$descripcion) {
            return new JsonResponse(['message' => 'Datos incompletos'], 400);
        }

        $nombreCompletoArchivo = $archivo->getClientOriginalName();

        $informacionDelArchivo = pathinfo($nombreCompletoArchivo);

        $nombreArchivo = $informacionDelArchivo['filename'];
        $extension = $informacionDelArchivo['extension'];

        $nombreArchivoHash = hash('sha256', $nombreArchivo);

        $alumnoAlQueLePerteneceElArchivo = $entityManager->getRepository(UsuarioAragon::class)->find($numeroDeCuenta);

        if (!$alumnoAlQueLePerteneceElArchivo) {
            return new JsonResponse(['error' => 'cita-notfound'], 404);
        }

        $nuevoArchivo = new Archivo();

        $nuevoArchivo->setUsuarioAragon($alumnoAlQueLePerteneceElArchivo);
        $nuevoArchivo->setTipoDocumento($descripcion);
        $nuevoArchivo->setArchivo($nombreArchivoHash);
        $nuevoArchivo->setExtension($extension);
        $now = new \DateTime();
        $now->setTimezone(new \DateTimeZone('UTC'));
        $nuevoArchivo->setCreatedAt($now);
        $entityManager->persist($nuevoArchivo);
        $entityManager->flush();

        $basePath = 'C:\Users\David\Desktop\servicio\archivossias';
        $rutaFinal = $basePath . DIRECTORY_SEPARATOR . $carrera . DIRECTORY_SEPARATOR . $generacion . DIRECTORY_SEPARATOR . $numeroDeCuenta;

        // Crear directorios si no existen
        if (!is_dir($rutaFinal)) {
            if (!mkdir($rutaFinal, 0777, true) && !is_dir($rutaFinal)) {
                return new JsonResponse(['error' => 'No se pudo crear la ruta destino'], 500);
            }
        }

        try {
            $archivo->move($rutaFinal, $nombreCompletoArchivo);
            return new JsonResponse(['message' => 'status-aceptada']);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => 'cita-notfound'], 404);
        }
    }

    #[Route('/historial-alumno/listar-documentos/', name: 'listar_documentos', methods: ['POST'])]
    public function listarDocumentos(Request $request): JsonResponse
    {
        $carrera = $request->request->get('carrera');
        $generacion = $request->request->get('generacion');
        $numeroDeCuenta = $request->request->get('numeroDeCuenta');

        if (!$carrera || !$generacion || !$numeroDeCuenta) {
            return new JsonResponse(['error' => 'Faltan parámetros'], 400);
        }

        $ruta = 'C:\Users\David\Desktop\servicio\archivossias' . DIRECTORY_SEPARATOR . $carrera . DIRECTORY_SEPARATOR . $generacion . DIRECTORY_SEPARATOR . $numeroDeCuenta;

        if (!is_dir($ruta)) {
            return new JsonResponse(['archivos' => []]); // No hay archivos
        }

        $archivos = [];
        foreach (scandir($ruta) as $archivo) {
            if ($archivo === '.' || $archivo === '..') continue;
            $fullPath = $ruta . DIRECTORY_SEPARATOR . $archivo;
            $fecha = date("Y-m-d H:i:s", filemtime($fullPath));
            $archivos[] = [
                'nombre' => $archivo,
                'fecha' => $fecha,
                'url' => "/admin/historial-alumno/descargar-documento?carrera=" . urlencode($carrera) .
                    "&generacion=" . urlencode($generacion) .
                    "&numeroDeCuenta=" . urlencode($numeroDeCuenta) .
                    "&archivo=" . urlencode($archivo)
            ];
        }

        return new JsonResponse(['archivos' => $archivos]);
    }

    #[Route('/historial-alumno/descargar-documento/{id}', name: 'descargar_documento', methods: ['GET'])]
    public function descargarDocumento(int $id, Request $request, EntityManagerInterface $entityManager)
    {
        $carrera = $request->query->get('carrera');
        $generacion = $request->query->get('generacion');
        $numeroDeCuenta = $request->query->get('numeroDeCuenta');

        $documentoNombreConExtension = $entityManager->getRepository(Archivo::class)->find($id);
        
        if (!$documentoNombreConExtension) {
            return new JsonResponse(['error' => 'cita-notfound'], 404);
        }

        $ruta = 'C:\Users\David\Desktop\servicio\archivossias' . DIRECTORY_SEPARATOR .
            $carrera . DIRECTORY_SEPARATOR .
            $generacion . DIRECTORY_SEPARATOR .
            $numeroDeCuenta . DIRECTORY_SEPARATOR .
            $archivoNombre = $entityManager->getRepository(UsuarioAragon::class)->find($numeroDeCuenta);

        if (!file_exists($ruta)) {
            throw $this->createNotFoundException("Archivo no encontrado");
        }

        $response = new BinaryFileResponse($ruta);
        $response->setContentDisposition(ResponseHeaderBag::DISPOSITION_ATTACHMENT, $archivoNombre);

        dd($ruta);
        return $response;
         return new JsonResponse(['error' => 'cita-notfound'], 404);
    }
}
