<?php
// presentacion/controllers/ControladorEvento.php

namespace Iglesia\Presentacion\Controllers;

use DateTime;
use Iglesia\Negocio\Services\EventoService;
use Iglesia\Negocio\Services\MiembroService;

class ControladorEvento {
    private $eventoService;
    private $miembroService;
    
    public function __construct() {
        $this->eventoService = new EventoService();
        $this->miembroService = new MiembroService();
    }
    
    public function listar() {
        $filtro = $_GET['filtro'] ?? 'todos';
        
        switch ($filtro) {
            case 'proximos':
                $eventos = $this->eventoService->obtenerProximosEventos(20);
                break;
                
            case 'pasados':
                $eventos = $this->eventoService->obtenerEventosPasados(20);
                break;
                
            default:
                $eventos = $this->eventoService->obtenerTodos();
                break;
        }
        
        $tiposEvento = $this->eventoService->obtenerTiposEvento();
        
        $titulo = 'Lista de Eventos';
        $vista = __DIR__ . '/../views/eventos/lista.php';
        require_once __DIR__ . '/../views/layout/main.php';
    }
    
    public function mostrarFormulario() {
        $tiposEvento = $this->eventoService->obtenerTiposEvento();
        
        $titulo = 'Nuevo Evento';
        $vista = __DIR__ . '/../views/eventos/crear.php';
        
        require_once __DIR__ . '/../views/layout/main.php';
    }
    
    public function crear() {
        $nombre = $_POST['nombre'] ?? '';
        $fecha = $_POST['fecha'] ?? '';
        $ubicacion = $_POST['ubicacion'] ?? '';
        $descripcion = $_POST['descripcion'] ?? '';
        $tipo = $_POST['tipo'] ?? '';

        if($tipo === 'otro') {
            $tipo = $_POST['nuevo_tipo'] ?? '';
        }
        if (empty($tipo)) {
            $tipo = 'Otro';
        }
        
        $errores = $this->eventoService->validarEvento($nombre, $fecha);
        
        if (!empty($errores)) {
            $tiposEvento = $this->eventoService->obtenerTiposEvento();
            $titulo = 'Nuevo Evento';
            $vista = __DIR__ . '/../views/eventos/crear.php';
            require_once __DIR__ . '/../views/layout/main.php';
            return;
        }
        
        $resultado = $this->eventoService->crear($nombre, $fecha, $ubicacion, $descripcion, $tipo);
        
        if ($resultado) {
            header('Location: /eventos?mensaje=creado');
            exit;
        } else {
            $error = 'No se pudo crear el evento';
            $tiposEvento = $this->eventoService->obtenerTiposEvento();
            $titulo = 'Nuevo Evento';
            $vista = __DIR__ . '/../views/eventos/crear.php';
            require_once __DIR__ . '/../views/layout/main.php';
        }
    }
    
    public function eliminar() {
        $id = $_GET['id'] ?? null;
        
        if (!$id) {
            header('Location: /eventos?error=id_no_proporcionado');
            exit;
        }
        
        $resultado = $this->eventoService->eliminar($id);
        
        if ($resultado) {
            header('Location: /eventos?mensaje=eliminado');
        } else {
            header('Location: /eventos?error=no_se_pudo_eliminar');
        }
        exit;
    }
    
    public function mostrarFormularioEdicion() {
        $id = $_GET['id'] ?? null;
        
        if (!$id) {
            header('Location: /eventos?error=id_no_proporcionado');
            exit;
        }
        
        $evento = $this->eventoService->obtenerPorId($id);
        
        if (!$evento) {
            header('Location: /eventos?error=evento_no_encontrado');
            exit;
        }
        
        $tiposEvento = $this->eventoService->obtenerTiposEvento();
        
        $titulo = 'Editar Evento';
        $vista = __DIR__ . '/../views/eventos/editar.php';
        
        require_once __DIR__ . '/../views/layout/main.php';
    }
    
    public function actualizar() {
        $id = $_POST['id'] ?? null;
        
        if (!$id) {
            header('Location: /eventos?error=id_no_proporcionado');
            exit;
        }
        
        $nombre = $_POST['nombre'] ?? '';
        $fecha = $_POST['fecha'] ?? '';
        $ubicacion = $_POST['ubicacion'] ?? '';
        $descripcion = $_POST['descripcion'] ?? '';
        $tipo = $_POST['tipo'] ?? '';
        
        $errores = $this->eventoService->validarEvento($nombre, $fecha);
        
        if (!empty($errores)) {
            $evento = $this->eventoService->obtenerPorId($id);
            $tiposEvento = $this->eventoService->obtenerTiposEvento();
            $titulo = 'Editar Evento';
            $vista = __DIR__ . '/../views/eventos/editar.php';
            require_once __DIR__ . '/../views/layout/main.php';
            return;
        }
        
        $resultado = $this->eventoService->actualizar($id, $nombre, $fecha, $ubicacion, $descripcion, $tipo);
        
        if ($resultado) {
            header('Location: /eventos?mensaje=actualizado');
        } else {
            $error = 'No se pudo actualizar el evento';
            $evento = $this->eventoService->obtenerPorId($id);
            $tiposEvento = $this->eventoService->obtenerTiposEvento();
            $titulo = 'Editar Evento';
            $vista = __DIR__ . '/../views/eventos/editar.php';
            require_once __DIR__ . '/../views/layout/main.php';
        }
    }
    
