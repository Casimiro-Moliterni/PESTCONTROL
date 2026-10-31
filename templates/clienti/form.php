<h1><?= $id ? 'Modifica cliente' : 'Nuovo cliente' ?></h1>
<form method="post" action="<?= $id ? '/app/clienti/' . (int) $id : '/app/clienti' ?>" class="form">
    <?= $csrf->field() ?>
    <label>Nome e cognome / ragione sociale *
        <input name="denominazione" value="<?= v($cliente, 'denominazione') ?>" required>
        <?= field_error($errors, 'denominazione') ?>
    </label>
    <div class="grid">
        <label>Tipologia *
            <select name="tipo"><?= options($stati['cliente_tipo'], $cliente['tipo'] ?? null) ?></select>
            <?= field_error($errors, 'tipo') ?>
        </label>
        <label>Stato *
            <select name="stato"><?= options($stati['cliente_stato'], $cliente['stato'] ?? null) ?></select>
            <?= field_error($errors, 'stato') ?>
        </label>
        <label>Etichetta commerciale (es. VIP)
            <input name="etichetta" value="<?= v($cliente, 'etichetta') ?>">
            <?= field_error($errors, 'etichetta') ?>
        </label>
    </div>
    <div class="grid">
        <label>Codice fiscale <input name="codice_fiscale" value="<?= v($cliente, 'codice_fiscale') ?>"><?= field_error($errors, 'codice_fiscale') ?></label>
        <label>Partita IVA <input name="partita_iva" value="<?= v($cliente, 'partita_iva') ?>"><?= field_error($errors, 'partita_iva') ?></label>
    </div>
    <div class="grid">
        <label>Email <input type="email" name="email" value="<?= v($cliente, 'email') ?>"><?= field_error($errors, 'email') ?></label>
        <label>Telefono <input name="telefono" value="<?= v($cliente, 'telefono') ?>"><?= field_error($errors, 'telefono') ?></label>
        <label>PEC <input type="email" name="pec" value="<?= v($cliente, 'pec') ?>"><?= field_error($errors, 'pec') ?></label>
    </div>
    <label>Indirizzo (fatturazione) <input name="indirizzo" value="<?= v($cliente, 'indirizzo') ?>"><?= field_error($errors, 'indirizzo') ?></label>
    <div class="grid">
        <label>Città <input name="citta" value="<?= v($cliente, 'citta') ?>"></label>
        <label>CAP <input name="cap" value="<?= v($cliente, 'cap') ?>"></label>
        <label>Provincia <input name="provincia" maxlength="5" value="<?= v($cliente, 'provincia') ?>"></label>
    </div>
    <label>Note <textarea name="note" rows="4"><?= v($cliente, 'note') ?></textarea></label>
    <div class="actions">
        <button class="btn" type="submit">Salva</button>
        <a class="btn btn-secondary" href="<?= $id ? '/app/clienti/' . (int) $id : '/app/clienti' ?>">Annulla</a>
    </div>
</form>
