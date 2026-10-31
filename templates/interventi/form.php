<h1><?= $id ? 'Modifica intervento' : 'Nuovo intervento' ?></h1>
<?php if (!$sedi): ?>
    <p class="flash flash-error">Per creare un intervento serve almeno una sede: aggiungila dalla scheda cliente.</p>
<?php endif; ?>
<form method="post" action="<?= $id ? '/app/interventi/' . (int) $id : '/app/interventi' ?>" class="form">
    <?= $csrf->field() ?>
    <label>Cliente / sede *
        <select name="sede_id" required>
            <option value="">— seleziona —</option>
            <?php $corrente = null; foreach ($sedi as $s):
                if ($s['cliente'] !== $corrente) {
                    if ($corrente !== null) echo '</optgroup>';
                    echo '<optgroup label="' . e($s['cliente']) . '">';
                    $corrente = $s['cliente'];
                } ?>
                <option value="<?= (int) $s['id'] ?>" <?= (string) ($intervento['sede_id'] ?? '') === (string) $s['id'] ? 'selected' : '' ?>><?= e($s['nome']) ?></option>
            <?php endforeach; if ($corrente !== null) echo '</optgroup>'; ?>
        </select>
        <?= field_error($errors, 'sede_id') ?>
    </label>
    <div class="grid">
        <label>Tipologia di servizio *
            <select name="tipo_servizio"><?= options($stati['servizio_tipo'], $intervento['tipo_servizio'] ?? null) ?></select>
            <?= field_error($errors, 'tipo_servizio') ?>
        </label>
        <label>Infestante <input name="infestante" value="<?= v($intervento, 'infestante') ?>"><?= field_error($errors, 'infestante') ?></label>
        <label>Stato *
            <select name="stato"><?= options($stati['intervento_stato'], $intervento['stato'] ?? null) ?></select>
            <?= field_error($errors, 'stato') ?>
        </label>
    </div>
    <div class="grid">
        <label>Data e ora pianificate
            <input type="datetime-local" name="pianificato_il" value="<?= v($intervento, 'pianificato_il') ?>">
            <?= field_error($errors, 'pianificato_il') ?>
        </label>
        <label>Durata stimata (minuti)
            <input name="durata_stimata_min" inputmode="numeric" value="<?= v($intervento, 'durata_stimata_min') ?>">
            <?= field_error($errors, 'durata_stimata_min') ?>
        </label>
    </div>
    <label>Descrizione <textarea name="descrizione" rows="3"><?= v($intervento, 'descrizione') ?></textarea></label>
    <label>Istruzioni operative <textarea name="istruzioni" rows="3"><?= v($intervento, 'istruzioni') ?></textarea></label>
    <fieldset>
        <legend>Operatori assegnati</legend>
        <?php foreach ($utenti as $u): ?>
            <label class="check">
                <input type="checkbox" name="operatori[]" value="<?= (int) $u['id'] ?>" <?= in_array((int) $u['id'], array_map('intval', $assegnati), true) ? 'checked' : '' ?>>
                <?= e($u['nome'] . ' ' . $u['cognome']) ?> <small>(<?= e($u['ruolo']) ?>)</small>
            </label>
        <?php endforeach; ?>
    </fieldset>
    <div class="actions">
        <button class="btn" type="submit">Salva</button>
        <a class="btn btn-secondary" href="<?= $id ? '/app/interventi/' . (int) $id : '/app/interventi' ?>">Annulla</a>
    </div>
</form>
