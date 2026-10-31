<div class="page-head">
    <h1>Clienti</h1>
    <?php if ($auth->can('clienti', 'creazione')): ?><a class="btn" href="/app/clienti/nuovo">Nuovo cliente</a><?php endif; ?>
</div>
<form method="get" class="filters">
    <input type="search" name="q" placeholder="Cerca nome, email, telefono, P.IVA" value="<?= e($q) ?>">
    <select name="stato"><?= options($stati['cliente_stato'], $stato, true) ?></select>
    <button class="btn btn-small" type="submit">Filtra</button>
</form>
<?php if (!$clienti): ?>
    <p class="muted">Nessun cliente trovato.</p>
<?php else: ?>
<div class="table-wrap"><table>
    <thead><tr><th>Denominazione</th><th>Tipo</th><th>Contatti</th><th>Stato</th></tr></thead>
    <tbody>
    <?php foreach ($clienti as $c): ?>
        <tr>
            <td><a href="/app/clienti/<?= (int) $c['id'] ?>"><?= e($c['denominazione']) ?></a>
                <?php if ($c['etichetta']): ?><span class="badge badge-alt"><?= e($c['etichetta']) ?></span><?php endif; ?></td>
            <td><?= lbl($stati['cliente_tipo'], $c['tipo']) ?></td>
            <td><?= e($c['email']) ?><br><small><?= e($c['telefono']) ?></small></td>
            <td><span class="badge"><?= lbl($stati['cliente_stato'], $c['stato']) ?></span></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table></div>
<?php endif; ?>