    public function ver() {
        $id = $_GET['id'] ?? null;
        
        if (!$id) {
            header('Location: /eventos?error=id_no_proporcionado');
            exit;
        }
        
        $evento = $this->eventoService->obtenerPorId($id);
        
        if (!$evento) {
            header('Location: /eventos?error=evento_no_encontrado');
            exit;
        }
        
        // Obtener los miembros asignados a este evento
        $miembrosEvento = $this->eventoService->obtenerMiembrosEvento($id);
        
        $titulo = 'Detalles de Evento: ' . $evento->getNombre();
        $vista = __DIR__ . '/../views/eventos/ver.php';
        
        require_once __DIR__ . '/../views/layout/main.php';
    }
    
    public function mostrarFormularioAsistencia() {
        $id = $_GET['id'] ?? null;
        
        if (!$id) {
            header('Location: /eventos?error=id_no_proporcionado');
            exit;
        }
        
        $evento = $this->eventoService->obtenerPorId($id);
        
        if (!$evento) {
            header('Location: /eventos?error=evento_no_encontrado');
            exit;
        }
        
       
        $miembros = $this->miembroService->obtenerTodos();
        $miembrosEvento = $this->eventoService->obtenerMiembrosEvento($id);
        
        $miembrosEventoMap = [];
        foreach ($miembrosEvento as $miembroEvento) {
            $miembrosEventoMap[$miembroEvento['id']] = $miembroEvento['asistencia'];
        }
        
        $titulo = 'Registrar Asistencia: ' . $evento->getNombre();
        $vista = __DIR__ . '/../views/eventos/asistencia.php';
        
        require_once __DIR__ . '/../views/layout/main.php';
    }
    
    public function registrarAsistencia() {
        $eventoId = $_POST['evento_id'] ?? null;
        $asistencias = $_POST['asistencia'] ?? [];
        
        if (!$eventoId) {
            header('Location: /eventos?error=datos_incompletos');
            exit;
        }
        
        $exitoso = true;
        $miembros = $this->miembroService->obtenerTodos();
        foreach ($miembros as $miembro) {
            $miembroId = $miembro->getId();
            $asistio = isset($asistencias[$miembroId]) ? true : false;
            
            $resultado = $this->eventoService->registrarAsistencia($miembroId, $eventoId, $asistio);
            
            if (!$resultado) {
                $exitoso = false;
            }
        }
        
        if ($exitoso) {
            header('Location: /eventos/ver?id=' . $eventoId . '&mensaje=asistencia_registrada');
        } else {
            header('Location: /eventos/asistencia?id=' . $eventoId . '&error=no_se_pudo_registrar');
        }
        exit;
    }
    
    public function eliminarAsistencia() {
        $eventoId = $_POST['evento_id'] ?? null;
        $miembroId = $_POST['miembro_id'] ?? null;
        
        if (!$eventoId || !$miembroId) {
            header('Location: /eventos?error=datos_incompletos');
            exit;
        }
        
        $resultado = $this->eventoService->eliminarAsistencia($miembroId, $eventoId);
        
        if ($resultado) {
            header('Location: /eventos/ver?id=' . $eventoId . '&mensaje=asistencia_eliminada');
        } else {
            header('Location: /eventos/ver?id=' . $eventoId . '&error=no_se_pudo_eliminar_asistencia');
        }
        exit;
    }
    
    public function buscar() {
        $termino = $_GET['termino'] ?? '';
        $tipo = $_GET['tipo'] ?? '';
        $fechaInicio = $_GET['fecha_inicio'] ?? '';
        $fechaFin = $_GET['fecha_fin'] ?? '';
        
        $eventos = $this->eventoService->buscarEventos($termino, $tipo, $fechaInicio, $fechaFin);
        $tiposEvento = $this->eventoService->obtenerTiposEvento();
        
        $titulo = 'Resultados de Búsqueda';
        $vista = __DIR__ . '/../views/eventos/busqueda.php';
        
        require_once __DIR__ . '/../views/layout/main.php';
    }
}