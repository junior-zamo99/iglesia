<?php

namespace Iglesia\Negocio\Entidades;

class Miembro {
    private $id;
    private $nombre;
    private $estado_civil;
    private $telefono;
    private $direccion;
    private $fecha_nacimiento;
    private $email;
    private $fecha_registro;

    public function __construct(
        $nombre = null,
        $apellido = null,
        $email = null,
        $estado_civil = null, 
        $telefono = null, 
        $direccion = null, 
        $fecha_nacimiento = null,
        $fecha_registro = null,
    ) {
        $this->nombre = $nombre;
        $this->apellido = $apellido;
        $this->email = $email;
        $this->estado_civil = $estado_civil;
        $this->telefono = $telefono;
        $this->direccion = $direccion;
        $this->fecha_nacimiento = $fecha_nacimiento;
        $this->fecha_registro = $fecha_registro;
    }

    
    public function getId() { return $this->id; }
    public function setId($id) { $this->id = $id; }

    public function getNombre() { return $this->nombre; }
    public function setNombre($nombre) { $this->nombre = $nombre; }

    public function getApellido() { return $this->apellido; }
    public function setApellido($apellido) { $this->apellido = $apellido; }

    public function getEmail() { return $this->email; }
    public function setEmail($email) { $this->email = $email; }

    public function getEstadoCivil() { return $this->estado_civil; }
    public function setEstadoCivil($estado_civil) { $this->estado_civil = $estado_civil; }

    public function getTelefono() { return $this->telefono; }
    public function setTelefono($telefono) { $this->telefono = $telefono; }

    public function getDireccion() { return $this->direccion; }
    public function setDireccion($direccion) { $this->direccion = $direccion; }

    public function getFechaNacimiento() { return $this->fecha_nacimiento; }
    public function setFechaNacimiento($fecha_nacimiento) { $this->fecha_nacimiento = $fecha_nacimiento; }

    public function getFechaRegistro() { return $this->fecha_registro; }
    public function setFechaRegistro($fecha_registro) { $this->fecha_registro = $fecha_registro; }

 
    public function getMinisterios() { return $this->ministerios; }
    public function addMinisterio(Ministerio $ministerio) { 
        $this->ministerios[] = $ministerio; 
    }
}