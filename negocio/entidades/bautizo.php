<?php
// negocio/entidades/Bautizo.php

namespace Iglesia\Negocio\Entidades;

class Bautizo {
    private $id;
    private $miembro_id;
    private $fecha;
    private $lugar;
    private $pastor;
    
    public function __construct($id = null, $miembro_id = null, $fecha = null, $lugar = null, $pastor = null) {
        $this->id = $id;
        $this->miembro_id = $miembro_id;
        $this->fecha = $fecha;
        $this->lugar = $lugar;
        $this->pastor = $pastor;
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
    
    public function getLugar() {
        return $this->lugar;
    }
    
    public function getPastor() {
        return $this->pastor;
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
    
    public function setLugar($lugar) {
        $this->lugar = $lugar;
        return $this;
    }
    
    public function setPastor($pastor) {
        $this->pastor = $pastor;
        return $this;
    }
}