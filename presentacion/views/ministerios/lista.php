<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Ministerios</title>
    <link rel="stylesheet" href="../../css/listarMiembro.css">
</head>
<body>
    <div class="container">
        <section class="content-section">
            <div class="content-header">
                <h2 class="content-title">Lista de Ministerios</h2>
                <a href="/ministerios/crear" class="btn btn-primary">Nuevo Ministerio</a>
            </div>

            <?php if (isset($_GET['mensaje']) && $_GET['mensaje'] === 'creado'): ?>
                <div class="alert alert-success">
                    <p>El ministerio ha sido creado exitosamente.</p>
                </div>
            <?php endif; ?>

            <?php if (isset($_GET['mensaje']) && $_GET['mensaje'] === 'actualizado'): ?>
                <div class="alert alert-success">
                    <p>El ministerio ha sido actualizado exitosamente.</p>
                </div>
            <?php endif; ?>

            <?php if (isset($_GET['mensaje']) && $_GET['mensaje'] === 'eliminado'): ?>
                <div class="alert alert-success">
                    <p>El ministerio ha sido eliminado exitosamente.</p>
                </div>
            <?php endif; ?>

            <?php if (isset($_GET['error'])): ?>
                <div class="alert alert-danger">
                    <p>
                        <?php
                        switch ($_GET['error']) {
                            case 'no_se_pudo_eliminar':
                                echo 'No se pudo eliminar el ministerio. Puede que tenga miembros asignados.';
                                break;
                            case 'id_no_proporcionado':
                                echo 'ID no proporcionado.';
                                break;
                            case 'ministerio_no_encontrado':
                                echo 'Ministerio no encontrado.';
                                break;
                            default:
                                echo 'Ha ocurrido un error.';
                        }
                        ?>
                    </p>
                </div>
            <?php endif; ?>

            <?php if (empty($ministerios)): ?>
                <div class="alert alert-info">
                    <p>No hay ministerios registrados.</p>
                </div>
            <?php else: ?>
                <div class="table-container">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nombre</th>
                                <th>Descripción</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($ministerios as $ministerio): ?>
                                <tr>
                                    <td><?php echo $ministerio->getId(); ?></td>
                                    <td><?php echo $ministerio->getNombre(); ?></td>
                                    <td><?php echo substr($ministerio->getDescripcion(), 0, 100) . (strlen($ministerio->getDescripcion()) > 100 ? '...' : ''); ?></td>
                                    <td class="actions-cell">
                                        <a href="/ministerios/ver?id=<?php echo $ministerio->getId(); ?>" class="btn btn-action btn-view">Ver</a>
                                        <a href="/ministerios/editar?id=<?php echo $ministerio->getId(); ?>" class="btn btn-action btn-edit">Editar</a>
                                        <a href="/ministerios/eliminar?id=<?php echo $ministerio->getId(); ?>" class="btn btn-action btn-delete" onclick="return confirm('¿Está seguro de eliminar este ministerio?')">Eliminar</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </section>
    </div>
</body>
</html>