<?php
// datos/repositories/MinisterioRepository.php

namespace Iglesia\Datos\Repositories;

use Iglesia\Datos\Config\Database;
use Iglesia\Negocio\Entidades\Ministerio;

class MinisterioRepository {
    private $db;
    
    public function __construct() {
        $this->db = Database::obtenerConexion();
    }
    
    public function findAll() {
        $query = "SELECT * FROM ministerio ORDER BY nombre";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
    
    public function findById($id) {
        $query = "SELECT * FROM ministerio WHERE id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$id]);
        
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }
    
    public function save(Ministerio $ministerio) {
        $query = "INSERT INTO ministerio (nombre, descripcion) VALUES (?, ?)";
        $stmt = $this->db->prepare($query);
        
        $result = $stmt->execute([
            $ministerio->getNombre(),
            $ministerio->getDescripcion()
        ]);
        
        if ($result) {
            $ministerio->setId($this->db->lastInsertId());
        }
        
        return $result;
    }
    
    public function update(Ministerio $ministerio) {
        $query = "UPDATE ministerio SET nombre = ?, descripcion = ? WHERE id = ?";
        $stmt = $this->db->prepare($query);
        
        return $stmt->execute([
            $ministerio->getNombre(),
            $ministerio->getDescripcion(),
            $ministerio->getId()
        ]);
    }
    
    public function delete($id) {
        $query = "DELETE FROM ministerio WHERE id = ?";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([$id]);
    }
    
    public function getMiembrosMinisterio($ministerioId) {
        $query = "SELECT m.id, m.nombre, m.apellido, m.telefono, m.email, mm.fechainicio 
                 FROM miembros m
                 JOIN miembroministerio mm ON m.id = mm.miembro_id
                 WHERE mm.ministerio_id = ?
                 ORDER BY m.apellido, m.nombre";
        
        $stmt = $this->db->prepare($query);
        $stmt->execute([$ministerioId]);
        
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
    
    public function asignarMiembro($miembroId, $ministerioId, $fechaInicio) {
        $query = "INSERT INTO miembroministerio (miembro_id, ministerio_id, fechainicio) VALUES (?, ?, ?)";
        $stmt = $this->db->prepare($query);
        
        return $stmt->execute([$miembroId, $ministerioId, $fechaInicio]);
    }
    
    public function removerMiembro($miembroId, $ministerioId) {
        $query = "DELETE FROM miembroministerio WHERE miembro_id = ? AND ministerio_id = ?";
        $stmt = $this->db->prepare($query);
        
        return $stmt->execute([$miembroId, $ministerioId]);
    }
    
    public function verificarMiembroEnMinisterio($miembroId, $ministerioId) {
        $query = "SELECT COUNT(*) FROM miembroministerio WHERE miembro_id = ? AND ministerio_id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$miembroId, $ministerioId]);
        
        return (int)$stmt->fetchColumn() > 0;
    }
}