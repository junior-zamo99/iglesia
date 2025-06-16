<?php

namespace Iglesia\Negocio\Strategies;

use Iglesia\Datos\Repositories\ContribucionRepository;

class PersonalizadoCalculoStrategy implements CalculoContribucionStrategy {
    private $contribucionRepository;
    
    public function __construct(ContribucionRepository $contribucionRepository) {
        $this->contribucionRepository = $contribucionRepository;
    }
    
    public function execute(array $data): array {
        $fechaInicio = $data['fecha_inicio'] ?? date('Y-m-01');
        $fechaFin = $data['fecha_fin'] ?? date('Y-m-d');
        
        // Validar que fecha_inicio no sea mayor que fecha_fin
        if (strtotime($fechaInicio) > strtotime($fechaFin)) {
            throw new \InvalidArgumentException('La fecha de inicio no puede ser mayor que la fecha de fin');
        }
        
        // Obtener datos
        $totalGeneral = $this->contribucionRepository->getTotalContribuciones($fechaInicio, $fechaFin);
        $totalGeneral = $totalGeneral ?? 0;
        
        $resumenPorTipo = $this->contribucionRepository->getResumenPorTipo($fechaInicio, $fechaFin);
        $resumenPorTipo = $resumenPorTipo ?? [];
        
        $contribuciones = $this->contribucionRepository->buscarContribuciones('', null, $fechaInicio, $fechaFin);
        $contribuciones = $contribuciones ?? [];
        
        // Calcular estadísticas
        $cantidadContribuciones = count($contribuciones);
        $promedioPorContribucion = $cantidadContribuciones > 0 ? $totalGeneral / $cantidadContribuciones : 0;
        
        // Calcular días en el período
        $diasEnPeriodo = $this->calcularDiasEnPeriodo($fechaInicio, $fechaFin);
        $promedioDiario = $diasEnPeriodo > 0 ? $totalGeneral / $diasEnPeriodo : 0;
        
        // Obtener contribución más alta y más baja
        $montos = array_column($contribuciones, 'monto');
        $contribucionMasAlta = !empty($montos) ? max($montos) : 0;
        $contribucionMasBaja = !empty($montos) ? min($montos) : 0;
        
        // Calcular crecimiento si hay datos históricos
        $crecimiento = $this->calcularCrecimiento($fechaInicio, $fechaFin, $totalGeneral);
        
        return [
            'tipo_calculo' => 'personalizado',
            'periodo' => [
                'fecha_inicio' => $fechaInicio,
                'fecha_fin' => $fechaFin,
                'descripcion' => $this->getDescripcionPeriodo($fechaInicio, $fechaFin),
                'dias_en_periodo' => $diasEnPeriodo,
                'tipo_periodo' => $this->getTipoPeriodo($fechaInicio, $fechaFin)
            ],
            'totales' => [
                'total_general' => round($totalGeneral, 2),
                'cantidad_contribuciones' => $cantidadContribuciones,
                'promedio_por_contribucion' => round($promedioPorContribucion, 2),
                'promedio_diario' => round($promedioDiario, 2),
                'contribucion_mas_alta' => round($contribucionMasAlta, 2),
                'contribucion_mas_baja' => round($contribucionMasBaja, 2)
            ],
            'desglose_por_tipo' => $resumenPorTipo,
            'estadisticas_avanzadas' => [
                'crecimiento' => $crecimiento,
                'distribucion_semanal' => $this->calcularDistribucionSemanal($contribuciones, $fechaInicio, $fechaFin),
                'dias_con_contribuciones' => $this->contarDiasConContribuciones($contribuciones),
                'proyeccion_mensual' => $diasEnPeriodo > 0 ? round(($totalGeneral / $diasEnPeriodo) * 30, 2) : 0
            ],
            'contribuciones_destacadas' => $this->getContribucionesDestacadas($contribuciones)
        ];
    }
    
    private function calcularDiasEnPeriodo(string $fechaInicio, string $fechaFin): int {
        $inicio = new \DateTime($fechaInicio);
        $fin = new \DateTime($fechaFin);
        $diferencia = $fin->diff($inicio);
        return $diferencia->days + 1; // +1 para incluir ambos días
    }
    
    private function getDescripcionPeriodo(string $fechaInicio, string $fechaFin): string {
        $inicio = new \DateTime($fechaInicio);
        $fin = new \DateTime($fechaFin);
        
        // Si es el mismo día
        if ($fechaInicio === $fechaFin) {
            return $inicio->format('d/m/Y');
        }
        
        // Si es el mismo mes
        if ($inicio->format('Y-m') === $fin->format('Y-m')) {
            return 'Del ' . $inicio->format('d') . ' al ' . $fin->format('d') . ' de ' . 
                   $this->getNombreMes($inicio->format('n')) . ' ' . $inicio->format('Y');
        }
        
        // Si es el mismo año
        if ($inicio->format('Y') === $fin->format('Y')) {
            return 'Del ' . $inicio->format('d/m') . ' al ' . $fin->format('d/m') . ' de ' . $inicio->format('Y');
        }
        
        // Período completo
        return 'Del ' . $inicio->format('d/m/Y') . ' al ' . $fin->format('d/m/Y');
    }
    
