<?php
// presentacion/controllers/ControladorContribucion.php

namespace Iglesia\Presentacion\Controllers;

use DateTime;
use Iglesia\Negocio\Services\ContribucionService;
use Iglesia\Negocio\Services\MiembroService;
use Iglesia\Negocio\Services\CalculadoraContribuciones;
use Iglesia\Negocio\Strategies\MensualCalculoStrategy;
use Iglesia\Negocio\Strategies\AnualCalculoStrategy;
use Iglesia\Negocio\Strategies\PorTipoCalculoStrategy;
class ControladorContribucion {
    private $contribucionService;
    private $miembroService;
    private $calculadora;
    
    public function __construct() {
        $this->contribucionService = new ContribucionService();
        $this->miembroService = new MiembroService();
        $this->calculadora = new CalculadoraContribuciones();
    }
    
    public function listar() {
        $filtro = $_GET['filtro'] ?? 'todas';
        $fechaInicio = $_GET['fecha_inicio'] ?? '';
        $fechaFin = $_GET['fecha_fin'] ?? '';
        
        switch ($filtro) {
            case 'mes_actual':
                $fechaInicio = date('Y-m-01');
                $fechaFin = date('Y-m-t');
                $contribuciones = $this->contribucionService->buscarContribuciones('', null, $fechaInicio, $fechaFin);
                break;
                
            case 'anio_actual':
                $fechaInicio = date('Y-01-01');
                $fechaFin = date('Y-12-31');
                $contribuciones = $this->contribucionService->buscarContribuciones('', null, $fechaInicio, $fechaFin);
                break;
                
            default:
                $contribuciones = $this->contribucionService->obtenerTodas();
                break;
        }
        
        $tiposContribucion = $this->contribucionService->obtenerTiposContribucion();
        $totalContribuciones = $this->contribucionService->obtenerTotalContribuciones($fechaInicio, $fechaFin);
        $resumenPorTipo = $this->contribucionService->obtenerResumenPorTipo($fechaInicio, $fechaFin);
        
        $titulo = 'Lista de Contribuciones';
        $vista = __DIR__ . '/../views/contribuciones/lista.php';
        
        require_once __DIR__ . '/../views/layout/main.php';
    }
    
    public function mostrarFormulario() {
        $miembros = $this->miembroService->obtenerTodos();
        $tiposContribucion = $this->contribucionService->obtenerTiposContribucion();
        
        $titulo = 'Registrar Contribución';
        $vista = __DIR__ . '/../views/contribuciones/crear.php';
        
        require_once __DIR__ . '/../views/layout/main.php';
    }
    
    public function crear() {
        $miembroId = $_POST['miembro_id'] ?? null;
        $fecha = $_POST['fecha'] ?? date('Y-m-d');
        $monto = $_POST['monto'] ?? '';
        $tipo = $_POST['tipo'] ?? '';
        $descripcion = $_POST['descripcion'] ?? '';
        
        $errores = $this->contribucionService->validarContribucion($miembroId, $fecha, $monto, $tipo);
        
        if (!empty($errores)) {
            $miembros = $this->miembroService->obtenerTodos();
            $tiposContribucion = $this->contribucionService->obtenerTiposContribucion();
            $titulo = 'Registrar Contribución';
            $vista = __DIR__ . '/../views/contribuciones/crear.php';
            require_once __DIR__ . '/../views/layout/main.php';
            return;
        }
        
        $resultado = $this->contribucionService->crear($miembroId, $fecha, $monto, $tipo, $descripcion);
        
        if ($resultado) {
            header('Location: /contribuciones?mensaje=creada');
            exit;
        } else {
            $error = 'No se pudo registrar la contribución';
            $miembros = $this->miembroService->obtenerTodos();
            $tiposContribucion = $this->contribucionService->obtenerTiposContribucion();
            $titulo = 'Registrar Contribución';
            $vista = __DIR__ . '/../views/contribuciones/crear.php';
            require_once __DIR__ . '/../views/layout/main.php';
        }
    }
    
