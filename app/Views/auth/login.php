<?php
use App\Helpers\Csrf;
?>
<div class="login-card">
    <h1>Hotel Luna Azul</h1>
    <p>Inicia sesión para continuar</p>

    <?php if (isset($error)): ?>
        <div class="alert-error" role="alert"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="post" action="/login">
        <?= Csrf::campo() ?>
        <div class="form-group">
            <label for="email">Correo electrónico</label>
            <input type="email" id="email" name="email" value="<?= htmlspecialchars($email ?? '') ?>" required autofocus>
        </div>
        <div class="form-group">
            <label for="password">Contraseña</label>
            <input type="password" id="password" name="password" required>
        </div>
        <button type="submit" class="btn">Ingresar</button>
    </form>
</div>
