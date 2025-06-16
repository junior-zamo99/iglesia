<?php
// negocio/services/ContribucionService.php

namespace Iglesia\Negocio\Services;

use Iglesia\Datos\Repositories\ContribucionRepository;
use Iglesia\Negocio\Entidades\Contribucion;
use DateTime;
use Iglesia\Negocio\Services\CalculadoraContribuciones;
use Iglesia\Negocio\Strategies\MensualCalculoStrategy;
use Iglesia\Negocio\Strategies\AnualCalculoStrategy;
use Iglesia\Negocio\Strategies\PorTipoCalculoStrategy;

class ContribucionService {
    public $contribucionRepository;
     private $calculadora;
    public function __construct() {
        $this->contribucionRepository = new ContribucionRepository();
        $this->calculadora = new CalculadoraContribuciones();
    }
    
    public function obtenerTodas() {
        $contribucionesData = $this->contribucionRepository->findAll();
        $contribuciones = [];
        
        foreach ($contribucionesData as $data) {
            $contribucion = $this->crearContribucionDesdeArray($data);
            $contribucion->nombreMiembro = $data['nombre'] . ' ' . $data['apellido'];
            $contribuciones[] = $contribucion;
        }
        
        return $contribuciones;
    }
    
    public function obtenerPorId($id) {
        $data = $this->contribucionRepository->findById($id);
        
        if (!$data) {
            return null;
        }
        
        $contribucion = $this->crearContribucionDesdeArray($data);
        $contribucion->nombreMiembro = $data['nombre'] . ' ' . $data['apellido'];
        return $contribucion;
    }
    
    public function obtenerPorMiembroId($miembroId) {
        $contribucionesData = $this->contribucionRepository->findByMiembroId($miembroId);
        $contribuciones = [];
        
        foreach ($contribucionesData as $data) {
            $contribucion = $this->crearContribucionDesdeArray($data);
            $contribucion->nombreMiembro = $data['nombre'] . ' ' . $data['apellido'];
            $contribuciones[] = $contribucion;
        }
        
        return $contribuciones;
    }
    
    public function crear($miembroId, $fecha, $monto, $tipo, $descripcion = '') {
        // Validar que el monto sea un número positivo
        if (!is_numeric($monto) || $monto <= 0) {
            return false;
        }
        
        $contribucion = new Contribucion(null, $miembroId, $fecha, $monto, $tipo, $descripcion);
        return $this->contribucionRepository->save($contribucion);
    }
    
    public function actualizar($id, $miembroId, $fecha, $monto, $tipo, $descripcion = '') {
        // Validar que el monto sea un número positivo
        if (!is_numeric($monto) || $monto <= 0) {
            return false;
        }
        
        $contribucion = new Contribucion($id, $miembroId, $fecha, $monto, $tipo, $descripcion);
        return $this->contribucionRepository->update($contribucion);
    }
    
    public function eliminar($id) {
        return $this->contribucionRepository->delete($id);
    }
    
    public function buscarContribuciones($termino = '', $tipo = null, $fechaInicio = null, $fechaFin = null) {
        $contribucionesData = $this->contribucionRepository->buscarContribuciones($termino, $tipo, $fechaInicio, $fechaFin);
        $contribuciones = [];
        
        foreach ($contribucionesData as $data) {
            $contribucion = $this->crearContribucionDesdeArray($data);
            $contribucion->nombreMiembro = $data['nombre'] . ' ' . $data['apellido'];
            $contribuciones[] = $contribucion;
        }
        
        return $contribuciones;
    }
    
    public function obtenerTiposContribucion() {
        return $this->contribucionRepository->getTiposContribucion();
    }
    
    public function obtenerResumenPorTipo($fechaInicio = null, $fechaFin = null) {
        return $this->contribucionRepository->getResumenPorTipo($fechaInicio, $fechaFin);
    }
    
    public function obtenerResumenPorMes($anio = null) {
        if (!$anio) {
            $anio = date('Y');
        }
        return $this->contribucionRepository->getResumenPorMes($anio);
    }
    
