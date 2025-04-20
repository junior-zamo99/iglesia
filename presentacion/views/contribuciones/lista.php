<link rel="stylesheet" href="../../css/listarMiembro.css">

<div class="container">
    <section class="content-section">
        <div class="content-header">
            <h2 class="content-title">Registro de Contribuciones</h2>
            <div class="button-group">
                <a href="/contribuciones/crear" class="btn btn-primary">Nueva Contribución</a>
            </div>
        </div>

        <?php if (isset($_GET['mensaje'])): ?>
            <div class="alert alert-success">
                <p>
                    <?php
                    switch ($_GET['mensaje']) {
                        case 'creada':
                            echo 'La contribución ha sido registrada exitosamente.';
                            break;
                        case 'actualizada':
                            echo 'La información de la contribución ha sido actualizada exitosamente.';
                            break;
                        case 'eliminada':
                            echo 'La contribución ha sido eliminada exitosamente.';
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
                            echo 'No se pudo registrar la contribución.';
                            break;
                        case 'no_se_pudo_actualizar':
                            echo 'No se pudo actualizar la información de la contribución.';
                            break;
                        case 'no_se_pudo_eliminar':
                            echo 'No se pudo eliminar la contribución.';
                            break;
                        default:
                            echo 'Ha ocurrido un error.';
                    }
                    ?>
                </p>
            </div>
        <?php endif; ?>

        <div class="filters-section">
            <form action="/contribuciones" method="GET" class="filters-form">
                <div class="filter-container">
                    <label for="filtro">Filtrar por:</label>
                    <select name="filtro" id="filtro" class="form-control" onchange="this.form.submit()">
                        <option value="todas" <?php echo (isset($_GET['filtro']) && $_GET['filtro'] == 'todas') ? 'selected' : ''; ?>>Todas las contribuciones</option>
                        <option value="mes_actual" <?php echo (isset($_GET['filtro']) && $_GET['filtro'] == 'mes_actual') ? 'selected' : ''; ?>>Mes actual</option>
                        <option value="anio_actual" <?php echo (isset($_GET['filtro']) && $_GET['filtro'] == 'anio_actual') ? 'selected' : ''; ?>>Año actual</option>
                    </select>
                </div>
            </form>

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
        </div>

        <div class="summary-section">
            <div class="summary-card">
                <h3>Total Contribuciones</h3>
                <p class="amount">$<?php echo number_format($totalContribuciones, 2); ?></p>
            </div>
            
            <div class="summary-card">
                <h3>Por Tipo</h3>
                <ul class="summary-list">
                    <?php foreach ($resumenPorTipo as $resumen): ?>
                        <li>
                            <?php echo htmlspecialchars($resumen['tipo']); ?>: 
                            <strong>$<?php echo number_format($resumen['total'], 2); ?></strong>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>

        <?php if (empty($contribuciones)): ?>
            <div class="alert alert-info">
                <p>No hay contribuciones registradas.</p>
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