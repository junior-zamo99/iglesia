<?php

namespace Iglesia\Negocio\Strategies;

use Iglesia\Datos\Repositories\ContribucionRepository;

class PorTipoCalculoStrategy implements CalculoContribucionStrategy {
    private $contribucionRepository;
    
    public function __construct(ContribucionRepository $contribucionRepository) {
        $this->contribucionRepository = $contribucionRepository;
    }
    
    public function execute(array $data): array {
        $fechaInicio = $data['fecha_inicio'] ?? date('Y-01-01');
        $fechaFin = $data['fecha_fin'] ?? date('Y-12-31');
        
        $resumenPorTipo = $this->contribucionRepository->getResumenPorTipo($fechaInicio, $fechaFin);
        $totalGeneral = $this->contribucionRepository->getTotalContribuciones($fechaInicio, $fechaFin);
        $todosLosTipos = $this->contribucionRepository->getTiposContribucion();
        
        $detallesPorTipo = [];
        $tipoMayorRecaudacion = null;
        $mayorMonto = 0;
        
        foreach ($resumenPorTipo as $tipo) {
            $porcentaje = $totalGeneral > 0 ? ($tipo['total'] / $totalGeneral) * 100 : 0;
            
            $detalle = [
                'tipo' => $tipo['tipo'],
                'total' => round($tipo['total'], 2),
                'porcentaje' => round($porcentaje, 2),
                'contribuciones' => $this->getContribucionesPorTipo($tipo['tipo'], $fechaInicio, $fechaFin)
            ];
            
            $detallesPorTipo[] = $detalle;
            
            if ($tipo['total'] > $mayorMonto) {
                $mayorMonto = $tipo['total'];
                $tipoMayorRecaudacion = $tipo['tipo'];
            }
        }
        
        $tiposSinContribuciones = array_diff($todosLosTipos, array_column($resumenPorTipo, 'tipo'));
        
        return [
            'tipo_calculo' => 'por_tipo',
            'periodo' => [
                'fecha_inicio' => $fechaInicio,
                'fecha_fin' => $fechaFin,
                'descripcion' => $this->getDescripcionPeriodo($fechaInicio, $fechaFin)
            ],
            'totales' => [
                'total_general' => round($totalGeneral, 2),
                'tipos_con_contribuciones' => count($resumenPorTipo),
                'tipos_registrados' => count($todosLosTipos),
                'cantidad_contribuciones' => $this->getTotalContribuciones($fechaInicio, $fechaFin)
            ],
            'desglose_por_tipo' => $detallesPorTipo,
            'estadisticas' => [
                'tipo_mayor_recaudacion' => $tipoMayorRecaudacion,
                'monto_mayor_recaudacion' => $mayorMonto,
                'tipos_sin_contribuciones' => $tiposSinContribuciones,
                'distribucion' => $this->calcularDistribucion($detallesPorTipo)
            ]
        ];
    }
    
    private function getContribucionesPorTipo(string $tipo, string $fechaInicio, string $fechaFin): array {
        $contribuciones = $this->contribucionRepository->buscarContribuciones('', $tipo, $fechaInicio, $fechaFin);
        
        return [
            'cantidad' => count($contribuciones),
            'promedio' => count($contribuciones) > 0 ? 
                array_sum(array_column($contribuciones, 'monto')) / count($contribuciones) : 0,
            'contribucion_mayor' => count($contribuciones) > 0 ? 
                max(array_column($contribuciones, 'monto')) : 0,
            'contribucion_menor' => count($contribuciones) > 0 ? 
                min(array_column($contribuciones, 'monto')) : 0
        ];
    }
    
    private function getTotalContribuciones(string $fechaInicio, string $fechaFin): int {
        $contribuciones = $this->contribucionRepository->buscarContribuciones('', null, $fechaInicio, $fechaFin);
        return count($contribuciones);
    }
    
    private function getDescripcionPeriodo(string $fechaInicio, string $fechaFin): string {
        $inicio = date('d/m/Y', strtotime($fechaInicio));
        $fin = date('d/m/Y', strtotime($fechaFin));
        
        if ($fechaInicio === date('Y-01-01') && $fechaFin === date('Y-12-31')) {
            return 'Año ' . date('Y');
        }
        
        return "Del {$inicio} al {$fin}";
    }
    
    private function calcularDistribucion(array $detallesPorTipo): string {
        if (empty($detallesPorTipo)) {
            return 'Sin contribuciones';
        }
        
        $tiposDominantes = array_filter($detallesPorTipo, function($tipo) {
            return $tipo['porcentaje'] >= 40;
        });
        
        if (!empty($tiposDominantes)) {
            $tipoDominante = reset($tiposDominantes);
            return "Dominado por: {$tipoDominante['tipo']} ({$tipoDominante['porcentaje']}%)";
        }
        
        return 'Distribución equilibrada';
    }
}