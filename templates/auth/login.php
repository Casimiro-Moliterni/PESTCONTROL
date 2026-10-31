<h1>Accesso</h1>
<form method="post" action="/app/login" class="form">
    <?= $csrf->field() ?>
    <label>Email
        <input type="email" name="email" value="<?= e($email) ?>" required autofocus>
    </label>
    <label>Password
        <input type="password" name="password" required>
    </label>
    <button type="submit" class="btn">Accedi</button>
</form>
