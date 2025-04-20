<link rel="stylesheet" href="../../css/listarMiembro.css">

<div class="container">
    <section class="content-section">
        <div class="content-header">
            <h2 class="content-title">Lista de Eventos</h2>
            <div class="button-group">
                <a href="/eventos/crear" class="btn btn-primary">Nuevo Evento</a>
                <a href="/eventos?filtro=proximos" class="btn btn-secondary">Próximos</a>
                <a href="/eventos?filtro=pasados" class="btn btn-secondary">Pasados</a>
                <a href="/eventos" class="btn btn-secondary">Todos</a>
            </div>
        </div>

        <div class="search-container">
            <form action="/eventos/buscar" method="GET" class="search-form">
                <div class="form-group search-input">
                    <input type="text" name="termino" placeholder="Buscar eventos..." class="form-control">
                </div>
                <div class="form-group search-select">
                    <select name="tipo" class="form-control">
                        <option value="">Todos los tipos</option>
                        <?php foreach ($tiposEvento as $tipo): ?>
                            <option value="<?php echo htmlspecialchars($tipo); ?>"><?php echo htmlspecialchars($tipo); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group search-date">
                    <input type="date" name="fecha_inicio" placeholder="Fecha inicio" class="form-control">
                </div>
                <div class="form-group search-date">
                    <input type="date" name="fecha_fin" placeholder="Fecha fin" class="form-control">
                </div>
                <button type="submit" class="btn btn-primary">Buscar</button>
            </form>
        </div>

        <?php if (isset($_GET['mensaje']) && $_GET['mensaje'] === 'creado'): ?>
            <div class="alert alert-success">
                <p>El evento ha sido creado exitosamente.</p>
            </div>
        <?php endif; ?>

        <?php if (isset($_GET['mensaje']) && $_GET['mensaje'] === 'actualizado'): ?>
            <div class="alert alert-success">
                <p>El evento ha sido actualizado exitosamente.</p>
            </div>
        <?php endif; ?>

        <?php if (isset($_GET['mensaje']) && $_GET['mensaje'] === 'eliminado'): ?>
            <div class="alert alert-success">
                <p>El evento ha sido eliminado exitosamente.</p>
            </div>
        <?php endif; ?>

        <?php if (isset($_GET['error'])): ?>
            <div class="alert alert-danger">
                <p>
                    <?php
                    switch ($_GET['error']) {
                        case 'no_se_pudo_eliminar':
                            echo 'No se pudo eliminar el evento.';
                            break;
                        case 'id_no_proporcionado':
                            echo 'ID no proporcionado.';
                            break;
                        case 'evento_no_encontrado':
                            echo 'Evento no encontrado.';
                            break;
                        default:
                            echo 'Ha ocurrido un error.';
                    }
                    ?>
                </p>
            </div>
        <?php endif; ?>

        <?php if (empty($eventos)): ?>
            <div class="alert alert-info">
                <p>No hay eventos registrados.</p>
            </div>
        <?php else: ?>
            <div class="table-container">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Fecha</th>
                            <th>Ubicación</th>
                            <th>Tipo</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($eventos as $evento): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($evento->getNombre()); ?></td>
                                <td>
                                    <?php 
                                        $fechaEvento = new DateTime($evento->getFecha());
                                        echo $fechaEvento->format('d/m/Y'); 
                                    ?>
                                </td>
                                <td><?php echo htmlspecialchars($evento->getUbicacion()); ?></td>
                                <td><?php echo htmlspecialchars($evento->getTipo()); ?></td>
                                <td class="actions-cell">
                                    <a href="/eventos/ver?id=<?php echo $evento->getId(); ?>" class="btn btn-action btn-view" title="Ver detalles">
                                        <i class="fas fa-eye"></i> Ver
                                    </a>
                                    <a href="/eventos/editar?id=<?php echo $evento->getId(); ?>" class="btn btn-action btn-edit" title="Editar">
                                        <i class="fas fa-edit"></i> Editar
                                    </a>
                                    <a href="/eventos/eliminar?id=<?php echo $evento->getId(); ?>" class="btn btn-action btn-delete" onclick="return confirm('¿Está seguro de eliminar este evento?')" title="Eliminar">
                                        <i class="fas fa-trash"></i> Eliminar
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </section>
</div>