<?php
// negocio/entidades/Contribucion.php

namespace Iglesia\Negocio\Entidades;

class Contribucion {
    private $id;
    private $miembro_id;
    private $fecha;
    private $monto;
    private $tipo;
    private $descripcion;
    
    public function __construct($id = null, $miembro_id = null, $fecha = null, $monto = null, $tipo = null, $descripcion = null) {
        $this->id = $id;
        $this->miembro_id = $miembro_id;
        $this->fecha = $fecha;
        $this->monto = $monto;
        $this->tipo = $tipo;
        $this->descripcion = $descripcion;
    }
    
    
    public function getId() {
        return $this->id;
    }
    
    public function getMiembroId() {
        return $this->miembro_id;
    }
    
    public function getFecha() {
        return $this->fecha;
    }
    
    public function getMonto() {
        return $this->monto;
    }
    
    public function getTipo() {
        return $this->tipo;
    }
    
    public function getDescripcion() {
        return $this->descripcion;
    }
    
    
    public function setId($id) {
        $this->id = $id;
        return $this;
    }
    
    public function setMiembroId($miembro_id) {
        $this->miembro_id = $miembro_id;
        return $this;
    }
    
    public function setFecha($fecha) {
        $this->fecha = $fecha;
        return $this;
    }
    
    public function setMonto($monto) {
        $this->monto = $monto;
        return $this;
    }
    
    public function setTipo($tipo) {
        $this->tipo = $tipo;
        return $this;
    }
    
    public function setDescripcion($descripcion) {
        $this->descripcion = $descripcion;
        return $this;
    }
}