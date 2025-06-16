<?php

namespace Iglesia\Negocio\Strategies;

use Iglesia\Datos\Repositories\ContribucionRepository;

class AnualCalculoStrategy implements CalculoContribucionStrategy {
    private $contribucionRepository;
    
    public function __construct(ContribucionRepository $contribucionRepository) {
        $this->contribucionRepository = $contribucionRepository;
    }
    
    public function execute(array $data): array {
        $año = $data['año'] ?? date('Y');
        
        $fechaInicio = $año . '-01-01';
        $fechaFin = $año . '-12-31';
        
        // ✅ ARREGLAR: Verificar que no sean null
        $totalGeneral = $this->contribucionRepository->getTotalContribuciones($fechaInicio, $fechaFin);
        $totalGeneral = $totalGeneral ?? 0;
        
        $resumenPorTipo = $this->contribucionRepository->getResumenPorTipo($fechaInicio, $fechaFin);
        $resumenPorTipo = $resumenPorTipo ?? [];
        
        $resumenPorMes = $this->contribucionRepository->getResumenPorMes($año);
        $resumenPorMes = $resumenPorMes ?? [];
        
        $contribucionesDelAño = $this->contribucionRepository->buscarContribuciones('', null, $fechaInicio, $fechaFin);
        $contribucionesDelAño = $contribucionesDelAño ?? [];
        
        $cantidadContribuciones = count($contribucionesDelAño);
        
        $mesesTranscurridos = $this->getMesesTranscurridos($año);
        $promedioMensual = $mesesTranscurridos > 0 ? $totalGeneral / $mesesTranscurridos : 0;
        $promedioPorContribucion = $cantidadContribuciones > 0 ? $totalGeneral / $cantidadContribuciones : 0;
        
        return [
            'tipo_calculo' => 'anual',
            'periodo' => [
                'año' => $año,
                'fecha_inicio' => $fechaInicio,
                'fecha_fin' => $fechaFin,
                'meses_transcurridos' => $mesesTranscurridos
            ],
            'totales' => [
                'total_general' => round($totalGeneral, 2),
                'cantidad_contribuciones' => $cantidadContribuciones,
                'promedio_mensual' => round($promedioMensual, 2),
                'promedio_por_contribucion' => round($promedioPorContribucion, 2)
            ],
            'desglose_por_tipo' => $resumenPorTipo,
            'resumen_por_mes' => $this->formatearResumenMensual($resumenPorMes),
            'estadisticas' => [
                'proyeccion_anual' => $mesesTranscurridos > 0 ? round(($totalGeneral / $mesesTranscurridos) * 12, 2) : 0
            ]
        ];
    }
    
    private function getMesesTranscurridos(int $año): int {
        $añoActual = date('Y');
        $mesActual = date('n');
        
        if ($año < $añoActual) {
            return 12;
        } elseif ($año == $añoActual) {
            return $mesActual;
        } else {
            return 0;
        }
    }
    
    private function formatearResumenMensual(array $resumenPorMes): array {
        $resultado = [];
        
        for ($mes = 1; $mes <= 12; $mes++) {
            $mesData = array_filter($resumenPorMes, function($item) use ($mes) {
                return isset($item['mes']) && $item['mes'] == $mes;
            });
            
            $total = !empty($mesData) ? reset($mesData)['total'] : 0;
            
            $resultado[] = [
                'mes' => $mes,
                'nombre_mes' => $this->getNombreMes($mes),
                'total' => round($total, 2)
            ];
        }
        
        return $resultado;
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