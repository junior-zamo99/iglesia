<?php
// datos/repositories/BautizoRepository.php

namespace Iglesia\Datos\Repositories;

use Iglesia\Datos\Config\Database;
use Iglesia\Negocio\Entidades\Bautizo;

class BautizoRepository {
    private $db;
    
    public function __construct() {
        $this->db = Database::obtenerConexion();
    }
    
    public function findAll() {
        $query = "SELECT b.*, m.nombre, m.apellido 
                 FROM bautizo b
                 JOIN miembros m ON b.miembro_id = m.id 
                 ORDER BY b.fecha DESC";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
    
    public function findById($id) {
        $query = "SELECT b.*, m.nombre, m.apellido 
                 FROM bautizo b
                 JOIN miembros m ON b.miembro_id = m.id 
                 WHERE b.id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$id]);
        
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }
    
    public function save(Bautizo $bautizo) {
        $query = "INSERT INTO bautizo (miembro_id, fecha, lugar, pastor) 
                  VALUES (?, ?, ?, ?)";
        
        $stmt = $this->db->prepare($query);
        
        $result = $stmt->execute([
            $bautizo->getMiembroId(),
            $bautizo->getFecha(),
            $bautizo->getLugar(),
            $bautizo->getPastor()
        ]);
        
        if ($result) {
            $bautizo->setId($this->db->lastInsertId());
        }
        
        return $result;
    }
    
    public function update(Bautizo $bautizo) {
        $query = "UPDATE bautizo SET miembro_id = ?, fecha = ?, lugar = ?, pastor = ? 
                  WHERE id = ?";
        
        $stmt = $this->db->prepare($query);
        
        return $stmt->execute([
            $bautizo->getMiembroId(),
            $bautizo->getFecha(),
            $bautizo->getLugar(),
            $bautizo->getPastor(),
            $bautizo->getId()
        ]);
    }
    
    public function delete($id) {
        $query = "DELETE FROM bautizo WHERE id = ?";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([$id]);
    }
    
    public function findByMiembroId($miembroId) {
        $query = "SELECT * FROM bautizo WHERE miembro_id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$miembroId]);
        
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }
    
    public function buscarBautizos($termino = '', $fechaInicio = null, $fechaFin = null) {
        $params = [];
        $sql = "SELECT b.*, m.nombre, m.apellido 
                FROM bautizo b
                JOIN miembros m ON b.miembro_id = m.id 
                WHERE 1=1";
        
        if (!empty($termino)) {
            $sql .= " AND (m.nombre LIKE ? OR m.apellido LIKE ? OR b.lugar LIKE ? OR b.pastor LIKE ?)";
            $params[] = "%$termino%";
            $params[] = "%$termino%";
            $params[] = "%$termino%";
            $params[] = "%$termino%";
        }
        
        if (!empty($fechaInicio)) {
            $sql .= " AND b.fecha >= ?";
            $params[] = $fechaInicio;
        }
        
        if (!empty($fechaFin)) {
            $sql .= " AND b.fecha <= ?";
            $params[] = $fechaFin;
        }
        
        $sql .= " ORDER BY b.fecha DESC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
}