<?php
// datos/repositories/ContribucionRepository.php

namespace Iglesia\Datos\Repositories;

use Iglesia\Datos\Config\Database;
use Iglesia\Negocio\Entidades\Contribucion;

class ContribucionRepository {
    private $db;
    
    public function __construct() {
        $this->db = Database::obtenerConexion();
    }
    
    public function findAll() {
        $query = "SELECT c.*, m.nombre, m.apellido 
                 FROM contribucion c
                 JOIN miembros m ON c.miembro_id = m.id 
                 ORDER BY c.fecha DESC";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
    
    public function findById($id) {
        $query = "SELECT c.*, m.nombre, m.apellido 
                 FROM contribucion c
                 JOIN miembros m ON c.miembro_id = m.id 
                 WHERE c.id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$id]);
        
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }
    
    public function save(Contribucion $contribucion) {
        $query = "INSERT INTO contribucion (miembro_id, fecha, monto, tipo, descripcion) 
                  VALUES (?, ?, ?, ?, ?)";
        
        $stmt = $this->db->prepare($query);
        
        $result = $stmt->execute([
            $contribucion->getMiembroId(),
            $contribucion->getFecha(),
            $contribucion->getMonto(),
            $contribucion->getTipo(),
            $contribucion->getDescripcion()
        ]);
        
        if ($result) {
            $contribucion->setId($this->db->lastInsertId());
        }
        
        return $result;
    }
    
    public function update(Contribucion $contribucion) {
        $query = "UPDATE contribucion SET miembro_id = ?, fecha = ?, monto = ?, tipo = ?, descripcion = ? 
                  WHERE id = ?";
        
        $stmt = $this->db->prepare($query);
        
        return $stmt->execute([
            $contribucion->getMiembroId(),
            $contribucion->getFecha(),
            $contribucion->getMonto(),
            $contribucion->getTipo(),
            $contribucion->getDescripcion(),
            $contribucion->getId()
        ]);
    }
    
    public function delete($id) {
        $query = "DELETE FROM contribucion WHERE id = ?";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([$id]);
    }
    
    public function findByMiembroId($miembroId) {
        $query = "SELECT c.*, m.nombre, m.apellido 
                  FROM contribucion c
                  JOIN miembros m ON c.miembro_id = m.id 
                  WHERE c.miembro_id = ?
                  ORDER BY c.fecha DESC";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$miembroId]);
        
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
    
    public function buscarContribuciones($termino = '', $tipo = null, $fechaInicio = null, $fechaFin = null) {
        $params = [];
        $sql = "SELECT c.*, m.nombre, m.apellido 
                FROM contribucion c
                JOIN miembros m ON c.miembro_id = m.id 
                WHERE 1=1";
        
        if (!empty($termino)) {
            $sql .= " AND (m.nombre LIKE ? OR m.apellido LIKE ? OR c.tipo LIKE ? OR c.descripcion LIKE ?)";
            $params[] = "%$termino%";
            $params[] = "%$termino%";
            $params[] = "%$termino%";
            $params[] = "%$termino%";
        }
        
        if (!empty($tipo)) {
            $sql .= " AND c.tipo = ?";
            $params[] = $tipo;
        }
        
        if (!empty($fechaInicio)) {
            $sql .= " AND c.fecha >= ?";
            $params[] = $fechaInicio;
        }
        
        if (!empty($fechaFin)) {
            $sql .= " AND c.fecha <= ?";
            $params[] = $fechaFin;
        }
        
        $sql .= " ORDER BY c.fecha DESC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
    
    public function getTiposContribucion() {
        
        $query = "SELECT DISTINCT tipo FROM contribucion ORDER BY tipo";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        
        return $stmt->fetchAll(\PDO::FETCH_COLUMN);
    }
    
    public function getResumenPorTipo($fechaInicio = null, $fechaFin = null) {
        $params = [];
        $sql = "SELECT tipo, SUM(monto) as total 
                FROM contribucion WHERE 1=1";
        
        if (!empty($fechaInicio)) {
            $sql .= " AND fecha >= ?";
            $params[] = $fechaInicio;
        }
        
        if (!empty($fechaFin)) {
            $sql .= " AND fecha <= ?";
            $params[] = $fechaFin;
        }
        
        $sql .= " GROUP BY tipo ORDER BY total DESC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
    
    public function getResumenPorMes($anio) {
        $sql = "SELECT MONTH(fecha) as mes, SUM(monto) as total 
                FROM contribucion 
                WHERE YEAR(fecha) = ? 
                GROUP BY MONTH(fecha) 
                ORDER BY MONTH(fecha)";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$anio]);
        
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
    
    public function getTotalContribuciones($fechaInicio = null, $fechaFin = null) {
        $params = [];
        $sql = "SELECT SUM(monto) as total FROM contribucion WHERE 1=1";
        
        if (!empty($fechaInicio)) {
            $sql .= " AND fecha >= ?";
            $params[] = $fechaInicio;
        }
        
        if (!empty($fechaFin)) {
            $sql .= " AND fecha <= ?";
            $params[] = $fechaFin;
        }
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        
        return $stmt->fetchColumn();
    }
}