    public function eliminar() {
        $id = $_GET['id'] ?? null;
        
        if (!$id) {
            header('Location: /contribuciones?error=id_no_proporcionado');
            exit;
        }
        
        $resultado = $this->contribucionService->eliminar($id);
        
        if ($resultado) {
            header('Location: /contribuciones?mensaje=eliminada');
        } else {
            header('Location: /contribuciones?error=no_se_pudo_eliminar');
        }
        exit;
    }
    
    public function mostrarFormularioEdicion() {
        $id = $_GET['id'] ?? null;
        
        if (!$id) {
            header('Location: /contribuciones?error=id_no_proporcionado');
            exit;
        }
        
        $contribucion = $this->contribucionService->obtenerPorId($id);
        
        if (!$contribucion) {
            header('Location: /contribuciones?error=contribucion_no_encontrada');
            exit;
        }
        
        $miembros = $this->miembroService->obtenerTodos();
        $tiposContribucion = $this->contribucionService->obtenerTiposContribucion();
        
        $titulo = 'Editar Contribución';
        $vista = __DIR__ . '/../views/contribuciones/editar.php';
        
        require_once __DIR__ . '/../views/layout/main.php';
    }
    
    public function actualizar() {
        $id = $_POST['id'] ?? null;
        
        if (!$id) {
            header('Location: /contribuciones?error=id_no_proporcionado');
            exit;
        }
        
        $miembroId = $_POST['miembro_id'] ?? null;
        $fecha = $_POST['fecha'] ?? '';
        $monto = $_POST['monto'] ?? '';
        $tipo = $_POST['tipo'] ?? '';
        $descripcion = $_POST['descripcion'] ?? '';
        
        $errores = $this->contribucionService->validarContribucion($miembroId, $fecha, $monto, $tipo);
        
        if (!empty($errores)) {
            $contribucion = $this->contribucionService->obtenerPorId($id);
            $miembros = $this->miembroService->obtenerTodos();
            $tiposContribucion = $this->contribucionService->obtenerTiposContribucion();
            $titulo = 'Editar Contribución';
            $vista = __DIR__ . '/../views/contribuciones/editar.php';
            require_once __DIR__ . '/../views/layout/main.php';
            return;
        }
        
        $resultado = $this->contribucionService->actualizar($id, $miembroId, $fecha, $monto, $tipo, $descripcion);
        
        if ($resultado) {
            header('Location: /contribuciones?mensaje=actualizada');
        } else {
            $error = 'No se pudo actualizar la contribución';
            $contribucion = $this->contribucionService->obtenerPorId($id);
            $miembros = $this->miembroService->obtenerTodos();
            $tiposContribucion = $this->contribucionService->obtenerTiposContribucion();
            $titulo = 'Editar Contribución';
            $vista = __DIR__ . '/../views/contribuciones/editar.php';
            require_once __DIR__ . '/../views/layout/main.php';
        }
    }
    
    public function ver() {
        $id = $_GET['id'] ?? null;
        
        if (!$id) {
            header('Location: /contribuciones?error=id_no_proporcionado');
            exit;
        }
        
        $contribucion = $this->contribucionService->obtenerPorId($id);
        
        if (!$contribucion) {
            header('Location: /contribuciones?error=contribucion_no_encontrada');
            exit;
        }
        
        // Obtener información del miembro
        $miembro = $this->miembroService->obtenerPorId($contribucion->getMiembroId());
        
        $titulo = 'Detalles de Contribución';
        $vista = __DIR__ . '/../views/contribuciones/ver.php';
        
        require_once __DIR__ . '/../views/layout/main.php';
    }
    
