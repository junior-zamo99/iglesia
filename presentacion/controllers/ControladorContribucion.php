<?php
// presentacion/controllers/ControladorContribucion.php

namespace Iglesia\Presentacion\Controllers;

use DateTime;
use Iglesia\Negocio\Services\ContribucionService;
use Iglesia\Negocio\Services\MiembroService;

class ControladorContribucion {
    private $contribucionService;
    private $miembroService;
    
    public function __construct() {
        $this->contribucionService = new ContribucionService();
        $this->miembroService = new MiembroService();
    }
    
    public function listar() {
        $filtro = $_GET['filtro'] ?? 'todas';
        $fechaInicio = $_GET['fecha_inicio'] ?? '';
        $fechaFin = $_GET['fecha_fin'] ?? '';
        
        switch ($filtro) {
            case 'mes_actual':
                $fechaInicio = date('Y-m-01');
                $fechaFin = date('Y-m-t');
                $contribuciones = $this->contribucionService->buscarContribuciones('', null, $fechaInicio, $fechaFin);
                break;
                
            case 'anio_actual':
                $fechaInicio = date('Y-01-01');
                $fechaFin = date('Y-12-31');
                $contribuciones = $this->contribucionService->buscarContribuciones('', null, $fechaInicio, $fechaFin);
                break;
                
            default:
                $contribuciones = $this->contribucionService->obtenerTodas();
                break;
        }
        
        $tiposContribucion = $this->contribucionService->obtenerTiposContribucion();
        $totalContribuciones = $this->contribucionService->obtenerTotalContribuciones($fechaInicio, $fechaFin);
        $resumenPorTipo = $this->contribucionService->obtenerResumenPorTipo($fechaInicio, $fechaFin);
        
        $titulo = 'Lista de Contribuciones';
        $vista = __DIR__ . '/../views/contribuciones/lista.php';
        
        require_once __DIR__ . '/../views/layout/main.php';
    }
    
    public function mostrarFormulario() {
        $miembros = $this->miembroService->obtenerTodos();
        $tiposContribucion = $this->contribucionService->obtenerTiposContribucion();
        
        $titulo = 'Registrar Contribución';
        $vista = __DIR__ . '/../views/contribuciones/crear.php';
        
        require_once __DIR__ . '/../views/layout/main.php';
    }
    
    public function crear() {
        $miembroId = $_POST['miembro_id'] ?? null;
        $fecha = $_POST['fecha'] ?? date('Y-m-d');
        $monto = $_POST['monto'] ?? '';
        $tipo = $_POST['tipo'] ?? '';
        $descripcion = $_POST['descripcion'] ?? '';
        
        $errores = $this->contribucionService->validarContribucion($miembroId, $fecha, $monto, $tipo);
        
        if (!empty($errores)) {
            $miembros = $this->miembroService->obtenerTodos();
            $tiposContribucion = $this->contribucionService->obtenerTiposContribucion();
            $titulo = 'Registrar Contribución';
            $vista = __DIR__ . '/../views/contribuciones/crear.php';
            require_once __DIR__ . '/../views/layout/main.php';
            return;
        }
        
        $resultado = $this->contribucionService->crear($miembroId, $fecha, $monto, $tipo, $descripcion);
        
        if ($resultado) {
            header('Location: /contribuciones?mensaje=creada');
            exit;
        } else {
            $error = 'No se pudo registrar la contribución';
            $miembros = $this->miembroService->obtenerTodos();
            $tiposContribucion = $this->contribucionService->obtenerTiposContribucion();
            $titulo = 'Registrar Contribución';
            $vista = __DIR__ . '/../views/contribuciones/crear.php';
            require_once __DIR__ . '/../views/layout/main.php';
        }
    }
    
    public function eliminar() {
        $id = $_GET['id'] ?? null;
        
        if (!$id) {
            header('Location: /contribuciones?error=id_no_proporcionado');
            exit;
        }
        
        $resultado = $this->contribucionService->eliminar($id);
        
        if ($resultado) {
            header('Location: /contribuciones?mensaje=eliminada');
        } else {
            header('Location: /contribuciones?error=no_se_pudo_eliminar');
        }
        exit;
    }
    
