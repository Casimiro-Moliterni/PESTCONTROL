<h1>Richiesta #<?= (int) $richiesta['id'] ?></h1>
<p>
    <span class="badge"><?= lbl($stati['richiesta_stato'], $richiesta['stato']) ?></span>
    <span class="badge badge-alt"><?= lbl($stati['richiesta_tipo'], $richiesta['tipo']) ?></span>
</p>
<dl class="details">
    <dt>Ricevuta il</dt><dd><?= fmt_data($richiesta['ricevuta_il']) ?> (<?= e($richiesta['origine']) ?>)</dd>
    <dt>Nome</dt><dd><?= e($richiesta['nome']) ?></dd>
    <dt>Azienda</dt><dd><?= e($richiesta['azienda_richiedente'] ?: '—') ?></dd>
    <dt>Email</dt><dd><?= e($richiesta['email'] ?: '—') ?></dd>
    <dt>Telefono</dt><dd><?= e($richiesta['telefono'] ?: '—') ?></dd>
    <dt>Località</dt><dd><?= e($richiesta['localita'] ?: '—') ?></dd>
    <dt>Preferenza contatto</dt><dd><?= e($richiesta['preferenza_contatto'] ?: '—') ?></dd>
    <dt>Descrizione</dt><dd><?= nl2br(e($richiesta['descrizione'])) ?></dd>
</dl>

<?php if ($auth->can('richieste', 'modifica')): ?>
<h2>Gestione</h2>
<form method="post" action="/app/richieste/<?= (int) $richiesta['id'] ?>" class="form">
    <?= $csrf->field() ?>
    <div class="grid">
        <label>Stato <select name="stato"><?= options($stati['richiesta_stato'], $richiesta['stato']) ?></select></label>
        <label>Incaricato
            <select name="assegnato_a">
                <option value="">—</option>
                <?php foreach ($utenti as $u): ?>
                    <option value="<?= (int) $u['id'] ?>" <?= (int) $richiesta['assegnato_a'] === (int) $u['id'] ? 'selected' : '' ?>><?= e($u['nome'] . ' ' . $u['cognome']) ?></option>
                <?php endforeach; ?>
            </select>
        </label>
    </div>
    <div class="actions"><button class="btn" type="submit">Salva</button></div>
</form>

<?php if ($richiesta['cliente_id']): ?>
    <p>Convertita nel cliente <a href="/app/clienti/<?= (int) $richiesta['cliente_id'] ?>">#<?= (int) $richiesta['cliente_id'] ?></a>.</p>
<?php elseif ($auth->can('clienti', 'creazione')): ?>
    <form method="post" action="/app/richieste/<?= (int) $richiesta['id'] ?>/converti" class="inline">
        <?= $csrf->field() ?><button class="btn btn-secondary" type="submit">Converti in cliente</button>
    </form>
<?php endif; endif; ?>
