<link rel="stylesheet" href="../../css/listarMiembro.css">

<div class="container">
    <section class="content-section">
        <div class="content-header">
            <h2 class="content-title">Ministerio: <?php echo $ministerio->getNombre(); ?></h2>
            <div class="button-group">
                <a href="/ministerios" class="btn btn-secondary">Volver a la lista</a>
                <a href="/ministerios/editar?id=<?php echo $ministerio->getId(); ?>" class="btn btn-edit">Editar</a>
            </div>
        </div>

        <?php if (isset($_GET['mensaje'])): ?>
            <div class="alert alert-success">
                <p>
                    <?php
                    switch ($_GET['mensaje']) {
                        case 'miembro_asignado':
                            echo 'Miembro asignado al ministerio exitosamente.';
                            break;
                        case 'miembro_removido':
                            echo 'Miembro removido del ministerio exitosamente.';
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
                        case 'no_se_pudo_asignar':
                            echo 'No se pudo asignar el miembro al ministerio.';
                            break;
                        case 'no_se_pudo_remover':
                            echo 'No se pudo remover el miembro del ministerio.';
                            break;
                        default:
                            echo 'Ha ocurrido un error.';
                    }
                    ?>
                </p>
            </div>
        <?php endif; ?>

        <div class="ministry-details">
            <div class="card">
                <div class="card-header">
                    <h3>Información del Ministerio</h3>
                </div>
                <div class="card-body">
                    <p><strong>Nombre:</strong> <?php echo $ministerio->getNombre(); ?></p>
                    <p><strong>Descripción:</strong> <?php echo nl2br($ministerio->getDescripcion() ?: 'No hay descripción disponible.'); ?></p>
                </div>
            </div>
        </div>

        <div class="ministry-members mt-4">
            <div class="header-with-button">
                <h3>Miembros del Ministerio</h3>
                <a href="/ministerios/asignar-miembro?id=<?php echo $ministerio->getId(); ?>" class="btn btn-primary">Asignar Miembro</a>
            </div>

            <?php if (empty($miembrosMinisterio)): ?>
                <div class="alert alert-info">
                    <p>Este ministerio no tiene miembros asignados.</p>
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
                                <th>Fecha de Inicio</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($miembrosMinisterio as $miembro): ?>
                                <tr>
                                    <td><?php echo $miembro['nombre']; ?></td>
                                    <td><?php echo $miembro['apellido']; ?></td>
                                    <td><?php echo $miembro['email']; ?></td>
                                    <td><?php echo $miembro['telefono']; ?></td>
                                    <td><?php echo date('d/m/Y', strtotime($miembro['fechainicio'])); ?></td>
                                    <td class="actions-cell">
                                        <a href="/miembros/ver?id=<?php echo $miembro['id']; ?>" class="btn btn-action btn-view">Ver</a>
                                        <form action="/ministerios/remover-miembro" method="POST" style="display:inline;">
                                            <input type="hidden" name="ministerio_id" value="<?php echo $ministerio->getId(); ?>">
                                            <input type="hidden" name="miembro_id" value="<?php echo $miembro['id']; ?>">
                                            <button type="submit" class="btn btn-action btn-delete" onclick="return confirm('¿Está seguro de remover a este miembro del ministerio?')">Remover</button>
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