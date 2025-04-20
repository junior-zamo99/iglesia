<link rel="stylesheet" href="../../css/listarMiembro.css">
<div class="container">
    <section class="content-section">
        <div class="content-header">
            <h2 class="content-title">Registrar Asistencia: <?php echo htmlspecialchars($evento->getNombre()); ?></h2>
            <div class="button-group">
                <a href="/eventos/ver?id=<?php echo $evento->getId(); ?>" class="btn btn-secondary">Volver al evento</a>
            </div>
        </div>

        <form action="/eventos/registrar-asistencia" method="POST">
            <input type="hidden" name="evento_id" value="<?php echo $evento->getId(); ?>">
            
            <div class="table-container">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Apellido</th>
                            <th>Email</th>
                            <th>Teléfono</th>
                            <th>Asistencia</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($miembros as $miembro): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($miembro->getNombre()); ?></td>
                                <td><?php echo htmlspecialchars($miembro->getApellido()); ?></td>
                                <td><?php echo htmlspecialchars($miembro->getEmail()); ?></td>
                                <td><?php echo htmlspecialchars($miembro->getTelefono()); ?></td>
                                <td>
                                    <input type="checkbox" 
                                           name="asistencia[<?php echo $miembro->getId(); ?>]" 
                                           value="1" 
                                           <?php echo isset($miembrosEventoMap[$miembro->getId()]) && $miembrosEventoMap[$miembro->getId()] ? 'checked' : ''; ?>>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Guardar Asistencias</button>
            </div>
        </form>
    </section>
</div>