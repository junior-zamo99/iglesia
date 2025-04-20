<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Miembros</title>
    <link rel="stylesheet" href="../../css/listarMiembro.css">
    <!-- Agrega el CSS aquí si tienes problemas con el archivo externo -->
    <style>
        /* Pegar el CSS que te proporcioné arriba */
    </style>
</head>
<body>
  

    <div class="container">
        <section class="content-section">
            <div class="content-header">
                <h2 class="content-title">Lista de Miembros</h2>
                <a href="/miembros/crear" class="btn btn-primary">Nuevo Miembro</a>
            </div>

            <?php if (empty($miembros)): ?>
                <div class="alert alert-info">
                    <p>No hay miembros registrados.</p>
                </div>
            <?php else: ?>
                <div class="table-container">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nombre</th>
                                <th>Apellido</th>
                                <th>Email</th>
                                <th>Estado Civil</th>
                                <th>Teléfono</th>
                                <th>Dirección</th>
                                <th>Fecha de Nacimiento</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($miembros as $miembro): ?>
                                <tr>
                                    <td><?php echo $miembro->getId(); ?></td>
                                    <td><?php echo $miembro->getNombre(); ?></td>
                                    <td><?php echo $miembro->getApellido(); ?></td>
                                    <td><?php echo $miembro->getEmail(); ?></td>
                                    <td><?php echo $miembro->getEstadoCivil(); ?></td>
                                    <td><?php echo $miembro->getTelefono(); ?></td>
                                    <td><?php echo $miembro->getDireccion(); ?></td>
                                    <td><?php echo $miembro->getFechaNacimiento(); ?></td>
                                    <td class="actions-cell">
                                        <a href="/miembros/editar?id=<?php echo $miembro->getId(); ?>" class="btn btn-action btn-edit">Editar</a>
                                        <a href="/miembros/eliminar?id=<?php echo $miembro->getId(); ?>" class="btn btn-action btn-delete" onclick="return confirm('¿Está seguro de eliminar este miembro?')">Eliminar</a>                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </section>
    </div>

   
</body>
</html>