    public function obtenerTotalContribuciones($fechaInicio = null, $fechaFin = null) {
        return $this->contribucionRepository->getTotalContribuciones($fechaInicio, $fechaFin);
    }
    
    private function crearContribucionDesdeArray($data) {
        return new Contribucion(
            $data['id'],
            $data['miembro_id'],
            $data['fecha'],
            $data['monto'],
            $data['tipo'],
            $data['descripcion'] ?? ''
        );
    }
    
    public function validarContribucion($miembroId, $fecha, $monto, $tipo) {
        $errores = [];
        
        if (empty($miembroId)) {
            $errores['miembro_id'] = 'Debe seleccionar un miembro';
        }
        
        if (empty($fecha)) {
            $errores['fecha'] = 'La fecha de la contribución es obligatoria';
        } else {
            $fechaObj = DateTime::createFromFormat('Y-m-d', $fecha);
            if (!$fechaObj || $fechaObj->format('Y-m-d') !== $fecha) {
                $errores['fecha'] = 'El formato de fecha debe ser YYYY-MM-DD';
            }
        }
        
        if (empty($monto)) {
            $errores['monto'] = 'El monto de la contribución es obligatorio';
        } else if (!is_numeric($monto) || $monto <= 0) {
            $errores['monto'] = 'El monto debe ser un número positivo';
        }
        
        if (empty($tipo)) {
            $errores['tipo'] = 'El tipo de contribución es obligatorio';
        }
        
        return $errores;
    }



 public function obtenerTotalesMensuales(int $mes = null, int $año = null): array {
        $strategy = new MensualCalculoStrategy($this->contribucionRepository);
        $this->calculadora->setStrategy($strategy);
        
        return $this->calculadora->calcular([
            'mes' => $mes,
            'año' => $año
        ]);
    }
    
    /**
     * Obtiene totales anuales usando Strategy Pattern
     */
    public function obtenerTotalesAnuales(int $año = null): array {
        $strategy = new AnualCalculoStrategy($this->contribucionRepository);
        $this->calculadora->setStrategy($strategy);
        
        return $this->calculadora->calcular([
            'año' => $año
        ]);
    }
    
    /**
     * Obtiene totales por tipo usando Strategy Pattern
     */
    public function obtenerTotalesPorTipo(string $fechaInicio = null, string $fechaFin = null): array {
        $strategy = new PorTipoCalculoStrategy($this->contribucionRepository);
        $this->calculadora->setStrategy($strategy);
        
        return $this->calculadora->calcular([
            'fecha_inicio' => $fechaInicio,
            'fecha_fin' => $fechaFin
        ]);
    }
    
    /**
     * Compara dos estrategias diferentes
     */
    public function compararEstrategias(string $estrategia1, string $estrategia2, array $parametros = []): array {
        $resultado1 = $this->ejecutarEstrategia($estrategia1, $parametros);
        $resultado2 = $this->ejecutarEstrategia($estrategia2, $parametros);
        
        return [
            'comparacion' => [
                $estrategia1 => $resultado1,
                $estrategia2 => $resultado2
            ],
            'generado_en' => date('Y-m-d H:i:s')
        ];
    }
    
    private function ejecutarEstrategia(string $tipoEstrategia, array $parametros): array {
        switch ($tipoEstrategia) {
            case 'mensual':
                return $this->obtenerTotalesMensuales($parametros['mes'] ?? null, $parametros['año'] ?? null);
                
            case 'anual':
                return $this->obtenerTotalesAnuales($parametros['año'] ?? null);
                
            case 'por_tipo':
                return $this->obtenerTotalesPorTipo($parametros['fecha_inicio'] ?? null, $parametros['fecha_fin'] ?? null);
                
            default:
                throw new \InvalidArgumentException("Estrategia '$tipoEstrategia' no válida");
        }
    }

       public function getContribucionRepository(): ContribucionRepository {
        return $this->contribucionRepository;
    }
    

}