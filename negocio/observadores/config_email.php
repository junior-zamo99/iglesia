<?php

return [
    'habilitado' => true,
    'enviar_email_real' => true, 
    'enviar_por_tipo' => [
        'evento_creado' => true,
        'evento_actualizado' => true,
        'evento_eliminado' => true
    ],
    'smtp' => [
        'host' => 'smtp.gmail.com',
        'port' => 587,
        'encryption' => 'tls',
        'username' => 'junior.zf.99@gmail.com',        
        'password' => 'zabr ybid yuao wqgl',            
        'from_email' => 'junior.zf.99@gmail.com',      
        'from_name' => 'Sistema Iglesia - Local'
    ],
    'destinatarios' => [
        'admin' => 'jzamoadunay@gmail.com',           
        'desarrollo' => true                       
    ]
];