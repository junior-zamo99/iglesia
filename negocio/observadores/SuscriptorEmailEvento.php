<?php

namespace Iglesia\Negocio\Observadores;

class SuscriptorEmailEvento implements SuscriptorNotificacionEvento {
    private $emailAdmin;
    private $configuracion;
    
    public function __construct(string $emailAdmin = 'junior.zf.99@gmail.com') {
        $this->emailAdmin = $emailAdmin;
        $this->cargarConfiguracion();
    }
    
    public function actualizar(array $contexto): void {
        if (!$this->configuracion['habilitado']) {
            return;
        }
        
        $tipoEvento = $contexto['tipo_evento'];
        
        $enviarEsteEvento = $this->configuracion['enviar_por_tipo'][$tipoEvento] ?? false;
        if (!$enviarEsteEvento) {
            return;
        }
        
        $asunto = $this->construirAsunto($tipoEvento, $contexto['datos_evento']);
        $cuerpo = $this->construirCuerpo($tipoEvento, $contexto);
        
        $this->enviarEmail($asunto, $cuerpo);
    }
    
    private function construirAsunto(string $tipoEvento, array $datosEvento): string {
        $nombre = $datosEvento['nombre'] ?? 'Evento';
        
        switch ($tipoEvento) {
            case 'evento_creado':
                return "🎉 Nuevo Evento Programado - {$nombre}";
            case 'evento_actualizado':
                return "✏️ Cambios en Evento - {$nombre}";
            case 'evento_eliminado':
                return "❌ Evento Cancelado - {$nombre}";
            default:
                return "📅 Notificación de Evento - {$nombre}";
        }
    }
    
    private function construirCuerpo(string $tipoEvento, array $contexto): string {
        $datos = $contexto['datos_evento'];
        $fechaHora = $contexto['fecha_hora'];
        $usuario = $contexto['usuario_id'];
        
        $cuerpo = "Estimado Administrador,\n\n";
        
        switch ($tipoEvento) {
            case 'evento_creado':
                $cuerpo .= "Se ha programado un nuevo evento:\n\n";
                $cuerpo .= "📅 Evento: {$datos['nombre']}\n";
                $cuerpo .= "📅 Fecha: " . $this->formatearFecha($datos['fecha']) . "\n";
                $cuerpo .= "📍 Ubicación: {$datos['ubicacion']}\n";
                $cuerpo .= "🏷️ Tipo: {$datos['tipo']}\n";
                if (!empty($datos['descripcion'])) {
                    $cuerpo .= "\nDescripción: {$datos['descripcion']}\n";
                }
                $cuerpo .= "\nRegistrado por: {$usuario}\n";
                $cuerpo .= "Fecha de registro: " . $this->formatearFechaHora($fechaHora) . "\n";
                break;
                
            case 'evento_actualizado':
                $cuerpo .= "Se han realizado cambios en el evento:\n\n";
                $cuerpo .= "📅 Evento: {$datos['nombre']}\n";
                $cuerpo .= "📅 Fecha: " . $this->formatearFecha($datos['fecha']) . "\n";
                $cuerpo .= "📍 Ubicación: {$datos['ubicacion']}\n";
                $cuerpo .= "🏷️ Tipo: {$datos['tipo']}\n";
                if (!empty($datos['descripcion'])) {
                    $cuerpo .= "\nDescripción: {$datos['descripcion']}\n";
                }
                $cuerpo .= "\nModificado por: {$usuario}\n";
                $cuerpo .= "Fecha de modificación: " . $this->formatearFechaHora($fechaHora) . "\n";
                break;
                
            case 'evento_eliminado':
                $cuerpo .= "El evento \"{$datos['nombre']}\" ha sido cancelado.\n\n";
                $cuerpo .= "📅 Fecha original: " . $this->formatearFecha($datos['fecha']) . "\n";
                $cuerpo .= "📍 Ubicación: {$datos['ubicacion']}\n";
                $cuerpo .= "\nCancelado por: {$usuario}\n";
                $cuerpo .= "Fecha de cancelación: " . $this->formatearFechaHora($fechaHora) . "\n";
                break;
        }
        
        $cuerpo .= "\nSaludos,\nSistema de Gestión de Iglesia";
        
        return $cuerpo;
    }
    
    private function formatearFecha(string $fecha): string {
        $fechaObj = new \DateTime($fecha);
        $meses = [
            1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril',
            5 => 'Mayo', 6 => 'Junio', 7 => 'Julio', 8 => 'Agosto',
            9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre'
        ];
        
        $dia = $fechaObj->format('d');
        $mes = $meses[(int)$fechaObj->format('m')];
        $anio = $fechaObj->format('Y');
        
        return "{$dia} de {$mes}, {$anio}";
    }
    
    private function formatearFechaHora(string $fechaHora): string {
        $fechaObj = new \DateTime($fechaHora);
        return $this->formatearFecha($fechaObj->format('Y-m-d')) . ' a las ' . $fechaObj->format('H:i');
    }
    
    private function enviarEmail(string $asunto, string $cuerpo): void {
        // Enviar email real si está habilitado
        if ($this->configuracion['enviar_email_real'] ?? false) {
            $this->enviarEmailReal($asunto, $cuerpo);
        }
    }

    private function enviarEmailReal(string $asunto, string $cuerpo): void {
        try {
      
            require_once __DIR__ . '/../../vendor/autoload.php';
            
            $mail = new \PHPMailer\PHPMailer\PHPMailer(true);
            
        
            $mail->isSMTP();
            $mail->Host       = $this->configuracion['smtp']['host'];
            $mail->SMTPAuth   = true;
            $mail->Username   = $this->configuracion['smtp']['username'];
            $mail->Password   = $this->configuracion['smtp']['password'];
            $mail->SMTPSecure = $this->configuracion['smtp']['encryption'];
            $mail->Port       = $this->configuracion['smtp']['port'];
            
           
            $mail->setFrom(
                $this->configuracion['smtp']['from_email'], 
                $this->configuracion['smtp']['from_name']
            );
            
            $mail->addAddress($this->configuracion['destinatarios']['admin']);
            
         
            $asuntoCompleto = "[DESARROLLO] " . $asunto;
            
            $mail->isHTML(false);
            $mail->Subject = $asuntoCompleto;
            $mail->Body    = $cuerpo . "\n\n--- EMAIL ENVIADO DESDE DESARROLLO LOCAL ---";
            
            $mail->send();
            
        } catch (\Exception $e) {
          
        }
    }

    private function cargarConfiguracion(): void {
        $archivoConfig = __DIR__ . '/config_email.php';
        if (file_exists($archivoConfig)) {
            $this->configuracion = require $archivoConfig;
        } else {
            // Configuración por defecto
            $this->configuracion = [
                'habilitado' => true,
                'enviar_email_real' => false,
                'enviar_por_tipo' => [
                    'evento_creado' => true,
                    'evento_actualizado' => true,
                    'evento_eliminado' => true
                ]
            ];
        }
    }
}