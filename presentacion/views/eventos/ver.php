<link rel="stylesheet" href="../../css/listarMiembro.css">

<div class="container">
    <section class="content-section">
        <div class="content-header">
            <h2 class="content-title">Evento: <?php echo htmlspecialchars($evento->getNombre()); ?></h2>
            <div class="button-group">
                <a href="/eventos" class="btn btn-secondary">Volver a la lista</a>
                <a href="/eventos/editar?id=<?php echo $evento->getId(); ?>" class="btn btn-warning">Editar</a>
            </div>
        </div>

        <?php if (isset($_GET['mensaje'])): ?>
            <div class="alert alert-success">
                <p>
                    <?php
                    switch ($_GET['mensaje']) {
                        case 'asistencia_registrada':
                            echo 'Asistencia registrada exitosamente.';
                            break;
                        case 'asistencia_eliminada':
                            echo 'Asistencia eliminada exitosamente.';
                            break;
                        default:
                            echo 'Operación completada con éxito.';
                    }
                    ?>
                </p>
            </div>
        <?php endif; ?>

        <?php if (isset($_GET['error'])): ?>
            <div class="alert alert-danger">
                <p>
                    <?php
                    switch ($_GET['error']) {
                        case 'no_se_pudo_registrar':
                            echo 'No se pudo registrar la asistencia.';
                            break;
                        case 'no_se_pudo_eliminar_asistencia':
                            echo 'No se pudo eliminar la asistencia.';
                            break;
                        default:
                            echo 'Ha ocurrido un error.';
                    }
                    ?>
                </p>
            </div>
        <?php endif; ?>

        <div class="event-details">
            <div class="card">
                <div class="card-header">
                    <h3>Información del Evento</h3>
                </div>
                <div class="card-body">
                    <p><strong>Nombre:</strong> <?php echo htmlspecialchars($evento->getNombre()); ?></p>
                    <p>
                        <strong>Fecha:</strong> 
                        <?php 
                            $fechaEvento = new DateTime($evento->getFecha());
                            echo $fechaEvento->format('d/m/Y'); 
                        ?>
                    </p>
                    <p><strong>Ubicación:</strong> <?php echo htmlspecialchars($evento->getUbicacion() ?: 'No especificada'); ?></p>
                    <p><strong>Tipo:</strong> <?php echo htmlspecialchars($evento->getTipo() ?: 'No especificado'); ?></p>
                    <p><strong>Descripción:</strong></p>
                    <div class="event-description">
                        <?php echo nl2br(htmlspecialchars($evento->getDescripcion() ?: 'No hay descripción disponible.')); ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="event-members mt-4">
            <div class="header-with-button">
                <h3>Asistentes al Evento</h3>
                <a href="/eventos/asistencia?id=<?php echo $evento->getId(); ?>" class="btn btn-primary">Registrar Asistencia</a>
            </div>

            <?php if (empty($miembrosEvento)): ?>
                <div class="alert alert-info">
                    <p>Este evento no tiene asistentes registrados.</p>
                </div>
            <?php else: ?>
                <div class="table-container">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Nombre</th>
                                <th>Apellido</th>
                                <th>Email</th>
                                <th>Teléfono</th>
                                <th>Asistencia</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($miembrosEvento as $miembro): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($miembro['nombre']); ?></td>
                                    <td><?php echo htmlspecialchars($miembro['apellido']); ?></td>
                                    <td><?php echo htmlspecialchars($miembro['email']); ?></td>
                                    <td><?php echo htmlspecialchars($miembro['telefono']); ?></td>
                                    <td>
                                        <?php if ($miembro['asistencia']): ?>
                                            <span class="badge badge-success">Asistió</span>
                                        <?php else: ?>
                                            <span class="badge badge-warning">No asistió</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="actions-cell">
                                        <a href="/miembros/ver?id=<?php echo $miembro['id']; ?>" class="btn btn-action btn-view">Ver</a>
                                        <form action="/eventos/eliminar-asistencia" method="POST" style="display:inline;">
                                            <input type="hidden" name="evento_id" value="<?php echo $evento->getId(); ?>">
                                            <input type="hidden" name="miembro_id" value="<?php echo $miembro['id']; ?>">
                                            <button type="submit" class="btn btn-action btn-delete" onclick="return confirm('¿Está seguro de eliminar este registro de asistencia?')">Eliminar</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </section>
</div>