    private function getTipoPeriodo(string $fechaInicio, string $fechaFin): string {
        $dias = $this->calcularDiasEnPeriodo($fechaInicio, $fechaFin);
        
        if ($dias === 1) return 'Día específico';
        if ($dias <= 7) return 'Período semanal';
        if ($dias <= 31) return 'Período mensual';
        if ($dias <= 92) return 'Período trimestral';
        if ($dias <= 186) return 'Período semestral';
        if ($dias <= 366) return 'Período anual';
        return 'Período multi-anual';
    }
    
    private function calcularCrecimiento(string $fechaInicio, string $fechaFin, float $totalActual): array {
        // Calcular el período anterior de la misma duración
        $inicio = new \DateTime($fechaInicio);
        $fin = new \DateTime($fechaFin);
        $diferencia = $fin->diff($inicio);
        
        $fechaInicioAnterior = clone $inicio;
        $fechaInicioAnterior->sub($diferencia)->sub(new \DateInterval('P1D'));
        
        $fechaFinAnterior = clone $inicio;
        $fechaFinAnterior->sub(new \DateInterval('P1D'));
        
        $totalAnterior = $this->contribucionRepository->getTotalContribuciones(
            $fechaInicioAnterior->format('Y-m-d'),
            $fechaFinAnterior->format('Y-m-d')
        ) ?? 0;
        
        $diferenciaMonto = $totalActual - $totalAnterior;
        $porcentajeCrecimiento = $totalAnterior > 0 ? (($diferenciaMonto / $totalAnterior) * 100) : 0;
        
        return [
            'total_anterior' => round($totalAnterior, 2),
            'diferencia_monto' => round($diferenciaMonto, 2),
            'porcentaje_crecimiento' => round($porcentajeCrecimiento, 2),
            'tendencia' => $diferenciaMonto > 0 ? 'crecimiento' : ($diferenciaMonto < 0 ? 'decrecimiento' : 'estable'),
            'periodo_comparacion' => [
                'fecha_inicio' => $fechaInicioAnterior->format('Y-m-d'),
                'fecha_fin' => $fechaFinAnterior->format('Y-m-d')
            ]
        ];
    }
    
    private function calcularDistribucionSemanal(array $contribuciones, string $fechaInicio, string $fechaFin): array {
        $distribucion = ['Lunes' => 0, 'Martes' => 0, 'Miércoles' => 0, 'Jueves' => 0, 'Viernes' => 0, 'Sábado' => 0, 'Domingo' => 0];
        $diasSemana = ['Monday' => 'Lunes', 'Tuesday' => 'Martes', 'Wednesday' => 'Miércoles', 'Thursday' => 'Jueves', 'Friday' => 'Viernes', 'Saturday' => 'Sábado', 'Sunday' => 'Domingo'];
        
        foreach ($contribuciones as $contribucion) {
            $fecha = new \DateTime($contribucion['fecha']);
            $diaSemana = $fecha->format('l'); // Nombre del día en inglés
            if (isset($diasSemana[$diaSemana])) {
                $distribucion[$diasSemana[$diaSemana]] += $contribucion['monto'];
            }
        }
        
        return $distribucion;
    }
    
    private function contarDiasConContribuciones(array $contribuciones): int {
        $fechasUnicas = array_unique(array_column($contribuciones, 'fecha'));
        return count($fechasUnicas);
    }
    
    private function getContribucionesDestacadas(array $contribuciones): array {
        if (empty($contribuciones)) {
            return ['mas_alta' => null, 'mas_baja' => null, 'mas_reciente' => null];
        }
        
        // Ordenar por monto para obtener la más alta y más baja
        usort($contribuciones, function($a, $b) {
            return $b['monto'] <=> $a['monto'];
        });
        
        $masAlta = $contribuciones[0];
        $masBaja = $contribuciones[count($contribuciones) - 1];
        
        // Ordenar por fecha para obtener la más reciente
        usort($contribuciones, function($a, $b) {
            return strtotime($b['fecha']) <=> strtotime($a['fecha']);
        });
        
        $masReciente = $contribuciones[0];
        
        return [
            'mas_alta' => $masAlta,
            'mas_baja' => $masBaja,
            'mas_reciente' => $masReciente
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