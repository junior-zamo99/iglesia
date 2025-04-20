<link rel="stylesheet" href="../../css/listarMiembro.css">

<div class="container">
    <section class="content-section">
        <div class="content-header">
            <h2 class="content-title">Detalles de Contribución</h2>
            <div class="button-group">
                <a href="/contribuciones" class="btn btn-secondary">Volver a la lista</a>
                <a href="/contribuciones/editar?id=<?php echo $contribucion->getId(); ?>" class="btn btn-warning">Editar</a>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h3>Información de la Contribución</h3>
            </div>
            <div class="card-body">
                <div class="info-group">
                    <p>
                        <strong>Fecha:</strong> 
                        <?php 
                            $fechaContribucion = new DateTime($contribucion->getFecha());
                            echo $fechaContribucion->format('d/m/Y'); 
                        ?>
                    </p>
                </div>
                
                <div class="info-group">
                    <p><strong>Miembro:</strong> <?php echo htmlspecialchars($miembro->getNombre() . ' ' . $miembro->getApellido()); ?></p>
                </div>
                
                <div class="info-group">
                    <p><strong>Tipo:</strong> <?php echo htmlspecialchars($contribucion->getTipo()); ?></p>
                </div>
                
                <div class="info-group">
                    <p><strong>Monto:</strong> $<?php echo number_format($contribucion->getMonto(), 2); ?></p>
                </div>
                
                <?php if ($contribucion->getDescripcion()): ?>
                <div class="info-group">
                    <p><strong>Descripción:</strong></p>
                    <p><?php echo nl2br(htmlspecialchars($contribucion->getDescripcion())); ?></p>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="card mt-4">
            <div class="card-header">
                <h3>Información del Miembro</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>Nombre:</strong> <?php echo htmlspecialchars($miembro->getNombre()); ?></p>
                        <p><strong>Apellido:</strong> <?php echo htmlspecialchars($miembro->getApellido()); ?></p>
                        <p><strong>Email:</strong> <?php echo htmlspecialchars($miembro->getEmail() ?: 'No especificado'); ?></p>
                        <p><strong>Teléfono:</strong> <?php echo htmlspecialchars($miembro->getTelefono() ?: 'No especificado'); ?></p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Estado Civil:</strong> <?php echo htmlspecialchars($miembro->getEstadoCivil() ?: 'No especificado'); ?></p>
                        <p><strong>Dirección:</strong> <?php echo htmlspecialchars($miembro->getDireccion() ?: 'No especificada'); ?></p>
                        <p>
                            <strong>Fecha de Registro:</strong> 
                            <?php 
                                if ($miembro->getFechaRegistro()) {
                                    $fechaReg = new DateTime($miembro->getFechaRegistro());
                                    echo $fechaReg->format('d/m/Y');
                                } else {
                                    echo 'No especificada';
                                }
                            ?>
                        </p>
                    </div>
                </div>
                <div class="button-group mt-3">
                    <a href="/miembros/ver?id=<?php echo $miembro->getId(); ?>" class="btn btn-info">Ver Perfil Completo</a>
                </div>
            </div>
        </div>
    </section>
</div>