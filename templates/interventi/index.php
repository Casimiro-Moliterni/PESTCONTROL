<div class="page-head">
    <h1>Interventi</h1>
    <?php if ($auth->can('interventi', 'creazione')): ?><a class="btn" href="/app/interventi/nuovo">Nuovo intervento</a><?php endif; ?>
</div>
<form method="get" class="filters">
    <select name="stato"><?= options($stati['intervento_stato'], $stato, true) ?></select>
    <button class="btn btn-small" type="submit">Filtra</button>
</form>
<?php if (!$interventi): ?>
    <p class="muted">Nessun intervento.</p>
<?php else: ?>
<div class="table-wrap"><table>
    <thead><tr><th>Quando</th><th>Cliente / sede</th><th>Servizio</th><th>Stato</th></tr></thead>
    <tbody>
    <?php foreach ($interventi as $i): ?>
        <tr>
            <td><a href="/app/interventi/<?= (int) $i['id'] ?>"><?= fmt_data($i['pianificato_il']) ?></a></td>
            <td><?= e($i['cliente']) ?><br><small><?= e($i['sede_nome']) ?> — <?= e($i['sede_citta']) ?></small></td>
            <td><?= lbl($stati['servizio_tipo'], $i['tipo_servizio']) ?><br><small><?= e($i['infestante']) ?></small></td>
            <td><span class="badge"><?= lbl($stati['intervento_stato'], $i['stato']) ?></span></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table></div>
<?php endif; ?>
