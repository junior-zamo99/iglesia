<link rel="stylesheet" href="../../css/listarMiembro.css">

<div class="container">
    <section class="content-section">
        <div class="content-header">
            <h2 class="content-title">Registro de Bautizos</h2>
            <div class="button-group">
                <a href="/bautizos/crear" class="btn btn-primary">Nuevo Bautizo</a>
            </div>
        </div>

        <?php if (isset($_GET['mensaje'])): ?>
            <div class="alert alert-success">
                <p>
                    <?php
                    switch ($_GET['mensaje']) {
                        case 'creado':
                            echo 'El bautizo ha sido registrado exitosamente.';
                            break;
                        case 'actualizado':
                            echo 'La información del bautizo ha sido actualizada exitosamente.';
                            break;
                        case 'eliminado':
                            echo 'El registro de bautizo ha sido eliminado exitosamente.';
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
                        case 'no_se_pudo_crear':
                            echo 'No se pudo registrar el bautizo.';
                            break;
                        case 'no_se_pudo_actualizar':
                            echo 'No se pudo actualizar la información del bautizo.';
                            break;
                        case 'no_se_pudo_eliminar':
                            echo 'No se pudo eliminar el registro de bautizo.';
                            break;
                        default:
                            echo 'Ha ocurrido un error.';
                    }
                    ?>
                </p>
            </div>
        <?php endif; ?>

        <div class="search-section">
            <form action="/bautizos/buscar" method="GET" class="search-form">
                <div class="input-group">
                    <input type="text" name="termino" placeholder="Buscar..." class="form-control" value="<?php echo isset($_GET['termino']) ? htmlspecialchars($_GET['termino']) : ''; ?>">
                    <div class="date-filters">
                        <div class="date-input">
                            <label>Desde:</label>
                            <input type="date" name="fecha_inicio" class="form-control" value="<?php echo isset($_GET['fecha_inicio']) ? htmlspecialchars($_GET['fecha_inicio']) : ''; ?>">
                        </div>
                        <div class="date-input">
                            <label>Hasta:</label>
                            <input type="date" name="fecha_fin" class="form-control" value="<?php echo isset($_GET['fecha_fin']) ? htmlspecialchars($_GET['fecha_fin']) : ''; ?>">
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">Buscar</button>
                </div>
            </form>
        </div>

        <?php if (empty($bautizos)): ?>
            <div class="alert alert-info">
                <p>No hay bautizos registrados.</p>
            </div>
        <?php else: ?>
            <div class="table-container">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Nombre del Miembro</th>
                            <th>Fecha</th>
                            <th>Lugar</th>
                            <th>Pastor</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($bautizos as $bautizo): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($bautizo->nombreMiembro); ?></td>
                                <td>
                                    <?php 
                                        $fechaBautizo = new DateTime($bautizo->getFecha());
                                        echo $fechaBautizo->format('d/m/Y'); 
                                    ?>
                                </td>
                                <td><?php echo htmlspecialchars($bautizo->getLugar() ?: 'No especificado'); ?></td>
                                <td><?php echo htmlspecialchars($bautizo->getPastor() ?: 'No especificado'); ?></td>
                                <td class="actions-cell">
                                    <a href="/bautizos/ver?id=<?php echo $bautizo->getId(); ?>" class="btn btn-action btn-view">Ver</a>
                                    <a href="/bautizos/editar?id=<?php echo $bautizo->getId(); ?>" class="btn btn-action btn-edit">Editar</a>
                                    <a href="/bautizos/eliminar?id=<?php echo $bautizo->getId(); ?>" class="btn btn-action btn-delete" onclick="return confirm('¿Está seguro de eliminar este registro de bautizo?')">Eliminar</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </section>
</div>