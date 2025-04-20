<link rel="stylesheet" href="../../css/listarMiembro.css">

<div class="container">
    <section class="content-section">
        <div class="content-header">
            <h2 class="content-title">Nuevo Evento</h2>
            <a href="/eventos" class="btn btn-secondary">Volver a la lista</a>
        </div>

        <?php if (isset($error)): ?>
            <div class="alert alert-danger">
                <p><?php echo $error; ?></p>
            </div>
        <?php endif; ?>

        <form action="/eventos/crear" method="POST" class="form">
            <div class="form-group">
                <label for="nombre">Nombre del Evento: <span class="required">*</span></label>
                <input type="text" id="nombre" name="nombre" class="form-control" value="<?php echo isset($_POST['nombre']) ? htmlspecialchars($_POST['nombre']) : ''; ?>" required>
                <?php if (isset($errores['nombre'])): ?>
                    <div class="error-message"><?php echo $errores['nombre']; ?></div>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label for="fecha">Fecha: <span class="required">*</span></label>
                <input type="date" id="fecha" name="fecha" class="form-control" value="<?php echo isset($_POST['fecha']) ? htmlspecialchars($_POST['fecha']) : date('Y-m-d'); ?>" required>
                <?php if (isset($errores['fecha'])): ?>
                    <div class="error-message"><?php echo $errores['fecha']; ?></div>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label for="ubicacion">Ubicación:</label>
                <input type="text" id="ubicacion" name="ubicacion" class="form-control" value="<?php echo isset($_POST['ubicacion']) ? htmlspecialchars($_POST['ubicacion']) : ''; ?>">
            </div>

            <div class="form-group">
                <label for="tipo">Tipo de Evento:</label>
                <select id="tipo" name="tipo" class="form-control">
                    <option value="">Seleccionar tipo</option>
                    <?php if (!empty($tiposEvento)): ?>
                        <?php foreach ($tiposEvento as $tipo): ?>
                            <option value="<?php echo htmlspecialchars($tipo); ?>" <?php echo (isset($_POST['tipo']) && $_POST['tipo'] === $tipo) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($tipo); ?>
                            </option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                    <option value="otro">Otro (nuevo tipo)</option>
                </select>
            </div>
            
            <div class="form-group" id="nuevoTipoGroup" style="display: none;">
                <label for="nuevoTipo">Especificar Nuevo Tipo:</label>
                <input type="text" id="nuevoTipo" name="nuevo_tipo" class="form-control" value="<?php echo isset($_POST['nuevo_tipo']) ? htmlspecialchars($_POST['nuevo_tipo']) : ''; ?>">
            </div>

            <div class="form-group">
                <label for="descripcion">Descripción:</label>
                <textarea id="descripcion" name="descripcion" class="form-control" rows="4"><?php echo isset($_POST['descripcion']) ? htmlspecialchars($_POST['descripcion']) : ''; ?></textarea>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Guardar Evento</button>
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