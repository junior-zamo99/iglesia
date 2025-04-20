<?php
namespace Iglesia\Presentacion\Controllers;

use Iglesia\Negocio\Services\MinisterioService;
use Iglesia\Negocio\Services\MiembroService;

class ControladorMinisterio {
    private $ministerioService;
    private $miembroService;
    
    public function __construct() {
        $this->ministerioService = new MinisterioService();
        $this->miembroService = new MiembroService();
    }
    
    public function listar() {
        $ministerios = $this->ministerioService->obtenerTodos();
        $titulo = 'Lista de Ministerios';
        $vista = __DIR__ . '/../views/ministerios/lista.php';
        require_once __DIR__ . '/../views/layout/main.php';
    }
    
    public function mostrarFormulario() {
        $titulo = 'Nuevo Ministerio';
        $vista = __DIR__ . '/../views/ministerios/crear.php';
        require_once __DIR__ . '/../views/layout/main.php';
    }
    
    public function crear() {
        $nombre = $_POST['nombre'] ?? '';
        $descripcion = $_POST['descripcion'] ?? '';
        
        $errores = [];
        
        if (empty($nombre)) {
            $errores['nombre'] = 'El nombre del ministerio es obligatorio';
        }
        
        if (!empty($errores)) {
            $titulo = 'Nuevo Ministerio';
            $vista = __DIR__ . '/../views/ministerios/crear.php';
            require_once __DIR__ . '/../views/layout/main.php';
            return;
        }
        
        $resultado = $this->ministerioService->crear($nombre, $descripcion);
        
        if ($resultado) {
            header('Location: /ministerios?mensaje=creado');
            exit;
        } else {
            $error = 'No se pudo crear el ministerio';
            $titulo = 'Nuevo Ministerio';
            $vista = __DIR__ . '/../views/ministerios/crear.php';
            require_once __DIR__ . '/../views/layout/main.php';
        }
    }
    
    public function eliminar() {
        $id = $_GET['id'] ?? null;
        
        if (!$id) {
            header('Location: /ministerios?error=id_no_proporcionado');
            exit;
        }
        
        $resultado = $this->ministerioService->eliminar($id);
        
        if ($resultado) {
            header('Location: /ministerios?mensaje=eliminado');
        } else {
            header('Location: /ministerios?error=no_se_pudo_eliminar');
        }
        exit;
    }
    
    public function mostrarFormularioEdicion() {
        $id = $_GET['id'] ?? null;
        
        if (!$id) {
            header('Location: /ministerios?error=id_no_proporcionado');
            exit;
        }
        
        $ministerio = $this->ministerioService->obtenerPorId($id);
        
        if (!$ministerio) {
            header('Location: /ministerios?error=ministerio_no_encontrado');
            exit;
        }
        
        $titulo = 'Editar Ministerio';
        $vista = __DIR__ . '/../views/ministerios/editar.php';
        
        require_once __DIR__ . '/../views/layout/main.php';
    }
    
    public function actualizar() {
        $id = $_POST['id'] ?? null;
        
        if (!$id) {
            header('Location: /ministerios?error=id_no_proporcionado');
            exit;
        }
        
        $nombre = $_POST['nombre'] ?? '';
        $descripcion = $_POST['descripcion'] ?? '';
        
        $errores = [];
        
        if (empty($nombre)) {
            $errores['nombre'] = 'El nombre del ministerio es obligatorio';
        }
        
        if (!empty($errores)) {
            $ministerio = $this->ministerioService->obtenerPorId($id);
            $titulo = 'Editar Ministerio';
            $vista = __DIR__ . '/../views/ministerios/editar.php';
            require_once __DIR__ . '/../views/layout/main.php';
            return;
        }
        
        $resultado = $this->ministerioService->actualizar($id, $nombre, $descripcion);
        
        if ($resultado) {
            header('Location: /ministerios?mensaje=actualizado');
        } else {
            $error = 'No se pudo actualizar el ministerio';
            $ministerio = $this->ministerioService->obtenerPorId($id);
            $titulo = 'Editar Ministerio';
            $vista = __DIR__ . '/../views/ministerios/editar.php';
            require_once __DIR__ . '/../views/layout/main.php';
        }
    }
    
    public function ver() {
        $id = $_GET['id'] ?? null;
        
        if (!$id) {
            header('Location: /ministerios?error=id_no_proporcionado');
            exit;
        }
        
        $ministerio = $this->ministerioService->obtenerPorId($id);
        
        if (!$ministerio) {
            header('Location: /ministerios?error=ministerio_no_encontrado');
            exit;
        }
        
        // Obtener los miembros asignados a este ministerio
        $miembrosMinisterio = $this->ministerioService->obtenerMiembrosMinisterio($id);
        
        $titulo = 'Detalles de Ministerio: ' . $ministerio->getNombre();
        $vista = __DIR__ . '/../views/ministerios/ver.php';
        
        require_once __DIR__ . '/../views/layout/main.php';
    }
    
    public function mostrarFormularioAsignarMiembro() {
        $id = $_GET['id'] ?? null;
        
        if (!$id) {
            header('Location: /ministerios?error=id_no_proporcionado');
            exit;
        }
        
        $ministerio = $this->ministerioService->obtenerPorId($id);
        
        if (!$ministerio) {
            header('Location: /ministerios?error=ministerio_no_encontrado');
            exit;
        }
        
        // Obtener todos los miembros para mostrar en el formulario
        $miembros = $this->miembroService->obtenerTodos();
        
        $titulo = 'Asignar Miembro al Ministerio: ' . $ministerio->getNombre();
        $vista = __DIR__ . '/../views/ministerios/asignar_miembro.php';
        
        require_once __DIR__ . '/../views/layout/main.php';
    }
    
    public function asignarMiembro() {
        $ministerio_id = $_POST['ministerio_id'] ?? null;
        $miembro_id = $_POST['miembro_id'] ?? null;
        $fecha_inicio = $_POST['fecha_inicio'] ?? date('Y-m-d');
        
        if (!$ministerio_id || !$miembro_id) {
            header('Location: /ministerios?error=datos_incompletos');
            exit;
        }
        
        $resultado = $this->ministerioService->asignarMiembro($miembro_id, $ministerio_id, $fecha_inicio);
        
        if ($resultado) {
            header('Location: /ministerios/ver?id=' . $ministerio_id . '&mensaje=miembro_asignado');
        } else {
            header('Location: /ministerios/asignar-miembro?id=' . $ministerio_id . '&error=no_se_pudo_asignar');
        }
        exit;
    }
    
    public function removerMiembro() {
        $ministerio_id = $_POST['ministerio_id'] ?? null;
        $miembro_id = $_POST['miembro_id'] ?? null;
        
        if (!$ministerio_id || !$miembro_id) {
            header('Location: /ministerios?error=datos_incompletos');
            exit;
        }
        
        $resultado = $this->ministerioService->removerMiembro($miembro_id, $ministerio_id);
        
        if ($resultado) {
            header('Location: /ministerios/ver?id=' . $ministerio_id . '&mensaje=miembro_removido');
        } else {
            header('Location: /ministerios/ver?id=' . $ministerio_id . '&error=no_se_pudo_remover');
        }
        exit;
    }
}