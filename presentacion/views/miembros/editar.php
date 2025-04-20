<link rel="stylesheet" href="../../css/listarMiembro.css">
<div class="container">
    <section class="content-section">
        <div class="content-header">
            <h2 class="content-title">Editar Miembro</h2>
            <a href="/miembros" class="btn btn-secondary">Volver a la lista</a>
        </div>

        <?php if (isset($error)): ?>
            <div class="alert alert-danger">
                <p><?php echo $error; ?></p>
            </div>
        <?php endif; ?>

        <form action="/miembros/actualizar" method="POST" class="form">
            <input type="hidden" name="id" value="<?php echo $miembro->getId(); ?>">
            
            <div class="form-group">
                <label for="nombre">Nombre:</label>
                <input type="text" id="nombre" name="nombre" class="form-control" value="<?php echo $miembro->getNombre(); ?>" required>
                <?php if (isset($errores['nombre'])): ?>
                    <div class="error-message"><?php echo $errores['nombre']; ?></div>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label for="apellido">Apellido:</label>
                <input type="text" id="apellido" name="apellido" class="form-control" value="<?php echo $miembro->getApellido(); ?>" required>
            </div>

            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" class="form-control" value="<?php echo $miembro->getEmail(); ?>" required>
                <?php if (isset($errores['email'])): ?>
                    <div class="error-message"><?php echo $errores['email']; ?></div>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label for="estado_civil">Estado Civil:</label>
                <select id="estado_civil" name="estado_civil" class="form-control">
                    <option value="Soltero" <?php echo $miembro->getEstadoCivil() == 'Soltero' ? 'selected' : ''; ?>>Soltero</option>
                    <option value="Casado" <?php echo $miembro->getEstadoCivil() == 'Casado' ? 'selected' : ''; ?>>Casado</option>
                    <option value="Viudo" <?php echo $miembro->getEstadoCivil() == 'Viudo' ? 'selected' : ''; ?>>Viudo</option>
                    <option value="Divorciado" <?php echo $miembro->getEstadoCivil() == 'Divorciado' ? 'selected' : ''; ?>>Divorciado</option>
                </select>
            </div>

            <div class="form-group">
                <label for="telefono">Teléfono:</label>
                <input type="tel" id="telefono" name="telefono" class="form-control" value="<?php echo $miembro->getTelefono(); ?>">
            </div>

            <div class="form-group">
                <label for="direccion">Dirección:</label>
                <textarea id="direccion" name="direccion" class="form-control"><?php echo $miembro->getDireccion(); ?></textarea>
            </div>

            <div class="form-group">
                <label for="fecha_nacimiento">Fecha de Nacimiento:</label>
                <input type="date" id="fecha_nacimiento" name="fecha_nacimiento" class="form-control" value="<?php echo $miembro->getFechaNacimiento(); ?>">
                <?php if (isset($errores['fecha_nacimiento'])): ?>
                    <div class="error-message"><?php echo $errores['fecha_nacimiento']; ?></div>
                <?php endif; ?>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Guardar Cambios</button>
            </div>
        </form>
    </section>
</div>