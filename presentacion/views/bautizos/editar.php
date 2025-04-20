<link rel="stylesheet" href="../../css/listarMiembro.css">


<div class="container">
    <section class="content-section">
        <div class="content-header">
            <h2 class="content-title">Editar Bautizo</h2>
            <div class="button-group">
                <a href="/bautizos" class="btn btn-secondary">Volver a la lista</a>
            </div>
        </div>

        <?php if (isset($error)): ?>
            <div class="alert alert-danger">
                <p><?php echo $error; ?></p>
            </div>
        <?php endif; ?>

        <form action="/bautizos/actualizar" method="POST" class="form-container">
            <input type="hidden" name="id" value="<?php echo $bautizo->getId(); ?>">
            
            <div class="form-group">
                <label for="miembro_id">Miembro*:</label>
                <select name="miembro_id" id="miembro_id" class="form-control <?php echo isset($errores['miembro_id']) ? 'is-invalid' : ''; ?>" required>
                    <option value="">Seleccione un miembro</option>
                    <?php foreach ($miembros as $miembro): ?>
                        <option value="<?php echo $miembro->getId(); ?>" <?php echo ($bautizo->getMiembroId() == $miembro->getId()) ? 'selected' : ''; ?>>
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
                <label for="fecha">Fecha de Bautizo*:</label>
                <input type="date" name="fecha" id="fecha" class="form-control <?php echo isset($errores['fecha']) ? 'is-invalid' : ''; ?>" value="<?php echo htmlspecialchars($bautizo->getFecha()); ?>" required>
                <?php if (isset($errores['fecha'])): ?>
                    <div class="invalid-feedback">
                        <?php echo $errores['fecha']; ?>
                    </div>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label for="lugar">Lugar:</label>
                <input type="text" name="lugar" id="lugar" class="form-control" value="<?php echo htmlspecialchars($bautizo->getLugar()); ?>" placeholder="Ej: Río Jordan, Templo Central, etc.">
            </div>

            <div class="form-group">
                <label for="pastor">Pastor Oficiante:</label>
                <input type="text" name="pastor" id="pastor" class="form-control" value="<?php echo htmlspecialchars($bautizo->getPastor()); ?>" placeholder="Nombre del pastor que realizó el bautizo">
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Actualizar</button>
                <a href="/bautizos" class="btn btn-secondary">Cancelar</a>
            </div>
        </form>
    </section>
</div>