    public function buscar() {
        $termino = $_GET['termino'] ?? '';
        $tipo = $_GET['tipo'] ?? '';
        $fechaInicio = $_GET['fecha_inicio'] ?? '';
        $fechaFin = $_GET['fecha_fin'] ?? '';
        
        $contribuciones = $this->contribucionService->buscarContribuciones($termino, $tipo, $fechaInicio, $fechaFin);
        $tiposContribucion = $this->contribucionService->obtenerTiposContribucion();
        $totalContribuciones = $this->contribucionService->obtenerTotalContribuciones($fechaInicio, $fechaFin);
        
        $titulo = 'Resultados de Búsqueda de Contribuciones';
        $vista = __DIR__ . '/../views/contribuciones/busqueda.php';
        
        require_once __DIR__ . '/../views/layout/main.php';
    }

public function dashboard() {
        try {
           
            $titulo = '💰 Dashboard Financiero - Strategy Pattern';
            $vista = __DIR__ . '/../views/contribuciones/dashboard_strategy.php';
            require_once __DIR__ . '/../views/layout/main.php';
            
        } catch (\Exception $e) {
            $this->mostrarError('Error al cargar dashboard', $e->getMessage());
        }
    }
   
    public function strategyMensual() {
        try {
            $mes = $_GET['mes'] ?? date('n');
            $año = $_GET['año'] ?? date('Y');
          
            $strategy = new MensualCalculoStrategy($this->contribucionService->contribucionRepository);
            $this->calculadora->setStrategy($strategy);
            
            $resultadoStrategy = $this->calculadora->calcular([
                'mes' => $mes,
                'año' => $año
            ]);
            
            $estrategiaActual = 'mensual';
            
            $titulo = '📅 Análisis Mensual - Strategy Pattern';
            $vista = __DIR__ . '/../views/contribuciones/dashboard_strategy.php';
            require_once __DIR__ . '/../views/layout/main.php';
            
        } catch (\Exception $e) {
            $this->mostrarError('Error en análisis mensual', $e->getMessage());
        }
    }
 
    public function strategyAnual() {
        try {
            $año = $_GET['año'] ?? date('Y');
            
           
            $strategy = new AnualCalculoStrategy($this->contribucionService->contribucionRepository);
            $this->calculadora->setStrategy($strategy);
            
            $resultadoStrategy = $this->calculadora->calcular([
                'año' => $año
            ]);
            
            $estrategiaActual = 'anual';
            
            $titulo = '📊 Análisis Anual - Strategy Pattern';
            $vista = __DIR__ . '/../views/contribuciones/dashboard_strategy.php';
            require_once __DIR__ . '/../views/layout/main.php';
            
        } catch (\Exception $e) {
            $this->mostrarError('Error en análisis anual', $e->getMessage());
        }
    }
    
 
    public function strategyTipo() {
        try {
            $fechaInicio = $_GET['fecha_inicio'] ?? date('Y-01-01');
            $fechaFin = $_GET['fecha_fin'] ?? date('Y-12-31');
            
            
            $strategy = new PorTipoCalculoStrategy($this->contribucionService->contribucionRepository);
            $this->calculadora->setStrategy($strategy);
            
            $resultadoStrategy = $this->calculadora->calcular([
                'fecha_inicio' => $fechaInicio,
                'fecha_fin' => $fechaFin
            ]);
            
            $estrategiaActual = 'tipo';
            
            $titulo = '🏷️ Análisis por Tipo - Strategy Pattern';
            $vista = __DIR__ . '/../views/contribuciones/dashboard_strategy.php';
            require_once __DIR__ . '/../views/layout/main.php';
            
        } catch (\Exception $e) {
            $this->mostrarError('Error en análisis por tipo', $e->getMessage());
        }
    }
    
    /**
     * Comparar estrategias
     */
    public function compararEstrategias() {
        try {
            $estrategia1 = $_GET['estrategia1'] ?? 'mensual';
            $estrategia2 = $_GET['estrategia2'] ?? 'anual';
            
            
            $resultado1 = $this->ejecutarEstrategiaPorNombre($estrategia1);
            
           
            $resultado2 = $this->ejecutarEstrategiaPorNombre($estrategia2);
            
            $comparacionDatos = [
                'estrategia_1' => [
                    'nombre' => $estrategia1,
                    'resultado' => $resultado1
                ],
                'estrategia_2' => [
                    'nombre' => $estrategia2,
                    'resultado' => $resultado2
                ],
                'comparacion' => $this->generarComparacion($resultado1, $resultado2)
            ];
            
            $titulo = '⚖️ Comparación de Estrategias';
            $vista = __DIR__ . '/../views/contribuciones/comparacion_estrategias.php';
            require_once __DIR__ . '/../views/layout/main.php';
            
        } catch (\Exception $e) {
            $this->mostrarError('Error en comparación', $e->getMessage());
        }
    }
    
 
    
