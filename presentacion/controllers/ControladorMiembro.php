<?php
namespace Iglesia\Presentacion\Controllers;
use DateTime;
use Iglesia\Negocio\Services\MiembroService;

class ControladorMiembro {
    private $miembroService;
    
    public function __construct() {
        $this->miembroService = new MiembroService();
    }
    
    public function listar() {
       
        $miembros = $this->miembroService->obtenerTodos();
        $titulo = 'Lista de Miembros';
        $vista = __DIR__ . '/../views/miembros/lista.php';
        require_once __DIR__ . '/../views/layout/main.php';
    }
    
    public function mostrarFormulario() {

        $titulo = 'Nuevo Miembro';
        $vista = __DIR__ . '/../views/miembros/crear.php';
        
        require_once __DIR__ . '/../views/layout/main.php';
    }
    
    public function crear() {
        $nombre = $_POST['nombre'] ?? '';
        $apellido=$_POST['apellido']?? '';
        $email = $_POST['email'] ?? '';
        $estado_civil=$_POST['estado_civil']??'';
        $telefono = $_POST['telefono'] ?? '';
        $direccion = $_POST['direccion'] ?? '';
        $fecha_nacimiento = $_POST['fecha_nacimiento'] ?? '';

        $errores = [];
        
        if (empty($nombre)) {
            $errores['nombre'] = 'El nombre es obligatorio';
        }
        
        if (empty($email)) {
            $errores['email'] = 'El email es obligatorio';
        }

        if (empty($fecha_nacimiento)) {
            $fecha_nacimiento = null; 
        } else {
            $fecha_obj = DateTime::createFromFormat('Y-m-d', $fecha_nacimiento);
            if ($fecha_obj) {
                $fecha_nacimiento = $fecha_obj->format('Y-m-d');
            } else {
                $errores['fecha_nacimiento'] = 'Formato de fecha incorrecto. Use YYYY-MM-DD';
                $fecha_nacimiento = null;
            }
        }
        

        
        if (!empty($errores)) {
            include __DIR__ . '/../views/miembros/crear.php';
            return;
        }
        
        $resultado = $this->miembroService->crear($nombre, $email,$apellido,$estado_civil,$telefono,$direccion,$fecha_nacimiento);
        
        if ($resultado) {

            header('Location: /miembros?mensaje=creado');
            exit;
        } else {

            $error = 'No se pudo crear el miembro';
            include __DIR__ . '/../views/miembros/crear.php';
        }
    }

    public function eliminar() {
        $id = $_GET['id'] ?? null;
        
        if (!$id) {
            header('Location: /miembros?error=id_no_proporcionado');
            exit;
        }
        
        $resultado = $this->miembroService->eliminar($id);
        
        if ($resultado) {
            header('Location: /miembros?mensaje=eliminado');
        } else {
            header('Location: /miembros?error=no_se_pudo_eliminar');
        }
        exit;
    }

    public function mostrarFormularioEdicion() {
        $id = $_GET['id'] ?? null;
        
        if (!$id) {
            header('Location: /miembros?error=id_no_proporcionado');
            exit;
        }
        
        $miembro = $this->miembroService->obtenerPorId($id);
        
        if (!$miembro) {
            header('Location: /miembros?error=miembro_no_encontrado');
            exit;
        }
        
        $titulo = 'Editar Miembro';
        $vista = __DIR__ . '/../views/miembros/editar.php';
        
        require_once __DIR__ . '/../views/layout/main.php';
    }
    
    public function actualizar() {
        $id = $_POST['id'] ?? null;
        
        if (!$id) {
            header('Location: /miembros?error=id_no_proporcionado');
            exit;
        }
        
        $nombre = $_POST['nombre'] ?? '';
        $apellido = $_POST['apellido'] ?? '';
        $email = $_POST['email'] ?? '';
        $estado_civil = $_POST['estado_civil'] ?? '';
        $telefono = $_POST['telefono'] ?? '';
        $direccion = $_POST['direccion'] ?? '';
        $fecha_nacimiento = $_POST['fecha_nacimiento'] ?? '';
        
        $errores = [];
        
        if (empty($nombre)) {
            $errores['nombre'] = 'El nombre es obligatorio';
        }
        
        if (empty($email)) {
            $errores['email'] = 'El email es obligatorio';
        }
        
        if (empty($fecha_nacimiento)) {
            $fecha_nacimiento = null; 
        } else {
            $fecha_obj = DateTime::createFromFormat('Y-m-d', $fecha_nacimiento);
            if ($fecha_obj) {
                $fecha_nacimiento = $fecha_obj->format('Y-m-d');
            } else {
                $errores['fecha_nacimiento'] = 'Formato de fecha incorrecto. Use YYYY-MM-DD';
                $fecha_nacimiento = null;
            }
        }
        
        if (!empty($errores)) {
            $miembro = $this->miembroService->obtenerPorId($id);
            $titulo = 'Editar Miembro';
            $vista = __DIR__ . '/../views/miembros/editar.php';
            require_once __DIR__ . '/../views/layout/main.php';
            return;
        }
        
        $resultado = $this->miembroService->actualizar($id, $nombre, $apellido, $email, $estado_civil, $telefono, $direccion, $fecha_nacimiento);
        
        if ($resultado) {
            header('Location: /miembros?mensaje=actualizado');
        } else {
            $error = 'No se pudo actualizar el miembro';
            $miembro = $this->miembroService->obtenerPorId($id);
            $titulo = 'Editar Miembro';
            $vista = __DIR__ . '/../views/miembros/editar.php';
            require_once __DIR__ . '/../views/layout/main.php';
        }
    }

}