<?php
// presentacion/controllers/ControladorBautizo.php

namespace Iglesia\Presentacion\Controllers;

use DateTime;
use Iglesia\Negocio\Services\BautizoService;
use Iglesia\Negocio\Services\MiembroService;

class ControladorBautizo {
    private $bautizoService;
    private $miembroService;
    
    public function __construct() {
        $this->bautizoService = new BautizoService();
        $this->miembroService = new MiembroService();
    }
    
    public function listar() {
        $bautizos = $this->bautizoService->obtenerTodos();
        
        $titulo = 'Lista de Bautizos';
        $vista = __DIR__ . '/../views/bautizos/lista.php';
        require_once __DIR__ . '/../views/layout/main.php';
    }
    
    public function mostrarFormulario() {
        $miembros = $this->miembroService->obtenerTodos();
        
        // Filtrar miembros que no están bautizados
        $miembrosFiltrados = [];
        foreach ($miembros as $miembro) {
            $bautizo = $this->bautizoService->obtenerPorMiembroId($miembro->getId());
            if (!$bautizo) {
                $miembrosFiltrados[] = $miembro;
            }
        }
        
        $miembros = $miembrosFiltrados;
        
        $titulo = 'Nuevo Bautizo';
        $vista = __DIR__ . '/../views/bautizos/crear.php';
        
        require_once __DIR__ . '/../views/layout/main.php';
    }
    
    public function crear() {
        $miembroId = $_POST['miembro_id'] ?? null;
        $fecha = $_POST['fecha'] ?? '';
        $lugar = $_POST['lugar'] ?? '';
        $pastor = $_POST['pastor'] ?? '';
        
        $errores = $this->bautizoService->validarBautizo($miembroId, $fecha);
        
        if (!empty($errores)) {
            $miembros = $this->miembroService->obtenerTodos();
            
            // Filtrar miembros que no están bautizados
            $miembrosFiltrados = [];
            foreach ($miembros as $miembro) {
                $bautizo = $this->bautizoService->obtenerPorMiembroId($miembro->getId());
                if (!$bautizo) {
                    $miembrosFiltrados[] = $miembro;
                }
            }
            
            $miembros = $miembrosFiltrados;
            
            $titulo = 'Nuevo Bautizo';
            $vista = __DIR__ . '/../views/bautizos/crear.php';
            require_once __DIR__ . '/../views/layout/main.php';
            return;
        }
        
        $resultado = $this->bautizoService->crear($miembroId, $fecha, $lugar, $pastor);
        
        if ($resultado) {
            header('Location: /bautizos?mensaje=creado');
            exit;
        } else {
            $error = 'No se pudo registrar el bautizo. Es posible que este miembro ya esté bautizado.';
            
            $miembros = $this->miembroService->obtenerTodos();
            
            // Filtrar miembros que no están bautizados
            $miembrosFiltrados = [];
            foreach ($miembros as $miembro) {
                $bautizo = $this->bautizoService->obtenerPorMiembroId($miembro->getId());
                if (!$bautizo) {
                    $miembrosFiltrados[] = $miembro;
                }
            }
            
            $miembros = $miembrosFiltrados;
            
            $titulo = 'Nuevo Bautizo';
            $vista = __DIR__ . '/../views/bautizos/crear.php';
            require_once __DIR__ . '/../views/layout/main.php';
        }
    }
    
    public function eliminar() {
        $id = $_GET['id'] ?? null;
        
        if (!$id) {
            header('Location: /bautizos?error=id_no_proporcionado');
            exit;
        }
        
        $resultado = $this->bautizoService->eliminar($id);
        
        if ($resultado) {
            header('Location: /bautizos?mensaje=eliminado');
        } else {
            header('Location: /bautizos?error=no_se_pudo_eliminar');
        }
        exit;
    }
    
    public function mostrarFormularioEdicion() {
        $id = $_GET['id'] ?? null;
        
        if (!$id) {
            header('Location: /bautizos?error=id_no_proporcionado');
            exit;
        }
        
        $bautizo = $this->bautizoService->obtenerPorId($id);
        
        if (!$bautizo) {
            header('Location: /bautizos?error=bautizo_no_encontrado');
            exit;
        }
        
        $miembros = $this->miembroService->obtenerTodos();
        
        $titulo = 'Editar Bautizo';
        $vista = __DIR__ . '/../views/bautizos/editar.php';
        
        require_once __DIR__ . '/../views/layout/main.php';
    }
    
    public function actualizar() {
        $id = $_POST['id'] ?? null;
        
        if (!$id) {
            header('Location: /bautizos?error=id_no_proporcionado');
            exit;
        }
        
        $miembroId = $_POST['miembro_id'] ?? null;
        $fecha = $_POST['fecha'] ?? '';
        $lugar = $_POST['lugar'] ?? '';
        $pastor = $_POST['pastor'] ?? '';
        
        $errores = $this->bautizoService->validarBautizo($miembroId, $fecha);
        
        if (!empty($errores)) {
            $bautizo = $this->bautizoService->obtenerPorId($id);
            $miembros = $this->miembroService->obtenerTodos();
            $titulo = 'Editar Bautizo';
            $vista = __DIR__ . '/../views/bautizos/editar.php';
            require_once __DIR__ . '/../views/layout/main.php';
            return;
        }
        
        $resultado = $this->bautizoService->actualizar($id, $miembroId, $fecha, $lugar, $pastor);
        
        if ($resultado) {
            header('Location: /bautizos?mensaje=actualizado');
        } else {
            $error = 'No se pudo actualizar el bautizo. Es posible que el miembro seleccionado ya esté bautizado.';
            $bautizo = $this->bautizoService->obtenerPorId($id);
            $miembros = $this->miembroService->obtenerTodos();
            $titulo = 'Editar Bautizo';
            $vista = __DIR__ . '/../views/bautizos/editar.php';
            require_once __DIR__ . '/../views/layout/main.php';
        }
    }
    
    public function ver() {
        $id = $_GET['id'] ?? null;
        
        if (!$id) {
            header('Location: /bautizos?error=id_no_proporcionado');
            exit;
        }
        
        $bautizo = $this->bautizoService->obtenerPorId($id);
        
        if (!$bautizo) {
            header('Location: /bautizos?error=bautizo_no_encontrado');
            exit;
        }
        
        // Obtener información del miembro
        $miembro = $this->miembroService->obtenerPorId($bautizo->getMiembroId());
        
        $titulo = 'Detalles de Bautizo';
        $vista = __DIR__ . '/../views/bautizos/ver.php';
        
        require_once __DIR__ . '/../views/layout/main.php';
    }
    
    public function buscar() {
        $termino = $_GET['termino'] ?? '';
        $fechaInicio = $_GET['fecha_inicio'] ?? '';
        $fechaFin = $_GET['fecha_fin'] ?? '';
        
        $bautizos = $this->bautizoService->buscarBautizos($termino, $fechaInicio, $fechaFin);
        
        $titulo = 'Resultados de Búsqueda de Bautizos';
        $vista = __DIR__ . '/../views/bautizos/busqueda.php';
        
        require_once __DIR__ . '/../views/layout/main.php';
    }
}