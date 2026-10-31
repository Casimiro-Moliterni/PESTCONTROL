<h1>Dashboard</h1>
<div class="cards">
    <?php if ($nuoveRichieste !== null): ?>
        <a class="card" href="/app/richieste?stato=nuova"><strong><?= (int) $nuoveRichieste ?></strong><span>Nuove richieste</span></a>
    <?php endif; ?>
    <?php foreach ($conteggi as $stato => $n): ?>
        <a class="card" href="/app/interventi?stato=<?= e($stato) ?>"><strong><?= (int) $n ?></strong><span><?= lbl($stati['intervento_stato'], $stato) ?></span></a>
    <?php endforeach; ?>
</div>

<?php if ($auth->can('interventi', 'lettura')): ?>
<h2>Prossimi interventi</h2>
<?php if (!$prossimi): ?>
    <p class="muted">Nessun intervento in programma.</p>
<?php else: ?>
<div class="table-wrap"><table>
    <thead><tr><th>Quando</th><th>Cliente / sede</th><th>Servizio</th><th>Stato</th></tr></thead>
    <tbody>
    <?php foreach ($prossimi as $i): ?>
        <tr>
            <td><?= fmt_data($i['pianificato_il']) ?></td>
            <td><a href="/app/interventi/<?= (int) $i['id'] ?>"><?= e($i['cliente']) ?></a><br><small><?= e($i['sede_nome']) ?></small></td>
            <td><?= lbl($stati['servizio_tipo'], $i['tipo_servizio']) ?></td>
            <td><span class="badge"><?= lbl($stati['intervento_stato'], $i['stato']) ?></span></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table></div>
<?php endif; endif; ?>
