<?php

namespace Iglesia\Negocio\Strategies;

interface CalculoContribucionStrategy {
    /**
     * Ejecuta el cálculo de contribuciones según la estrategia específica
     * 
     * @param array $data Datos para el cálculo (fechas, filtros, etc.)
     * @return array Resultado del cálculo con totales y detalles
     */
    public function execute(array $data): array;
}