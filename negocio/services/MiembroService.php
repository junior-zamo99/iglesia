<?php

namespace Iglesia\Negocio\Services;

use Iglesia\Datos\Repositories\MiembroRepository;
use Iglesia\Negocio\Entidades\Miembro;

class MiembroService {
    private $miembroRepository;
    
    public function __construct() {
        $this->miembroRepository = new MiembroRepository();
    }
    
    public function obtenerTodos() {
        $miembrosDatos = $this->miembroRepository->findAll();
        
        $miembros = [];
        foreach ($miembrosDatos as $datos) {
            $miembro = new Miembro();
            $miembro->setId($datos['id']);
            $miembro->setNombre($datos['nombre']);
            $miembro->setApellido($datos['apellido']);
            $miembro->setEmail($datos['email']);
            $miembro->setEstadoCivil($datos['estado_civil']);
            $miembro->setTelefono($datos['telefono']);
            $miembro->setDireccion($datos['direccion']);
            $miembro->setFechaNacimiento($datos['fecha_nacimiento']);
            $miembros[] = $miembro;
        }
        
        return $miembros;
    }
    
    public function crear($nombre, $email, $apellido, $estado_civil, $telefono, $direccion, $fecha_nacimiento) {
    
        $miembro = new Miembro();
        $miembro->setNombre($nombre);
        $miembro->setEmail($email);
        $miembro->setEstadoCivil($estado_civil);
        $miembro->setTelefono($telefono);
        $miembro->setDireccion($direccion);
        $miembro->setFechaNacimiento($fecha_nacimiento);
        $miembro->setApellido($apellido);
        
 
        return $this->miembroRepository->save($miembro);
    }


    public function eliminar($id) {
        return $this->miembroRepository->delete($id);
    }

    public function obtenerPorId($id) {
        $datos = $this->miembroRepository->findById($id);
    
        if (!$datos) {
            return null;
        }
        
        $miembro = new Miembro();
        $miembro->setId($datos['id']);
        $miembro->setNombre($datos['nombre']);
        $miembro->setApellido($datos['apellido']);
        $miembro->setEmail($datos['email']);
        $miembro->setEstadoCivil($datos['estado_civil']);
        $miembro->setTelefono($datos['telefono']);
        $miembro->setDireccion($datos['direccion']);
        $miembro->setFechaNacimiento($datos['fecha_nacimiento']);
        
        return $miembro;
    }


    public function actualizar($id, $nombre, $apellido, $email, $estado_civil, $telefono, $direccion, $fecha_nacimiento) {
        $miembro = new Miembro();
        $miembro->setId($id);
        $miembro->setNombre($nombre);
        $miembro->setApellido($apellido);
        $miembro->setEmail($email);
        $miembro->setEstadoCivil($estado_civil);
        $miembro->setTelefono($telefono);
        $miembro->setDireccion($direccion);
        $miembro->setFechaNacimiento($fecha_nacimiento);
        
        return $this->miembroRepository->update($miembro);
    }


}