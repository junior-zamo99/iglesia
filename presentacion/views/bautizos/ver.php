<link rel="stylesheet" href="../../css/listarMiembro.css">

<div class="container">
    <section class="content-section">
        <div class="content-header">
            <h2 class="content-title">Detalles del Bautizo</h2>
            <div class="button-group">
                <a href="/bautizos" class="btn btn-secondary">Volver a la lista</a>
                <a href="/bautizos/editar?id=<?php echo $bautizo->getId(); ?>" class="btn btn-warning">Editar</a>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h3>Información del Bautizo</h3>
            </div>
            <div class="card-body">
                <div class="info-group">
                    <p><strong>Miembro:</strong> <?php echo htmlspecialchars($miembro->getNombre() . ' ' . $miembro->getApellido()); ?></p>
                </div>
                
                <div class="info-group">
                    <p>
                        <strong>Fecha de Bautizo:</strong> 
                        <?php 
                            $fechaBautizo = new DateTime($bautizo->getFecha());
                            echo $fechaBautizo->format('d/m/Y'); 
                        ?>
                    </p>
                </div>
                
                <div class="info-group">
                    <p><strong>Lugar:</strong> <?php echo htmlspecialchars($bautizo->getLugar() ?: 'No especificado'); ?></p>
                </div>
                
                <div class="info-group">
                    <p><strong>Pastor Oficiante:</strong> <?php echo htmlspecialchars($bautizo->getPastor() ?: 'No especificado'); ?></p>
                </div>
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
                        <p>
                            <strong>Fecha de Nacimiento:</strong> 
                            <?php 
                                if ($miembro->getFechaNacimiento()) {
                                    $fechaNac = new DateTime($miembro->getFechaNacimiento());
                                    echo $fechaNac->format('d/m/Y');
                                } else {
                                    echo 'No especificada';
                                }
                            ?>
                        </p>
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