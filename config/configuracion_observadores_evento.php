<?php

return [
    'notificaciones_email' => [
        'habilitado' => true,
        'email_admin' => 'admin@iglesia.com',
        'eventos_importantes' => ['Culto', 'Conferencia', 'Bautizo', 'Retiro', 'Reunión'],
        'enviar_por_tipo' => [
            'evento_creado' => true,
            'evento_actualizado' => true,
            'evento_eliminado' => true
        ]
    ]
];