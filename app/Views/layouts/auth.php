<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($titulo ?? 'Hotel Luna Azul') ?> | Hotel Luna Azul</title>
    <link rel="stylesheet" href="/assets/css/styles.css">
</head>
<body class="auth-body">
    <main class="auth-container">
        <?= $content ?>
    </main>
</body>
</html>