    private function ejecutarEstrategiaPorNombre(string $nombreEstrategia): array {
        switch ($nombreEstrategia) {
            case 'mensual':
                $strategy = new MensualCalculoStrategy($this->contribucionService->contribucionRepository);
                return $strategy->execute(['mes' => date('n'), 'año' => date('Y')]);
                
            case 'anual':
                $strategy = new AnualCalculoStrategy($this->contribucionService->contribucionRepository);
                return $strategy->execute(['año' => date('Y')]);
                
            case 'tipo':
                $strategy = new PorTipoCalculoStrategy($this->contribucionService->contribucionRepository);
                return $strategy->execute([
                    'fecha_inicio' => date('Y-01-01'),
                    'fecha_fin' => date('Y-12-31')
                ]);
                
            default:
                throw new \InvalidArgumentException("Estrategia '$nombreEstrategia' no válida");
        }
    }
    
    private function generarComparacion(array $resultado1, array $resultado2): array {
        $total1 = $resultado1['totales']['total_general'];
        $total2 = $resultado2['totales']['total_general'];
        
        $diferencia = $total1 - $total2;
        $porcentajeDiferencia = $total2 > 0 ? (($diferencia / $total2) * 100) : 0;
        
        return [
            'diferencia_absoluta' => $diferencia,
            'diferencia_porcentual' => round($porcentajeDiferencia, 2),
            'mayor_total' => $total1 > $total2 ? 'estrategia_1' : 'estrategia_2',
            'resumen' => $this->generarResumenComparacion($diferencia, $porcentajeDiferencia)
        ];
    }
    
    private function generarResumenComparacion(float $diferencia, float $porcentaje): string {
        if (abs($diferencia) < 0.01) {
            return 'Los totales son prácticamente iguales';
        } elseif ($diferencia > 0) {
            return "La primera estrategia muestra $" . number_format(abs($diferencia), 2) . " más (" . abs($porcentaje) . "% superior)";
        } else {
            return "La segunda estrategia muestra $" . number_format(abs($diferencia), 2) . " más (" . abs($porcentaje) . "% superior)";
        }
    }
    
    private function mostrarError(string $titulo, string $mensaje): void {
        $error = $mensaje;
        $titulo = $titulo;
        $vista = __DIR__ . '/../views/contribuciones/error_strategy.php';
        require_once __DIR__ . '/../views/layout/main.php';
    }

    public function strategyPersonalizado() {
    try {
        $fechaInicio = $_GET['fecha_inicio'] ?? date('Y-m-01');
        $fechaFin = $_GET['fecha_fin'] ?? date('Y-m-d');
        
        
        $repository = new \Iglesia\Datos\Repositories\ContribucionRepository();
        $strategy = new \Iglesia\Negocio\Strategies\PersonalizadoCalculoStrategy($repository);
        $this->calculadora->setStrategy($strategy);
        
        $resultadoStrategy = $this->calculadora->calcular([
            'fecha_inicio' => $fechaInicio,
            'fecha_fin' => $fechaFin
        ]);
        
        $estrategiaActual = 'personalizado';
        
        $titulo = '📅 Análisis Personalizado - Strategy Pattern';
        $vista = __DIR__ . '/../views/contribuciones/dashboard_personalizado.php';
        require_once __DIR__ . '/../views/layout/main.php';
        
    } catch (\Exception $e) {
        $this->mostrarError('Error en análisis personalizado', $e->getMessage());
    }
}


}