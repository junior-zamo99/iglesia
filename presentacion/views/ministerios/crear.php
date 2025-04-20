<link rel="stylesheet" href="../../css/listarMiembro.css">

<div class="container">
    <section class="content-section">
        <div class="content-header">
            <h2 class="content-title">Nuevo Ministerio</h2>
            <a href="/ministerios" class="btn btn-secondary">Volver a la lista</a>
        </div>

        <?php if (isset($error)): ?>
            <div class="alert alert-danger">
                <p><?php echo $error; ?></p>
            </div>
        <?php endif; ?>

        <form action="/ministerios/crear" method="POST" class="form">
            <div class="form-group">
                <label for="nombre">Nombre:</label>
                <input type="text" id="nombre" name="nombre" class="form-control" value="<?php echo $_POST['nombre'] ?? ''; ?>" required>
                <?php if (isset($errores['nombre'])): ?>
                    <div class="error-message"><?php echo $errores['nombre']; ?></div>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label for="descripcion">Descripción:</label>
                <textarea id="descripcion" name="descripcion" class="form-control" rows="5"><?php echo $_POST['descripcion'] ?? ''; ?></textarea>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Guardar</button>
            </div>
        </form>
    </section>
</div>