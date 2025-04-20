<link rel="stylesheet" href="../../css/listarMiembro.css">

<div class="container">
   
<section class="content-section">
        <div class="content-header">
            <h2 class="content-title">Nuevo Miembro</h2>
            <a href="/miembros" class="btn btn-secondary">Volver a la lista</a>
        </div>

        <form action="/miembros/crear" method="POST" class="form">
            <div class="form-group">
                <label for="nombre">Nombre:</label>
                <input type="text" id="nombre" name="nombre" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="apellido">Apellido:</label>
                <input type="text" id="apellido" name="apellido" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="estado_civil">Estado Civil:</label>
                <select id="estado_civil" name="estado_civil" class="form-control">
                    <option value="Soltero">Soltero</option>
                    <option value="Casado">Casado</option>
                    <option value="Viudo">Viudo</option>
                    <option value="Divorciado">Divorciado</option>
                </select>
            </div>

            <div class="form-group">
                <label for="telefono">Teléfono:</label>
                <input type="tel" id="telefono" name="telefono" class="form-control">
            </div>

            <div class="form-group">
                <label for="direccion">Dirección:</label>
                <textarea id="direccion" name="direccion" class="form-control"></textarea>
            </div>

            <div class="form-group">
                <label for="fechaNacimiento">Fecha de Nacimiento:</label>
                <input type="date" id="fecha_nacimiento" name="fecha_nacimiento" class="form-control">
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Guardar</button>
            </div>
        </form>
    </section>
</div>