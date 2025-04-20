<link rel="stylesheet" href="../../css/listarMiembro.css">

<div class="container">
    <section class="content-section">
        <div class="content-header">
            <h2 class="content-title">Registrar Nueva Contribución</h2>
            <div class="button-group">
                <a href="/contribuciones" class="btn btn-secondary">Volver a la lista</a>
            </div>
        </div>

        <?php if (isset($error)): ?>
            <div class="alert alert-danger">
                <p><?php echo $error; ?></p>
            </div>
        <?php endif; ?>

        <form action="/contribuciones/crear" method="POST" class="form-container">
            <div class="form-group">
                <label for="miembro_id">Miembro*:</label>
                <select name="miembro_id" id="miembro_id" class="form-control <?php echo isset($errores['miembro_id']) ? 'is-invalid' : ''; ?>" required>
                    <option value="">Seleccione un miembro</option>
                    <?php foreach ($miembros as $miembro): ?>
                        <option value="<?php echo $miembro->getId(); ?>" <?php echo (isset($_POST['miembro_id']) && $_POST['miembro_id'] == $miembro->getId()) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($miembro->getNombre() . ' ' . $miembro->getApellido()); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <?php if (isset($errores['miembro_id'])): ?>
                    <div class="invalid-feedback">
                        <?php echo $errores['miembro_id']; ?>
                    </div>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label for="fecha">Fecha*:</label>
                <input type="date" name="fecha" id="fecha" class="form-control <?php echo isset($errores['fecha']) ? 'is-invalid' : ''; ?>" 
                    value="<?php echo isset($_POST['fecha']) ? htmlspecialchars($_POST['fecha']) : date('Y-m-d'); ?>" required>
                <?php if (isset($errores['fecha'])): ?>
                    <div class="invalid-feedback">
                        <?php echo $errores['fecha']; ?>
                    </div>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label for="monto">Monto ($)*:</label>
                <input type="number" step="0.01" min="0.01" name="monto" id="monto" class="form-control <?php echo isset($errores['monto']) ? 'is-invalid' : ''; ?>" 
                    value="<?php echo isset($_POST['monto']) ? htmlspecialchars($_POST['monto']) : ''; ?>" required>
                <?php if (isset($errores['monto'])): ?>
                    <div class="invalid-feedback">
                        <?php echo $errores['monto']; ?>
                    </div>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label for="tipo">Tipo*:</label>
                <div class="tipo-container">
                    <select name="tipo" id="tipo" class="form-control <?php echo isset($errores['tipo']) ? 'is-invalid' : ''; ?>" required>
                        <option value="">Seleccione tipo</option>
                        <?php if (!empty($tiposContribucion)): ?>
                            <?php foreach ($tiposContribucion as $tipoOption): ?>
                                <option value="<?php echo htmlspecialchars($tipoOption); ?>" <?php echo (isset($_POST['tipo']) && $_POST['tipo'] == $tipoOption) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($tipoOption); ?>
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                        <option value="Diezmo" <?php echo (isset($_POST['tipo']) && $_POST['tipo'] == 'Diezmo') || empty($tiposContribucion) ? 'selected' : ''; ?>>Diezmo</option>
                        <option value="Ofrenda" <?php echo (isset($_POST['tipo']) && $_POST['tipo'] == 'Ofrenda') ? 'selected' : ''; ?>>Ofrenda</option>
                        <option value="Ofrenda especial" <?php echo (isset($_POST['tipo']) && $_POST['tipo'] == 'Ofrenda especial') ? 'selected' : ''; ?>>Ofrenda especial</option>
                        <option value="Misiones" <?php echo (isset($_POST['tipo']) && $_POST['tipo'] == 'Misiones') ? 'selected' : ''; ?>>Misiones</option>
                        <option value="Construcción" <?php echo (isset($_POST['tipo']) && $_POST['tipo'] == 'Construcción') ? 'selected' : ''; ?>>Construcción</option>
                    </select>
                    <?php if (isset($errores['tipo'])): ?>
                        <div class="invalid-feedback">
                            <?php echo $errores['tipo']; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="form-group">
                <label for="descripcion">Descripción:</label>
                <textarea name="descripcion" id="descripcion" class="form-control" rows="3"><?php echo isset($_POST['descripcion']) ? htmlspecialchars($_POST['descripcion']) : ''; ?></textarea>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Guardar</button>
                <a href="/contribuciones" class="btn btn-secondary">Cancelar</a>
            </div>
        </form>
    </section>
</div>