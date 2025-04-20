<?php
// negocio/entidades/Evento.php

namespace Iglesia\Negocio\Entidades;

class Evento {
    private $id;
    private $nombre;
    private $fecha;
    private $ubicacion;
    private $descripcion;
    private $tipo;
    
    public function __construct($id = null, $nombre = null, $fecha = null, $ubicacion = null, $descripcion = null, $tipo = null) {
        $this->id = $id;
        $this->nombre = $nombre;
        $this->fecha = $fecha;
        $this->ubicacion = $ubicacion;
        $this->descripcion = $descripcion;
        $this->tipo = $tipo;
    }
    
    // Getters
    public function getId() {
        return $this->id;
    }
    
    public function getNombre() {
        return $this->nombre;
    }
    
    public function getFecha() {
        return $this->fecha;
    }
    
    public function getUbicacion() {
        return $this->ubicacion;
    }
    
    public function getDescripcion() {
        return $this->descripcion;
    }
    
    public function getTipo() {
        return $this->tipo;
    }
    
    // Setters
    public function setId($id) {
        $this->id = $id;
        return $this;
    }
    
    public function setNombre($nombre) {
        $this->nombre = $nombre;
        return $this;
    }
    
    public function setFecha($fecha) {
        $this->fecha = $fecha;
        return $this;
    }
    
    public function setUbicacion($ubicacion) {
        $this->ubicacion = $ubicacion;
        return $this;
    }
    
    public function setDescripcion($descripcion) {
        $this->descripcion = $descripcion;
        return $this;
    }
    
    public function setTipo($tipo) {
        $this->tipo = $tipo;
        return $this;
    }
}