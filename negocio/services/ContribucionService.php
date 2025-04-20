<?php
// negocio/services/ContribucionService.php

namespace Iglesia\Negocio\Services;

use Iglesia\Datos\Repositories\ContribucionRepository;
use Iglesia\Negocio\Entidades\Contribucion;
use DateTime;

class ContribucionService {
    private $contribucionRepository;
    
    public function __construct() {
        $this->contribucionRepository = new ContribucionRepository();
    }
    
    public function obtenerTodas() {
        $contribucionesData = $this->contribucionRepository->findAll();
        $contribuciones = [];
        
        foreach ($contribucionesData as $data) {
            $contribucion = $this->crearContribucionDesdeArray($data);
            $contribucion->nombreMiembro = $data['nombre'] . ' ' . $data['apellido'];
            $contribuciones[] = $contribucion;
        }
        
        return $contribuciones;
    }
    
    public function obtenerPorId($id) {
        $data = $this->contribucionRepository->findById($id);
        
        if (!$data) {
            return null;
        }
        
        $contribucion = $this->crearContribucionDesdeArray($data);
        $contribucion->nombreMiembro = $data['nombre'] . ' ' . $data['apellido'];
        return $contribucion;
    }
    
    public function obtenerPorMiembroId($miembroId) {
        $contribucionesData = $this->contribucionRepository->findByMiembroId($miembroId);
        $contribuciones = [];
        
        foreach ($contribucionesData as $data) {
            $contribucion = $this->crearContribucionDesdeArray($data);
            $contribucion->nombreMiembro = $data['nombre'] . ' ' . $data['apellido'];
            $contribuciones[] = $contribucion;
        }
        
        return $contribuciones;
    }
    
    public function crear($miembroId, $fecha, $monto, $tipo, $descripcion = '') {
        // Validar que el monto sea un número positivo
        if (!is_numeric($monto) || $monto <= 0) {
            return false;
        }
        
        $contribucion = new Contribucion(null, $miembroId, $fecha, $monto, $tipo, $descripcion);
        return $this->contribucionRepository->save($contribucion);
    }
    
    public function actualizar($id, $miembroId, $fecha, $monto, $tipo, $descripcion = '') {
        // Validar que el monto sea un número positivo
        if (!is_numeric($monto) || $monto <= 0) {
            return false;
        }
        
        $contribucion = new Contribucion($id, $miembroId, $fecha, $monto, $tipo, $descripcion);
        return $this->contribucionRepository->update($contribucion);
    }
    
    public function eliminar($id) {
        return $this->contribucionRepository->delete($id);
    }
    
    public function buscarContribuciones($termino = '', $tipo = null, $fechaInicio = null, $fechaFin = null) {
        $contribucionesData = $this->contribucionRepository->buscarContribuciones($termino, $tipo, $fechaInicio, $fechaFin);
        $contribuciones = [];
        
        foreach ($contribucionesData as $data) {
            $contribucion = $this->crearContribucionDesdeArray($data);
            $contribucion->nombreMiembro = $data['nombre'] . ' ' . $data['apellido'];
            $contribuciones[] = $contribucion;
        }
        
        return $contribuciones;
    }
    
    public function obtenerTiposContribucion() {
        return $this->contribucionRepository->getTiposContribucion();
    }
    
    public function obtenerResumenPorTipo($fechaInicio = null, $fechaFin = null) {
        return $this->contribucionRepository->getResumenPorTipo($fechaInicio, $fechaFin);
    }
    
    public function obtenerResumenPorMes($anio = null) {
        if (!$anio) {
            $anio = date('Y');
        }
        return $this->contribucionRepository->getResumenPorMes($anio);
    }
    
    public function obtenerTotalContribuciones($fechaInicio = null, $fechaFin = null) {
        return $this->contribucionRepository->getTotalContribuciones($fechaInicio, $fechaFin);
    }
    
    private function crearContribucionDesdeArray($data) {
        return new Contribucion(
            $data['id'],
            $data['miembro_id'],
            $data['fecha'],
            $data['monto'],
            $data['tipo'],
            $data['descripcion'] ?? ''
        );
    }
    
    public function validarContribucion($miembroId, $fecha, $monto, $tipo) {
        $errores = [];
        
        if (empty($miembroId)) {
            $errores['miembro_id'] = 'Debe seleccionar un miembro';
        }
        
        if (empty($fecha)) {
            $errores['fecha'] = 'La fecha de la contribución es obligatoria';
        } else {
            $fechaObj = DateTime::createFromFormat('Y-m-d', $fecha);
            if (!$fechaObj || $fechaObj->format('Y-m-d') !== $fecha) {
                $errores['fecha'] = 'El formato de fecha debe ser YYYY-MM-DD';
            }
        }
        
        if (empty($monto)) {
            $errores['monto'] = 'El monto de la contribución es obligatorio';
        } else if (!is_numeric($monto) || $monto <= 0) {
            $errores['monto'] = 'El monto debe ser un número positivo';
        }
        
        if (empty($tipo)) {
            $errores['tipo'] = 'El tipo de contribución es obligatorio';
        }
        
        return $errores;
    }
}