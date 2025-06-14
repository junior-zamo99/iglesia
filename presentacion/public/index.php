<?php

require_once __DIR__ . '/../../vendor/autoload.php';

$rutas = require_once __DIR__ . '/../../config/rutas.php';


$metodo = $_SERVER['REQUEST_METHOD'];
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);


if (isset($rutas[$metodo][$uri])) {
 
    [$controlador, $accion] = $rutas[$metodo][$uri];
    
   
    if (class_exists($controlador)) {
     
        $instanciaControlador = new $controlador();
        $instanciaControlador->$accion();
    } else {
        http_response_code(500);
        echo "Error: Controlador no encontrado";
    }
} else {
    http_response_code(404);
    echo "Ruta no encontrada";
}