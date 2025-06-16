<?php

namespace Iglesia\Negocio\Services;

use Iglesia\Negocio\Strategies\CalculoContribucionStrategy;

class CalculadoraContribuciones {
    private $strategy;
    
    public function setStrategy(CalculoContribucionStrategy $strategy): void {
        $this->strategy = $strategy;
    }
    
    public function calcular(array $parametros = []): array {
        if (!$this->strategy) {
            throw new \Exception('No hay estrategia de cálculo configurada');
        }
        
        $resultado = $this->strategy->execute($parametros);
        $resultado['calculado_en'] = date('Y-m-d H:i:s');
        $resultado['estrategia_usada'] = get_class($this->strategy);
        
        return $resultado;
    }
}