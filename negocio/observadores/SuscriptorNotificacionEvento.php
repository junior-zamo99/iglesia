<?php

namespace Iglesia\Negocio\Observadores;

interface SuscriptorNotificacionEvento {
    public function actualizar(array $contexto): void;
}