<link rel="stylesheet" href="../../css/listarMiembro.css">

<div class="container">
    <section class="content-section">
        <div class="content-header">
            <h2 class="content-title">Resultados de Búsqueda</h2>
            <a href="/eventos" class="btn btn-secondary">Volver a la lista</a>
        </div>

        <div class="search-container">
            <form action="/eventos/buscar" method="GET" class="search-form">
                <div class="form-group search-input">
                    <input type="text" name="termino" placeholder="Buscar eventos..." class="form-control" value="<?php echo htmlspecialchars($_GET['termino'] ?? ''); ?>">
                </div>
                <div class="form-group search-select">
                    <select name="tipo" class="form-control">
                        <option value="">Todos los tipos</option>
                        <?php foreach ($tiposEvento as $tipo): ?>
                            <option value="<?php echo htmlspecialchars($tipo); ?>" <?php echo (isset($_GET['tipo']) && $_GET['tipo'] === $tipo) ? 'selected' : ''; ?>><?php echo htmlspecialchars($tipo); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group search-date">
                    <input type="date" name="fecha_inicio" placeholder="Fecha inicio" class="form-control" value="<?php echo htmlspecialchars($_GET['fecha_inicio'] ?? ''); ?>">
                </div>
                <div class="form-group search-date">
                    <input type="date" name="fecha_fin" placeholder="Fecha fin" class="form-control" value="<?php echo htmlspecialchars($_GET['fecha_fin'] ?? ''); ?>">
                </div>
                <button type="submit" class="btn btn-primary">Buscar</button>
            </form>
        </div>

        <div class="search-results">
            <h3>Se encontraron <?php echo count($eventos); ?> eventos</h3>
            
            <?php if (empty($eventos)): ?>
                <div class="alert alert-info">
                    <p>No se encontraron eventos con los criterios de búsqueda especificados.</p>
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
                            <?php foreach ($eventos as $eventoItem): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($eventoItem->getNombre()); ?></td>
                                    <td>
                                        <?php 
                                            $fechaEvento = new DateTime($eventoItem->getFecha());
                                            echo $fechaEvento->format('d/m/Y'); 
                                        ?>
                                    </td>
                                    <td><?php echo htmlspecialchars($eventoItem->getUbicacion()); ?></td>
                                    <td><?php echo htmlspecialchars($eventoItem->getTipo()); ?></td>
                                    <td class="actions-cell">
                                        <a href="/eventos/ver?id=<?php echo $eventoItem->getId(); ?>" class="btn btn-action btn-view" title="Ver detalles">
                                            <i class="fas fa-eye"></i> Ver
                                        </a>
                                        <a href="/eventos/editar?id=<?php echo $eventoItem->getId(); ?>" class="btn btn-action btn-edit" title="Editar">
                                            <i class="fas fa-edit"></i> Editar
                                        </a>
                                        <a href="/eventos/eliminar?id=<?php echo $eventoItem->getId(); ?>" class="btn btn-action btn-delete" onclick="return confirm('¿Está seguro de eliminar este evento?')" title="Eliminar">
                                            <i class="fas fa-trash"></i> Eliminar
                                        </a>
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