    public function mostrarFormularioEdicion() {
        $id = $_GET['id'] ?? null;
        
        if (!$id) {
            header('Location: /contribuciones?error=id_no_proporcionado');
            exit;
        }
        
        $contribucion = $this->contribucionService->obtenerPorId($id);
        
        if (!$contribucion) {
            header('Location: /contribuciones?error=contribucion_no_encontrada');
            exit;
        }
        
        $miembros = $this->miembroService->obtenerTodos();
        $tiposContribucion = $this->contribucionService->obtenerTiposContribucion();
        
        $titulo = 'Editar Contribución';
        $vista = __DIR__ . '/../views/contribuciones/editar.php';
        
        require_once __DIR__ . '/../views/layout/main.php';
    }
    
    public function actualizar() {
        $id = $_POST['id'] ?? null;
        
        if (!$id) {
            header('Location: /contribuciones?error=id_no_proporcionado');
            exit;
        }
        
        $miembroId = $_POST['miembro_id'] ?? null;
        $fecha = $_POST['fecha'] ?? '';
        $monto = $_POST['monto'] ?? '';
        $tipo = $_POST['tipo'] ?? '';
        $descripcion = $_POST['descripcion'] ?? '';
        
        $errores = $this->contribucionService->validarContribucion($miembroId, $fecha, $monto, $tipo);
        
        if (!empty($errores)) {
            $contribucion = $this->contribucionService->obtenerPorId($id);
            $miembros = $this->miembroService->obtenerTodos();
            $tiposContribucion = $this->contribucionService->obtenerTiposContribucion();
            $titulo = 'Editar Contribución';
            $vista = __DIR__ . '/../views/contribuciones/editar.php';
            require_once __DIR__ . '/../views/layout/main.php';
            return;
        }
        
        $resultado = $this->contribucionService->actualizar($id, $miembroId, $fecha, $monto, $tipo, $descripcion);
        
        if ($resultado) {
            header('Location: /contribuciones?mensaje=actualizada');
        } else {
            $error = 'No se pudo actualizar la contribución';
            $contribucion = $this->contribucionService->obtenerPorId($id);
            $miembros = $this->miembroService->obtenerTodos();
            $tiposContribucion = $this->contribucionService->obtenerTiposContribucion();
            $titulo = 'Editar Contribución';
            $vista = __DIR__ . '/../views/contribuciones/editar.php';
            require_once __DIR__ . '/../views/layout/main.php';
        }
    }
    
    public function ver() {
        $id = $_GET['id'] ?? null;
        
        if (!$id) {
            header('Location: /contribuciones?error=id_no_proporcionado');
            exit;
        }
        
        $contribucion = $this->contribucionService->obtenerPorId($id);
        
        if (!$contribucion) {
            header('Location: /contribuciones?error=contribucion_no_encontrada');
            exit;
        }
        
        // Obtener información del miembro
        $miembro = $this->miembroService->obtenerPorId($contribucion->getMiembroId());
        
        $titulo = 'Detalles de Contribución';
        $vista = __DIR__ . '/../views/contribuciones/ver.php';
        
        require_once __DIR__ . '/../views/layout/main.php';
    }
    
    public function buscar() {
        $termino = $_GET['termino'] ?? '';
        $tipo = $_GET['tipo'] ?? '';
        $fechaInicio = $_GET['fecha_inicio'] ?? '';
        $fechaFin = $_GET['fecha_fin'] ?? '';
        
        $contribuciones = $this->contribucionService->buscarContribuciones($termino, $tipo, $fechaInicio, $fechaFin);
        $tiposContribucion = $this->contribucionService->obtenerTiposContribucion();
        $totalContribuciones = $this->contribucionService->obtenerTotalContribuciones($fechaInicio, $fechaFin);
        
        $titulo = 'Resultados de Búsqueda de Contribuciones';
        $vista = __DIR__ . '/../views/contribuciones/busqueda.php';
        
        require_once __DIR__ . '/../views/layout/main.php';
    }
}