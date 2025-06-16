<?php

namespace Iglesia\Negocio\Strategies;

use Iglesia\Datos\Repositories\ContribucionRepository;

class MensualCalculoStrategy implements CalculoContribucionStrategy {
    private $contribucionRepository;
    
    public function __construct(ContribucionRepository $contribucionRepository) {
        $this->contribucionRepository = $contribucionRepository;
    }
    
    public function execute(array $data): array {
        $mes = $data['mes'] ?? date('n');
        $año = $data['año'] ?? date('Y');
        
        $fechaInicio = sprintf('%04d-%02d-01', $año, $mes);
        $fechaFin = date('Y-m-t', strtotime($fechaInicio));
        
      
        $totalGeneral = $this->contribucionRepository->getTotalContribuciones($fechaInicio, $fechaFin);
        $totalGeneral = $totalGeneral ?? 0; 
        
        $resumenPorTipo = $this->contribucionRepository->getResumenPorTipo($fechaInicio, $fechaFin);
        $resumenPorTipo = $resumenPorTipo ?? []; 
        
        $contribuciones = $this->contribucionRepository->buscarContribuciones('', null, $fechaInicio, $fechaFin);
        $contribuciones = $contribuciones ?? []; 
        
        $cantidadContribuciones = count($contribuciones);
        $promedioPorContribucion = $cantidadContribuciones > 0 ? $totalGeneral / $cantidadContribuciones : 0;
        $diasEnMes = date('t', strtotime($fechaInicio));
        $promedioDiario = $diasEnMes > 0 ? $totalGeneral / $diasEnMes : 0;
        
        return [
            'tipo_calculo' => 'mensual',
            'periodo' => [
                'mes' => $mes,
                'año' => $año,
                'nombre_mes' => $this->getNombreMes($mes),
                'fecha_inicio' => $fechaInicio,
                'fecha_fin' => $fechaFin,
                'dias_en_mes' => $diasEnMes
            ],
            'totales' => [
                'total_general' => round($totalGeneral, 2),
                'cantidad_contribuciones' => $cantidadContribuciones,
                'promedio_por_contribucion' => round($promedioPorContribucion, 2),
                'promedio_diario' => round($promedioDiario, 2)
            ],
            'desglose_por_tipo' => $resumenPorTipo,
            'contribuciones_detalle' => array_slice($contribuciones, 0, 10) // Solo las primeras 10
        ];
    }
    
    private function getNombreMes(int $mes): string {
        $meses = [
            1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril',
            5 => 'Mayo', 6 => 'Junio', 7 => 'Julio', 8 => 'Agosto',
            9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre'
        ];
        
        return $meses[$mes] ?? 'Mes ' . $mes;
    }
}