<link rel="stylesheet" href="../../css/listarMiembro.css">

<div class="container">
    <section class="content-section">
        <div class="content-header">
            <h2 class="content-title">Asignar Miembro a <?php echo $ministerio->getNombre(); ?></h2>
            <a href="/ministerios/ver?id=<?php echo $ministerio->getId(); ?>" class="btn btn-secondary">Volver</a>
        </div>

        <?php if (isset($error)): ?>
            <div class="alert alert-danger">
                <p><?php echo $error; ?></p>
            </div>
        <?php endif; ?>

        <?php if (isset($_GET['error'])): ?>
            <div class="alert alert-danger">
                <p>
                    <?php
                    switch ($_GET['error']) {
                        case 'no_se_pudo_asignar':
                            echo 'No se pudo asignar el miembro al ministerio. Es posible que ya esté asignado.';
                            break;
                        default:
                            echo 'Ha ocurrido un error.';
                    }
                    ?>
                </p>
            </div>
        <?php endif; ?>

        <?php if (empty($miembros)): ?>
            <div class="alert alert-info">
                <p>No hay miembros disponibles para asignar.</p>
            </div>
        <?php else: ?>
            <form action="/ministerios/asignar-miembro" method="POST" class="form">
                <input type="hidden" name="ministerio_id" value="<?php echo $ministerio->getId(); ?>">
                
                <div class="form-group">
                    <label for="miembro_id">Seleccione un miembro:</label>
                    <select id="miembro_id" name="miembro_id" class="form-control" required>
                        <option value="">-- Seleccionar Miembro --</option>
                        <?php foreach ($miembros as $miembro): ?>
                            <option value="<?php echo $miembro->getId(); ?>">
                                <?php echo $miembro->getNombre() . ' ' . $miembro->getApellido(); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="fecha_inicio">Fecha de Inicio:</label>
                    <input type="date" id="fecha_inicio" name="fecha_inicio" class="form-control" value="<?php echo date('Y-m-d'); ?>" required>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">Asignar Miembro</button>
                </div>
            </form>
        <?php endif; ?>
    </section>
</div>