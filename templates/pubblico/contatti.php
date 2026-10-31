<h1>Contattaci</h1>
<form method="post" action="/contatti" class="form">
    <?= $csrf->field() ?>
    <label class="hp" aria-hidden="true">Non compilare <input type="text" name="sito_web" tabindex="-1" autocomplete="off"></label>
    <label>Tipo di richiesta *
        <select name="tipo"><?= options($stati['richiesta_tipo'], $data['tipo'] ?? 'preventivo') ?></select>
        <?= field_error($errors, 'tipo') ?>
    </label>
    <div class="grid">
        <label>Nome e cognome * <input name="nome" value="<?= v($data, 'nome') ?>" required><?= field_error($errors, 'nome') ?></label>
        <label>Azienda <input name="azienda_richiedente" value="<?= v($data, 'azienda_richiedente') ?>"></label>
    </div>
    <div class="grid">
        <label>Email * <input type="email" name="email" value="<?= v($data, 'email') ?>" required><?= field_error($errors, 'email') ?></label>
        <label>Telefono <input name="telefono" value="<?= v($data, 'telefono') ?>"></label>
    </div>
    <label>Indirizzo o località dell'intervento <input name="localita" value="<?= v($data, 'localita') ?>"></label>
    <label>Descrizione del problema * <textarea name="descrizione" rows="5" required><?= v($data, 'descrizione') ?></textarea><?= field_error($errors, 'descrizione') ?></label>
    <label>Preferisci essere ricontattato via
        <select name="preferenza_contatto"><?= options(['email' => 'Email', 'telefono' => 'Telefono'], $data['preferenza_contatto'] ?? null, true) ?></select>
    </label>
    <label class="check">
        <input type="checkbox" name="privacy" value="1"> Ho letto l'informativa privacy e acconsento al trattamento dei dati per rispondere alla richiesta. *
    </label>
    <?= field_error($errors, 'privacy') ?>
    <div class="actions"><button class="btn" type="submit">Invia richiesta</button></div>
</form>
