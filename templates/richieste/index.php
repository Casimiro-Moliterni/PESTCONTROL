<h1>Richieste</h1>
<form method="get" class="filters">
    <select name="stato"><?= options($stati['richiesta_stato'], $stato, true) ?></select>
    <button class="btn btn-small" type="submit">Filtra</button>
</form>
<?php if (!$richieste): ?>
    <p class="muted">Nessuna richiesta.</p>
<?php else: ?>
<div class="table-wrap"><table>
    <thead><tr><th>Ricevuta</th><th>Richiedente</th><th>Tipo</th><th>Stato</th><th>Incaricato</th></tr></thead>
    <tbody>
    <?php foreach ($richieste as $r): ?>
        <tr>
            <td><a href="/app/richieste/<?= (int) $r['id'] ?>"><?= fmt_data($r['ricevuta_il']) ?></a></td>
            <td><?= e($r['nome']) ?><br><small><?= e($r['azienda_richiedente']) ?></small></td>
            <td><?= lbl($stati['richiesta_tipo'], $r['tipo']) ?></td>
            <td><span class="badge"><?= lbl($stati['richiesta_stato'], $r['stato']) ?></span></td>
            <td><?= e($r['assegnato'] ?? '—') ?></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table></div>
<?php endif; ?>
