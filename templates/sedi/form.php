<h1><?= $id ? 'Modifica sede' : 'Nuova sede' ?> — <?= e($cliente['denominazione']) ?></h1>
<form method="post" action="<?= $id ? '/app/sedi/' . (int) $id : '/app/clienti/' . (int) $cliente['id'] . '/sedi' ?>" class="form">
    <?= $csrf->field() ?>
    <label>Nome sede *
        <input name="nome" value="<?= v($sede, 'nome') ?>" required>
        <?= field_error($errors, 'nome') ?>
    </label>
    <label>Indirizzo <input name="indirizzo" value="<?= v($sede, 'indirizzo') ?>"><?= field_error($errors, 'indirizzo') ?></label>
    <div class="grid">
        <label>Città <input name="citta" value="<?= v($sede, 'citta') ?>"></label>
        <label>CAP <input name="cap" value="<?= v($sede, 'cap') ?>"></label>
        <label>Provincia <input name="provincia" maxlength="5" value="<?= v($sede, 'provincia') ?>"></label>
    </div>
    <div class="grid">
        <label>Latitudine <input name="latitudine" value="<?= v($sede, 'latitudine') ?>"><?= field_error($errors, 'latitudine') ?></label>
        <label>Longitudine <input name="longitudine" value="<?= v($sede, 'longitudine') ?>"><?= field_error($errors, 'longitudine') ?></label>
    </div>
    <div class="grid">
        <label>Referente in sede <input name="referente_nome" value="<?= v($sede, 'referente_nome') ?>"></label>
        <label>Telefono referente <input name="referente_telefono" value="<?= v($sede, 'referente_telefono') ?>"></label>
    </div>
    <label>Orari consentiti <input name="orari_consentiti" value="<?= v($sede, 'orari_consentiti') ?>"></label>
    <label>Istruzioni di accesso <textarea name="istruzioni_accesso" rows="3"><?= v($sede, 'istruzioni_accesso') ?></textarea></label>
    <label>Note per gli operatori <textarea name="note" rows="3"><?= v($sede, 'note') ?></textarea></label>
    <div class="actions">
        <button class="btn" type="submit">Salva</button>
        <a class="btn btn-secondary" href="/app/clienti/<?= (int) $cliente['id'] ?>">Annulla</a>
    </div>
</form>
<?php if ($id && $auth->can('clienti', 'eliminazione')): ?>
<form method="post" action="/app/sedi/<?= (int) $id ?>/archivia" class="form">
    <?= $csrf->field() ?><button class="btn btn-danger btn-small" type="submit">Archivia sede</button>
</form>
<?php endif; ?>
