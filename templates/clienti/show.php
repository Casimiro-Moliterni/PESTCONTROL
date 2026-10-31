<div class="page-head">
    <div><a href="/app/clienti" class="text-decoration-none">← Torna ai clienti</a><h1 class="h2 mt-2"><?= e($cliente['denominazione']) ?></h1></div>
    <div class="actions">
        <?php if ($auth->can('sopralluoghi', 'creazione')): ?><a class="btn btn-outline-primary" href="/app/sopralluoghi/nuovo?cliente_id=<?= (int)$cliente['id'] ?>">＋ Sopralluogo</a><?php endif; ?>
        <?php if ($auth->can('preventivi', 'creazione')): ?><a class="btn btn-outline-primary" href="/app/preventivi/nuovo?cliente_id=<?= (int)$cliente['id'] ?>">＋ Preventivo</a><?php endif; ?>
        <?php if ($auth->can('clienti', 'modifica')): ?><a class="btn btn-outline-secondary" href="/app/clienti/<?= (int) $cliente['id'] ?>/modifica">Modifica cliente</a><?php endif; ?>
        <?php if ($auth->can('clienti', 'eliminazione')): ?>
        <form method="post" action="/app/clienti/<?= (int) $cliente['id'] ?>/archivia" class="inline">
            <?= $csrf->field() ?><button class="btn btn-danger" type="submit" data-confirm="Archiviare questo cliente? I dati non saranno eliminati definitivamente, ma non comparirà più negli elenchi attivi.">Archivia cliente</button>
        </form>
        <?php endif; ?>
    </div>
</div>
<p>
    <span class="badge"><?= lbl($stati['cliente_stato'], $cliente['stato']) ?></span>
    <span class="badge badge-alt"><?= lbl($stati['cliente_tipo'], $cliente['tipo']) ?></span>
    <?php if ($cliente['etichetta']): ?><span class="badge badge-alt"><?= e($cliente['etichetta']) ?></span><?php endif; ?>
</p>
<dl class="details">
    <dt>Email</dt><dd><?= e($cliente['email'] ?: '—') ?></dd>
    <dt>Telefono</dt><dd><?= e($cliente['telefono'] ?: '—') ?></dd>
    <dt>PEC</dt><dd><?= e($cliente['pec'] ?: '—') ?></dd>
    <dt>Codice fiscale</dt><dd><?= e($cliente['codice_fiscale'] ?: '—') ?></dd>
    <dt>Partita IVA</dt><dd><?= e($cliente['partita_iva'] ?: '—') ?></dd>
    <dt>Indirizzo</dt><dd><?= e(trim(($cliente['indirizzo'] ?? '') . ' ' . ($cliente['cap'] ?? '') . ' ' . ($cliente['citta'] ?? '') . ' ' . ($cliente['provincia'] ?? '')) ?: '—') ?></dd>
    <dt>Note</dt><dd><?= nl2br(e($cliente['note'] ?: '—')) ?></dd>
</dl>

<div class="page-head">
    <h2>Sedi</h2>
    <?php if ($auth->can('clienti', 'modifica')): ?><a class="btn btn-small" href="/app/clienti/<?= (int) $cliente['id'] ?>/sedi/nuova">Nuova sede</a><?php endif; ?>
</div>
<?php if (!$sedi): ?>
    <p class="muted">Nessuna sede registrata. Aggiungine una per poter pianificare interventi.</p>
<?php else: ?>
<div class="table-wrap"><table>
    <thead><tr><th>Sede</th><th>Indirizzo</th><th>Referente</th><th></th></tr></thead>
    <tbody>
    <?php foreach ($sedi as $s): ?>
        <tr>
            <td><?= e($s['nome']) ?></td>
            <td><?= e(trim(($s['indirizzo'] ?? '') . ' ' . ($s['citta'] ?? ''))) ?></td>
            <td><?= e($s['referente_nome']) ?><br><small><?= e($s['referente_telefono']) ?></small></td>
            <td class="actions">
                <?php if ($auth->can('interventi', 'creazione')): ?><a class="btn btn-small" href="/app/interventi/nuovo?sede_id=<?= (int) $s['id'] ?>">Nuovo intervento</a><?php endif; ?>
                <?php if ($auth->can('clienti', 'modifica')): ?><a class="btn btn-small btn-secondary" href="/app/sedi/<?= (int) $s['id'] ?>/modifica">Modifica</a><?php endif; ?>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table></div>
<?php endif; ?>

<?php if ($interventi): ?>
<h2>Ultimi interventi</h2>
<div class="table-wrap"><table>
    <thead><tr><th>Quando</th><th>Sede</th><th>Servizio</th><th>Stato</th></tr></thead>
    <tbody>
    <?php foreach ($interventi as $i): ?>
        <tr>
            <td><a href="/app/interventi/<?= (int) $i['id'] ?>"><?= fmt_data($i['pianificato_il']) ?></a></td>
            <td><?= e($i['sede_nome']) ?></td>
            <td><?= lbl($stati['servizio_tipo'], $i['tipo_servizio']) ?></td>
            <td><span class="badge"><?= lbl($stati['intervento_stato'], $i['stato']) ?></span></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table></div>
<?php endif; ?>
