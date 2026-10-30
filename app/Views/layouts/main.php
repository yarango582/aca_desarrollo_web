<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($titulo ?? 'Hotel Luna Azul') ?> | Hotel Luna Azul</title>
    <link rel="stylesheet" href="/assets/css/styles.css">
</head>
<body>
    <header class="navbar">
        <a href="/" class="brand">Hotel Luna Azul</a>
        <nav>
            <a href="/huespedes">Huéspedes</a>
            <a href="/reservas">Reservas</a>
            <a href="/habitaciones">Habitaciones</a>
            <a href="/logout">Salir</a>
        </nav>
    </header>

    <main class="container">
        <?= $content ?>
    </main>

    <footer class="footer">Proyecto final Desarrollo Web &middot; <?= date('Y') ?></footer>
    <script src="/assets/js/app.js"></script>
</body>
</html>
