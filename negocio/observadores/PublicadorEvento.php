<?php

namespace Iglesia\Negocio\Observadores;

abstract class PublicadorEvento {
    private $suscriptores = [];
    protected $estadoPrincipal;
    protected $datosEventoActual = [];
    
    public function suscribir(SuscriptorNotificacionEvento $suscriptor): void {
        $this->suscriptores[] = $suscriptor;
    }
    
    public function desuscribir(SuscriptorNotificacionEvento $suscriptor): void {
        $clave = array_search($suscriptor, $this->suscriptores, true);
        if ($clave !== false) {
            unset($this->suscriptores[$clave]);
        }
    }
    
    protected function notificarSuscriptores(): void {
        foreach ($this->suscriptores as $suscriptor) {
            $suscriptor->actualizar($this->obtenerContexto());
        }
    }
    
    protected function obtenerContexto(): array {
        return [
            'tipo_evento' => $this->estadoPrincipal,
            'evento_id' => $this->datosEventoActual['id'] ?? null,
            'datos_evento' => $this->datosEventoActual,
            'fecha_hora' => date('Y-m-d H:i:s'),
            'usuario_id' => 'junior-zamo99'
        ];
    }
    
    abstract public function logicaNegocioPrincipal();
}