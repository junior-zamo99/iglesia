<?php

namespace Iglesia\Config;

return [
    'GET' => [
        '/miembros' => ['Iglesia\Presentacion\Controllers\ControladorMiembro', 'listar'],
        '/miembros/crear' => ['Iglesia\Presentacion\Controllers\ControladorMiembro', 'mostrarFormulario'],
        '/miembros/eliminar' => ['Iglesia\Presentacion\Controllers\ControladorMiembro', 'eliminar'],
        '/miembros/editar' => ['Iglesia\Presentacion\Controllers\ControladorMiembro', 'mostrarFormularioEdicion'],

         // ministerios
         '/ministerios' => ['Iglesia\Presentacion\Controllers\ControladorMinisterio', 'listar'],
         '/ministerios/crear' => ['Iglesia\Presentacion\Controllers\ControladorMinisterio', 'mostrarFormulario'],
         '/ministerios/editar' => ['Iglesia\Presentacion\Controllers\ControladorMinisterio', 'mostrarFormularioEdicion'],
         '/ministerios/eliminar' => ['Iglesia\Presentacion\Controllers\ControladorMinisterio', 'eliminar'],
         '/ministerios/ver' => ['Iglesia\Presentacion\Controllers\ControladorMinisterio', 'ver'],
         '/ministerios/asignar-miembro' => ['Iglesia\Presentacion\Controllers\ControladorMinisterio', 'mostrarFormularioAsignarMiembro'],

         //eventos
         '/eventos' => ['Iglesia\Presentacion\Controllers\ControladorEvento', 'listar'],
        '/eventos/crear' => ['Iglesia\Presentacion\Controllers\ControladorEvento', 'mostrarFormulario'],
        '/eventos/editar' => ['Iglesia\Presentacion\Controllers\ControladorEvento', 'mostrarFormularioEdicion'],
        '/eventos/eliminar' => ['Iglesia\Presentacion\Controllers\ControladorEvento', 'eliminar'],
        '/eventos/ver' => ['Iglesia\Presentacion\Controllers\ControladorEvento', 'ver'],
        '/eventos/asistencia' => ['Iglesia\Presentacion\Controllers\ControladorEvento', 'mostrarFormularioAsistencia'],
        '/eventos/buscar' => ['Iglesia\Presentacion\Controllers\ControladorEvento', 'buscar'],

        '/bautizos' => ['Iglesia\Presentacion\Controllers\ControladorBautizo', 'listar'],
        '/bautizos/crear' => ['Iglesia\Presentacion\Controllers\ControladorBautizo', 'mostrarFormulario'],
        '/bautizos/editar' => ['Iglesia\Presentacion\Controllers\ControladorBautizo', 'mostrarFormularioEdicion'],
        '/bautizos/eliminar' => ['Iglesia\Presentacion\Controllers\ControladorBautizo', 'eliminar'],
        '/bautizos/ver' => ['Iglesia\Presentacion\Controllers\ControladorBautizo', 'ver'],
        '/bautizos/buscar' => ['Iglesia\Presentacion\Controllers\ControladorBautizo', 'buscar'],

        '/contribuciones' => ['Iglesia\Presentacion\Controllers\ControladorContribucion', 'listar'],
        '/contribuciones/crear' => ['Iglesia\Presentacion\Controllers\ControladorContribucion', 'mostrarFormulario'],
        '/contribuciones/editar' => ['Iglesia\Presentacion\Controllers\ControladorContribucion', 'mostrarFormularioEdicion'],
        '/contribuciones/eliminar' => ['Iglesia\Presentacion\Controllers\ControladorContribucion', 'eliminar'],
        '/contribuciones/ver' => ['Iglesia\Presentacion\Controllers\ControladorContribucion', 'ver'],
        '/contribuciones/buscar' => ['Iglesia\Presentacion\Controllers\ControladorContribucion', 'buscar'],

      '/contribuciones/dashboard' => ['Iglesia\Presentacion\Controllers\ControladorContribucion', 'dashboard'],
        '/contribuciones/strategy-mensual' => ['Iglesia\Presentacion\Controllers\ControladorContribucion', 'strategyMensual'],
        '/contribuciones/strategy-anual' => ['Iglesia\Presentacion\Controllers\ControladorContribucion', 'strategyAnual'],
        '/contribuciones/strategy-tipo' => ['Iglesia\Presentacion\Controllers\ControladorContribucion', 'strategyTipo'],
        '/contribuciones/comparar' => ['Iglesia\Presentacion\Controllers\ControladorContribucion', 'compararEstrategias'],
         '/contribuciones/strategy-personalizado' => ['Iglesia\Presentacion\Controllers\ControladorContribucion', 'strategyPersonalizado'],
    ],
    'POST' => [
        '/miembros/crear' => ['Iglesia\Presentacion\Controllers\ControladorMiembro', 'crear'],
        '/miembros/actualizar' => ['Iglesia\Presentacion\Controllers\ControladorMiembro', 'actualizar'],
         //ministerios
         '/ministerios/crear' => ['Iglesia\Presentacion\Controllers\ControladorMinisterio', 'crear'],
         '/ministerios/actualizar' => ['Iglesia\Presentacion\Controllers\ControladorMinisterio', 'actualizar'],
         '/ministerios/asignar-miembro' => ['Iglesia\Presentacion\Controllers\ControladorMinisterio', 'asignarMiembro'],
         '/ministerios/remover-miembro' => ['Iglesia\Presentacion\Controllers\ControladorMinisterio', 'removerMiembro'],

         // Rutas POST de eventos
        '/eventos/crear' => ['Iglesia\Presentacion\Controllers\ControladorEvento', 'crear'],
        '/eventos/actualizar' => ['Iglesia\Presentacion\Controllers\ControladorEvento', 'actualizar'],
        '/eventos/registrar-asistencia' => ['Iglesia\Presentacion\Controllers\ControladorEvento', 'registrarAsistencia'],
        '/eventos/eliminar-asistencia' => ['Iglesia\Presentacion\Controllers\ControladorEvento', 'eliminarAsistencia'],

        '/bautizos/crear' => ['Iglesia\Presentacion\Controllers\ControladorBautizo', 'crear'],
        '/bautizos/actualizar' => ['Iglesia\Presentacion\Controllers\ControladorBautizo', 'actualizar'],

        '/contribuciones/crear' => ['Iglesia\Presentacion\Controllers\ControladorContribucion', 'crear'],
        '/contribuciones/actualizar' => ['Iglesia\Presentacion\Controllers\ControladorContribucion', 'actualizar'],
    ],
    
];