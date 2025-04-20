<?php
// negocio/services/MinisterioService.php

namespace Iglesia\Negocio\Services;

use Iglesia\Datos\Repositories\MinisterioRepository;
use Iglesia\Negocio\Entidades\Ministerio;

class MinisterioService {
    private $ministerioRepository;
    
    public function __construct() {
        $this->ministerioRepository = new MinisterioRepository();
    }
    
    public function obtenerTodos() {
        $ministeriosData = $this->ministerioRepository->findAll();
        $ministerios = [];
        
        foreach ($ministeriosData as $data) {
            $ministerios[] = new Ministerio(
                $data['id'],
                $data['nombre'],
                $data['descripcion']
            );
        }
        
        return $ministerios;
    }
    
    public function obtenerPorId($id) {
        $data = $this->ministerioRepository->findById($id);
        
        if (!$data) {
            return null;
        }
        
        return new Ministerio(
            $data['id'],
            $data['nombre'],
            $data['descripcion']
        );
    }
    
    public function crear($nombre, $descripcion) {
        $ministerio = new Ministerio(null, $nombre, $descripcion);
        return $this->ministerioRepository->save($ministerio);
    }
    
    public function actualizar($id, $nombre, $descripcion) {
        $ministerio = new Ministerio($id, $nombre, $descripcion);
        return $this->ministerioRepository->update($ministerio);
    }
    
    public function eliminar($id) {
        return $this->ministerioRepository->delete($id);
    }
    
    public function obtenerMiembrosMinisterio($ministerioId) {
        return $this->ministerioRepository->getMiembrosMinisterio($ministerioId);
    }
    
    public function asignarMiembro($miembroId, $ministerioId, $fechaInicio) {
        // Verificamos si ya existe la asignación
        if ($this->ministerioRepository->verificarMiembroEnMinisterio($miembroId, $ministerioId)) {
            return false; // Ya está asignado
        }
        
        return $this->ministerioRepository->asignarMiembro($miembroId, $ministerioId, $fechaInicio);
    }
    
    public function removerMiembro($miembroId, $ministerioId) {
        return $this->ministerioRepository->removerMiembro($miembroId, $ministerioId);
    }
}