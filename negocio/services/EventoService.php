<?php
// negocio/services/EventoService.php

namespace Iglesia\Negocio\Services;

use Iglesia\Datos\Repositories\EventoRepository;
use Iglesia\Negocio\Entidades\Evento;
use DateTime;

class EventoService {
    private $eventoRepository;
    
    public function __construct() {
        $this->eventoRepository = new EventoRepository();
    }
    
    public function obtenerTodos() {
        $eventosData = $this->eventoRepository->findAll();
        $eventos = [];
        
        foreach ($eventosData as $data) {
            $eventos[] = $this->crearEventoDesdeArray($data);
        }
        
        return $eventos;
    }
    
    public function obtenerPorId($id) {
        $data = $this->eventoRepository->findById($id);
        
        if (!$data) {
            return null;
        }
        
        return $this->crearEventoDesdeArray($data);
    }
    
    public function crear($nombre, $fecha, $ubicacion = '', $descripcion = '', $tipo = '') {
        $evento = new Evento(null, $nombre, $fecha, $ubicacion, $descripcion, $tipo);
        return $this->eventoRepository->save($evento);
    }
    
    public function actualizar($id, $nombre, $fecha, $ubicacion = '', $descripcion = '', $tipo = '') {
        $evento = new Evento($id, $nombre, $fecha, $ubicacion, $descripcion, $tipo);
        return $this->eventoRepository->update($evento);
    }
    
    public function eliminar($id) {
        return $this->eventoRepository->delete($id);
    }
    
    public function obtenerProximosEventos($limite = 5) {
        $eventosData = $this->eventoRepository->getProximosEventos($limite);
        $eventos = [];
        
        foreach ($eventosData as $data) {
            $eventos[] = $this->crearEventoDesdeArray($data);
        }
        
        return $eventos;
    }
    
    public function obtenerEventosPasados($limite = 10) {
        $eventosData = $this->eventoRepository->getEventosPasados($limite);
        $eventos = [];
        
        foreach ($eventosData as $data) {
            $eventos[] = $this->crearEventoDesdeArray($data);
        }
        
        return $eventos;
    }
    
    public function obtenerMiembrosEvento($eventoId) {
        return $this->eventoRepository->getMiembrosEvento($eventoId);
    }
    
    public function registrarAsistencia($miembroId, $eventoId, $asistencia = true) {
        return $this->eventoRepository->registrarAsistencia($miembroId, $eventoId, $asistencia);
    }
    
    public function eliminarAsistencia($miembroId, $eventoId) {
        return $this->eventoRepository->eliminarAsistencia($miembroId, $eventoId);
    }
    
    public function obtenerTiposEvento() {
        return $this->eventoRepository->getTiposEvento();
    }
    
    public function buscarEventos($termino = '', $tipo = null, $fechaInicio = null, $fechaFin = null) {
        $eventosData = $this->eventoRepository->buscarEventos($termino, $tipo, $fechaInicio, $fechaFin);
        $eventos = [];
        
        foreach ($eventosData as $data) {
            $eventos[] = $this->crearEventoDesdeArray($data);
        }
        
        return $eventos;
    }
    
    private function crearEventoDesdeArray($data) {
        return new Evento(
            $data['id'],
            $data['nombre'],
            $data['fecha'],
            $data['ubicacion'],
            $data['descripcion'],
            $data['tipo']
        );
    }
    
    public function validarEvento($nombre, $fecha) {
        $errores = [];
        
        if (empty($nombre)) {
            $errores['nombre'] = 'El nombre del evento es obligatorio';
        }
        
        if (empty($fecha)) {
            $errores['fecha'] = 'La fecha del evento es obligatoria';
        } else {
            $fechaObj = DateTime::createFromFormat('Y-m-d', $fecha);
            if (!$fechaObj || $fechaObj->format('Y-m-d') !== $fecha) {
                $errores['fecha'] = 'El formato de fecha debe ser YYYY-MM-DD';
            }
        }
        
        return $errores;
    }
}