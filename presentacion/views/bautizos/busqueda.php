<link rel="stylesheet" href="../../css/listarMiembro.css">

<div class="container">
    <section class="content-section">
        <div class="content-header">
            <h2 class="content-title">Resultados de Búsqueda</h2>
            <div class="button-group">
                <a href="/bautizos" class="btn btn-secondary">Volver a la lista</a>
                <a href="/bautizos/crear" class="btn btn-primary">Nuevo Bautizo</a>
            </div>
        </div>

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

        <div class="search-info">
            <?php
            $criterios = [];
            
            if (!empty($_GET['termino'])) {
                $criterios[] = "Término: \"" . htmlspecialchars($_GET['termino']) . "\"";
            }
            
            if (!empty($_GET['fecha_inicio'])) {
                $fechaInicio = new DateTime($_GET['fecha_inicio']);
                $criterios[] = "Desde: " . $fechaInicio->format('d/m/Y');
            }
            
            if (!empty($_GET['fecha_fin'])) {
                $fechaFin = new DateTime($_GET['fecha_fin']);
                $criterios[] = "Hasta: " . $fechaFin->format('d/m/Y');
            }
            
            if (!empty($criterios)) {
                echo "<p>Búsqueda por: " . implode(", ", $criterios) . "</p>";
            }
            ?>
            <p>Se encontraron <?php echo count($bautizos); ?> resultados</p>
        </div>

        <?php if (empty($bautizos)): ?>
            <div class="alert alert-info">
                <p>No se encontraron bautizos que coincidan con los criterios de búsqueda.</p>
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