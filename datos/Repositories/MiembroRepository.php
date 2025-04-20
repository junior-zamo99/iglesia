<?php
// datos/repositories/MiembroRepository.php

namespace Iglesia\Datos\Repositories;

use Iglesia\Datos\Config\Database;
use Iglesia\Negocio\Entidades\Miembro;
use PD0;

class MiembroRepository {
    private $db;
    
    public function __construct() {
        // Obtener la conexión a la base de datos
       
        $this->db = Database::obtenerConexion();
    }
    
    public function findAll() {
        $query = "SELECT * FROM miembros";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
    
    public function save(Miembro $miembro) {
        $query = "INSERT INTO miembros (nombre, apellido, email, estado_civil, telefono, direccion, fecha_nacimiento, fecha_registro) 
                  VALUES (?, ?, ?, ?, ?, ?, ?, NOW())";
        
        $stmt = $this->db->prepare($query);
        
        return $stmt->execute([
            $miembro->getNombre(),
            $miembro->getApellido(),
            $miembro->getEmail(),
            $miembro->getEstadoCivil(),
            $miembro->getTelefono(),
            $miembro->getDireccion(),
            $miembro->getFechaNacimiento()
        ]);
    }

    public function delete($id) {
        $query = "DELETE FROM miembros WHERE id = ?";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([$id]);
    }


    public function findById($id) {
        $query = "SELECT * FROM miembros WHERE id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$id]);
        
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public function update(Miembro $miembro) {
        $query = "UPDATE miembros SET nombre = ?, apellido = ?, email = ?, estado_civil = ?, telefono = ?, direccion = ?, fecha_nacimiento = ? WHERE id = ?";
        
        $stmt = $this->db->prepare($query);
        
        return $stmt->execute([
            $miembro->getNombre(),
            $miembro->getApellido(),
            $miembro->getEmail(),
            $miembro->getEstadoCivil(),
            $miembro->getTelefono(),
            $miembro->getDireccion(),
            $miembro->getFechaNacimiento(),
            $miembro->getId()
        ]);
    }
}