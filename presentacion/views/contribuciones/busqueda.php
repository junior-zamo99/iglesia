<link rel="stylesheet" href="../../css/listarMiembro.css">

<div class="container">
    <section class="content-section">
        <div class="content-header">
            <h2 class="content-title">Resultados de Búsqueda</h2>
            <div class="button-group">
                <a href="/contribuciones" class="btn btn-secondary">Volver a la lista</a>
                <a href="/contribuciones/crear" class="btn btn-primary">Nueva Contribución</a>
            </div>
        </div>

        <div class="search-section">
            <form action="/contribuciones/buscar" method="GET" class="search-form">
                <div class="input-group">
                    <input type="text" name="termino" placeholder="Buscar..." class="form-control" value="<?php echo isset($_GET['termino']) ? htmlspecialchars($_GET['termino']) : ''; ?>">
                    
                    <select name="tipo" class="form-control">
                        <option value="">Todos los tipos</option>
                        <?php foreach ($tiposContribucion as $tipoOption): ?>
                            <option value="<?php echo htmlspecialchars($tipoOption); ?>" <?php echo (isset($_GET['tipo']) && $_GET['tipo'] == $tipoOption) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($tipoOption); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    
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
            
            if (!empty($_GET['tipo'])) {
                $criterios[] = "Tipo: \"" . htmlspecialchars($_GET['tipo']) . "\"";
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
            <p>Se encontraron <?php echo count($contribuciones); ?> resultados</p>
            <?php if ($totalContribuciones > 0): ?>
                <p><strong>Total: $<?php echo number_format($totalContribuciones, 2); ?></strong></p>
            <?php endif; ?>
        </div>

        <?php if (empty($contribuciones)): ?>
            <div class="alert alert-info">
                <p>No se encontraron contribuciones que coincidan con los criterios de búsqueda.</p>
            </div>
        <?php else: ?>
            <div class="table-container">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Fecha</th>
                            <th>Miembro</th>
                            <th>Tipo</th>
                            <th>Monto</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($contribuciones as $contribucion): ?>
                            <tr>
                                <td>
                                    <?php 
                                        $fechaContribucion = new DateTime($contribucion->getFecha());
                                        echo $fechaContribucion->format('d/m/Y'); 
                                    ?>
                                </td>
                                <td><?php echo htmlspecialchars($contribucion->nombreMiembro); ?></td>
                                <td><?php echo htmlspecialchars($contribucion->getTipo()); ?></td>
                                <td class="amount-cell">$<?php echo number_format($contribucion->getMonto(), 2); ?></td>
                                <td class="actions-cell">
                                    <a href="/contribuciones/ver?id=<?php echo $contribucion->getId(); ?>" class="btn btn-action btn-view">Ver</a>
                                    <a href="/contribuciones/editar?id=<?php echo $contribucion->getId(); ?>" class="btn btn-action btn-edit">Editar</a>
                                    <a href="/contribuciones/eliminar?id=<?php echo $contribucion->getId(); ?>" class="btn btn-action btn-delete" onclick="return confirm('¿Está seguro de eliminar esta contribución?')">Eliminar</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </section>
</div>