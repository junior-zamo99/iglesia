<?php
// negocio/services/BautizoService.php

namespace Iglesia\Negocio\Services;

use Iglesia\Datos\Repositories\BautizoRepository;
use Iglesia\Negocio\Entidades\Bautizo;
use DateTime;

class BautizoService {
    private $bautizoRepository;
    
    public function __construct() {
        $this->bautizoRepository = new BautizoRepository();
    }
    
    public function obtenerTodos() {
        $bautizosData = $this->bautizoRepository->findAll();
        $bautizos = [];
        
        foreach ($bautizosData as $data) {
            $bautizo = $this->crearBautizoDesdeArray($data);
            $bautizo->nombreMiembro = $data['nombre'] . ' ' . $data['apellido'];
            $bautizos[] = $bautizo;
        }
        
        return $bautizos;
    }
    
    public function obtenerPorId($id) {
        $data = $this->bautizoRepository->findById($id);
        
        if (!$data) {
            return null;
        }
        
        $bautizo = $this->crearBautizoDesdeArray($data);
        $bautizo->nombreMiembro = $data['nombre'] . ' ' . $data['apellido'];
        return $bautizo;
    }
    
    public function obtenerPorMiembroId($miembroId) {
        $data = $this->bautizoRepository->findByMiembroId($miembroId);
        
        if (!$data) {
            return null;
        }
        
        return $this->crearBautizoDesdeArray($data);
    }
    
    public function crear($miembroId, $fecha, $lugar = '', $pastor = '') {
        // Verificar si el miembro ya tiene un bautizo registrado
        $bautizoExistente = $this->obtenerPorMiembroId($miembroId);
        if ($bautizoExistente) {
            return false; // Un miembro solo puede tener un bautizo
        }
        
        $bautizo = new Bautizo(null, $miembroId, $fecha, $lugar, $pastor);
        return $this->bautizoRepository->save($bautizo);
    }
    
    public function actualizar($id, $miembroId, $fecha, $lugar = '', $pastor = '') {
        // Verificar si estamos cambiando el miembro y si el nuevo miembro ya tiene un bautizo
        $bautizoActual = $this->obtenerPorId($id);
        if ($bautizoActual->getMiembroId() != $miembroId) {
            $bautizoExistente = $this->obtenerPorMiembroId($miembroId);
            if ($bautizoExistente) {
                return false; // El nuevo miembro ya tiene un bautizo registrado
            }
        }
        
        $bautizo = new Bautizo($id, $miembroId, $fecha, $lugar, $pastor);
        return $this->bautizoRepository->update($bautizo);
    }
    
    public function eliminar($id) {
        return $this->bautizoRepository->delete($id);
    }
    
    public function buscarBautizos($termino = '', $fechaInicio = null, $fechaFin = null) {
        $bautizosData = $this->bautizoRepository->buscarBautizos($termino, $fechaInicio, $fechaFin);
        $bautizos = [];
        
        foreach ($bautizosData as $data) {
            $bautizo = $this->crearBautizoDesdeArray($data);
            $bautizo->nombreMiembro = $data['nombre'] . ' ' . $data['apellido'];
            $bautizos[] = $bautizo;
        }
        
        return $bautizos;
    }
    
    private function crearBautizoDesdeArray($data) {
        $bautizo = new Bautizo(
            $data['id'],
            $data['miembro_id'],
            $data['fecha'],
            $data['lugar'],
            $data['pastor']
        );
        
        // Si hay datos adicionales de miembro disponibles, agregamos una propiedad
        if (isset($data['nombre']) && isset($data['apellido'])) {
            $bautizo->nombreMiembro = $data['nombre'] . ' ' . $data['apellido'];
        }
        
        return $bautizo;
    }
    
    public function validarBautizo($miembroId, $fecha) {
        $errores = [];
        
        if (empty($miembroId)) {
            $errores['miembro_id'] = 'Debe seleccionar un miembro';
        }
        
        if (empty($fecha)) {
            $errores['fecha'] = 'La fecha del bautizo es obligatoria';
        } else {
            $fechaObj = DateTime::createFromFormat('Y-m-d', $fecha);
            if (!$fechaObj || $fechaObj->format('Y-m-d') !== $fecha) {
                $errores['fecha'] = 'El formato de fecha debe ser YYYY-MM-DD';
            }
        }
        
        return $errores;
    }
}