<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iglesia - <?php echo $titulo ?? 'Gestión'; ?></title>
  
</head>
<body>
    <header class="header">
        <div class="container">
            <div class="logo">
                <h1>Sistema de Gestión - Iglesia</h1>
            </div>
            <nav class="navbar">
                <ul class="nav-menu">
                    <li><a href="/miembros">Miembros</a></li>
                    <li><a href="/ministerios">Ministerios</a></li>
                    <li><a href="/eventos">Eventos</a></li>
                    <li><a href="/bautizos">Bautizos</a></li>
                    <li><a href="/contribuciones">Contribuciones</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <main class="main-content">
        <div class="container">
            <?php if (isset($mensajeExito)): ?>
                <div class="alert alert-success">
                    <?php echo $mensajeExito; ?>
                </div>
            <?php endif; ?>
            
            <?php if (isset($mensajeError)): ?>
                <div class="alert alert-danger">
                    <?php echo $mensajeError; ?>
                </div>
            <?php endif; ?>
            
            <!-- Contenido de la página -->
            <?php include $vista; ?>
        </div>
    </main>

    <footer class="footer">
        <div class="container">
            <p>&copy; <?php echo date('Y'); ?> - Sistema de Gestión de Iglesia</p>
        </div>
    </footer>
</body>
</html>