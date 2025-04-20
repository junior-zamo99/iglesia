<link rel="stylesheet" href="../../css/listarMiembro.css">

<div class="container">
    <section class="content-section">
        <div class="content-header">
            <h2 class="content-title">Editar Evento</h2>
            <div class="button-group">
                <a href="/eventos" class="btn btn-secondary">Volver a la lista</a>
                <a href="/eventos/ver?id=<?php echo $evento->getId(); ?>" class="btn btn-info">Ver detalles</a>
            </div>
        </div>

        <?php if (isset($error)): ?>
            <div class="alert alert-danger">
                <p><?php echo $error; ?></p>
            </div>
        <?php endif; ?>

        <form action="/eventos/actualizar" method="POST" class="form">
            <input type="hidden" name="id" value="<?php echo $evento->getId(); ?>">
            
            <div class="form-group">
                <label for="nombre">Nombre del Evento: <span class="required">*</span></label>
                <input type="text" id="nombre" name="nombre" class="form-control" value="<?php echo htmlspecialchars($evento->getNombre()); ?>" required>
                <?php if (isset($errores['nombre'])): ?>
                    <div class="error-message"><?php echo $errores['nombre']; ?></div>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label for="fecha">Fecha: <span class="required">*</span></label>
                <input type="date" id="fecha" name="fecha" class="form-control" value="<?php echo htmlspecialchars($evento->getFecha()); ?>" required>
                <?php if (isset($errores['fecha'])): ?>
                    <div class="error-message"><?php echo $errores['fecha']; ?></div>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label for="ubicacion">Ubicación:</label>
                <input type="text" id="ubicacion" name="ubicacion" class="form-control" value="<?php echo htmlspecialchars($evento->getUbicacion()); ?>">
            </div>

            <div class="form-group">
                <label for="tipo">Tipo de Evento:</label>
                <select id="tipo" name="tipo" class="form-control">
                    <option value="">Seleccionar tipo</option>
                    <?php if (!empty($tiposEvento)): ?>
                        <?php foreach ($tiposEvento as $tipo): ?>
                            <option value="<?php echo htmlspecialchars($tipo); ?>" <?php echo ($evento->getTipo() === $tipo) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($tipo); ?>
                            </option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                    <option value="otro" <?php echo (!in_array($evento->getTipo(), $tiposEvento) && $evento->getTipo() != '') ? 'selected' : ''; ?>>Otro (nuevo tipo)</option>
                </select>
            </div>
            
            <div class="form-group" id="nuevoTipoGroup" style="display: <?php echo (!in_array($evento->getTipo(), $tiposEvento) && $evento->getTipo() != '') ? 'flex' : 'none'; ?>;">
                <label for="nuevoTipo">Especificar Nuevo Tipo:</label>
                <input type="text" id="nuevoTipo" name="nuevo_tipo" class="form-control" value="<?php echo (!in_array($evento->getTipo(), $tiposEvento) && $evento->getTipo() != '') ? htmlspecialchars($evento->getTipo()) : ''; ?>">
            </div>

            <div class="form-group">
                <label for="descripcion">Descripción:</label>
                <textarea id="descripcion" name="descripcion" class="form-control" rows="4"><?php echo htmlspecialchars($evento->getDescripcion()); ?></textarea>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Guardar Cambios</button>
            </div>
        </form>
    </section>
</div>

<script>
    document.getElementById('tipo').addEventListener('change', function() {
        const nuevoTipoGroup = document.getElementById('nuevoTipoGroup');
        const nuevoTipoInput = document.getElementById('nuevoTipo');
        
        if (this.value === 'otro') {
            nuevoTipoGroup.style.display = 'flex';
            nuevoTipoInput.required = true;
        } else {
            nuevoTipoGroup.style.display = 'none';
            nuevoTipoInput.required = false;
        }
    });
</script>