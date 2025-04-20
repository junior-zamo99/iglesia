<?php
// datos/repositories/EventoRepository.php

namespace Iglesia\Datos\Repositories;

use Iglesia\Datos\Config\Database;
use Iglesia\Negocio\Entidades\Evento;

class EventoRepository {
    private $db;
    
    public function __construct() {
        $this->db = Database::obtenerConexion();
    }
    
    public function findAll() {
        $query = "SELECT * FROM evento ORDER BY fecha DESC";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
    
    public function findById($id) {
        $query = "SELECT * FROM evento WHERE id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$id]);
        
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }
    
    public function save(Evento $evento) {
        $query = "INSERT INTO evento (nombre, fecha, ubicacion, descripcion, tipo) 
                  VALUES (?, ?, ?, ?, ?)";
        
        $stmt = $this->db->prepare($query);
        
        $result = $stmt->execute([
            $evento->getNombre(),
            $evento->getFecha(),
            $evento->getUbicacion(),
            $evento->getDescripcion(),
            $evento->getTipo()
        ]);
        
        if ($result) {
            $evento->setId($this->db->lastInsertId());
        }
        
        return $result;
    }
    
    public function update(Evento $evento) {
        $query = "UPDATE evento SET nombre = ?, fecha = ?, ubicacion = ?, descripcion = ?, tipo = ? 
                  WHERE id = ?";
        
        $stmt = $this->db->prepare($query);
        
        return $stmt->execute([
            $evento->getNombre(),
            $evento->getFecha(),
            $evento->getUbicacion(),
            $evento->getDescripcion(),
            $evento->getTipo(),
            $evento->getId()
        ]);
    }
    
    public function delete($id) {
        
        $query1 = "DELETE FROM miembroevento WHERE evento_id = ?";
        $stmt1 = $this->db->prepare($query1);
        $stmt1->execute([$id]);
       
        $query2 = "DELETE FROM evento WHERE id = ?";
        $stmt2 = $this->db->prepare($query2);
        return $stmt2->execute([$id]);
    }
    
    public function getProximosEventos($limite = 5) {
        $query = "SELECT * FROM evento WHERE fecha >= CURRENT_DATE ORDER BY fecha ASC LIMIT ?";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$limite]);
        
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
    
    public function getEventosPasados($limite = 10) {
        $query = "SELECT * FROM evento WHERE fecha < CURRENT_DATE ORDER BY fecha DESC LIMIT ?";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$limite]);
        
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
    
    public function getMiembrosEvento($eventoId) {
        $query = "SELECT m.id, m.nombre, m.apellido, m.email, m.telefono, me.asistencia 
                 FROM miembros m
                 JOIN miembroevento me ON m.id = me.miembro_id
                 WHERE me.evento_id = ?
                 ORDER BY m.apellido, m.nombre";
        
        $stmt = $this->db->prepare($query);
        $stmt->execute([$eventoId]);
        
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
    
    public function registrarAsistencia($miembroId, $eventoId, $asistencia = true) {
        $queryCheck = "SELECT COUNT(*) FROM miembroevento WHERE miembro_id = ? AND evento_id = ?";
        $stmtCheck = $this->db->prepare($queryCheck);
        $stmtCheck->execute([$miembroId, $eventoId]);
        if ((int)$stmtCheck->fetchColumn() > 0) {
            $query = "UPDATE miembroevento SET asistencia = ? WHERE miembro_id = ? AND evento_id = ?";
            $stmt = $this->db->prepare($query);
            return $stmt->execute([$asistencia, $miembroId, $eventoId]);
        } else {
            $query = "INSERT INTO miembroevento (miembro_id, evento_id, asistencia) VALUES (?, ?, ?)";
            $stmt = $this->db->prepare($query);
            return $stmt->execute([$miembroId, $eventoId, $asistencia]);
        }
    }
    
    public function eliminarAsistencia($miembroId, $eventoId) {
        $query = "DELETE FROM miembroevento WHERE miembro_id = ? AND evento_id = ?";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([$miembroId, $eventoId]);
    }
    
    public function getTiposEvento() {
     
        $query = "SELECT DISTINCT tipo FROM evento ORDER BY tipo";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        
        return $stmt->fetchAll(\PDO::FETCH_COLUMN);
    }
    
    public function buscarEventos($termino, $tipo = null, $fechaInicio = null, $fechaFin = null) {
        $params = [];
        $sql = "SELECT * FROM evento WHERE 1=1";
        
        if (!empty($termino)) {
            $sql .= " AND (nombre LIKE ? OR descripcion LIKE ? OR ubicacion LIKE ?)";
            $params[] = "%$termino%";
            $params[] = "%$termino%";
            $params[] = "%$termino%";
        }
        
        if (!empty($tipo)) {
            $sql .= " AND tipo = ?";
            $params[] = $tipo;
        }
        
        if (!empty($fechaInicio)) {
            $sql .= " AND fecha >= ?";
            $params[] = $fechaInicio;
        }
        
        if (!empty($fechaFin)) {
            $sql .= " AND fecha <= ?";
            $params[] = $fechaFin;
        }
        
        $sql .= " ORDER BY fecha DESC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
}