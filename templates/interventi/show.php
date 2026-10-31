<div class="page-head">
    <h1>Intervento #<?= (int) $intervento['id'] ?></h1>
    <div class="actions">
        <?php if ($auth->can('interventi', 'modifica')): ?><a class="btn btn-small" href="/app/interventi/<?= (int) $intervento['id'] ?>/modifica">Modifica</a><?php endif; ?>
        <?php if ($auth->can('interventi', 'eliminazione')): ?>
        <form method="post" action="/app/interventi/<?= (int) $intervento['id'] ?>/archivia" class="inline">
            <?= $csrf->field() ?><button class="btn btn-small btn-danger" type="submit">Archivia</button>
        </form>
        <?php endif; ?>
    </div>
</div>
<p>
    <span class="badge"><?= lbl($stati['intervento_stato'], $intervento['stato']) ?></span>
    <span class="badge badge-alt"><?= lbl($stati['servizio_tipo'], $intervento['tipo_servizio']) ?></span>
</p>
<dl class="details">
    <dt>Cliente</dt><dd><?= e($intervento['cliente']) ?></dd>
    <dt>Sede</dt><dd><?= e($intervento['sede_nome']) ?> — <?= e(trim(($intervento['sede_indirizzo'] ?? '') . ' ' . ($intervento['sede_citta'] ?? ''))) ?></dd>
    <dt>Referente in sede</dt><dd><?= e($intervento['referente_nome'] ?: '—') ?> <?= e($intervento['referente_telefono']) ?></dd>
    <dt>Accesso</dt><dd><?= e($intervento['orari_consentiti'] ?: '—') ?><br><?= nl2br(e($intervento['istruzioni_accesso'] ?? '')) ?></dd>
    <dt>Infestante</dt><dd><?= e($intervento['infestante'] ?: '—') ?></dd>
    <dt>Pianificato</dt><dd><?= fmt_data($intervento['pianificato_il']) ?><?= $intervento['durata_stimata_min'] ? ' (' . (int) $intervento['durata_stimata_min'] . ' min)' : '' ?></dd>
    <dt>Operatori</dt><dd><?= $operatori ? e(implode(', ', array_map(fn($o) => $o['nome'] . ' ' . $o['cognome'], $operatori))) : '—' ?></dd>
    <dt>Descrizione</dt><dd><?= nl2br(e($intervento['descrizione'] ?: '—')) ?></dd>
    <dt>Istruzioni operative</dt><dd><?= nl2br(e($intervento['istruzioni'] ?: '—')) ?></dd>
    <dt>Inizio effettivo</dt><dd><?= fmt_data($intervento['inizio_effettivo']) ?></dd>
    <dt>Fine effettiva</dt><dd><?= fmt_data($intervento['fine_effettiva']) ?></dd>
    <dt>Esito</dt><dd><?= nl2br(e($intervento['esito'] ?: '—')) ?></dd>
    <dt>Note operatore</dt><dd><?= nl2br(e($intervento['note_operatore'] ?: '—')) ?></dd>
</dl>

<?php if ($puoEseguire): ?>
<h2>Scheda di esecuzione</h2>
<form method="post" action="/app/interventi/<?= (int) $intervento['id'] ?>/esecuzione" class="form">
    <?= $csrf->field() ?>
    <label>Stato
        <select name="stato"><?= options($stati['intervento_stato_operatore'], $intervento['stato']) ?></select>
    </label>
    <label>Esito <textarea name="esito" rows="3"><?= e($intervento['esito']) ?></textarea></label>
    <label>Note, anomalie, attività da ripetere <textarea name="note_operatore" rows="3"><?= e($intervento['note_operatore']) ?></textarea></label>
    <div class="actions"><button class="btn" type="submit">Aggiorna scheda</button></div>
</form>
<?php endif; ?>
<section class="card border-0 shadow-sm mt-4"><div class="card-body"><h2 class="h5">Rapporto e allegati</h2><p class="text-secondary">Esito, attività svolte e anomalie sono registrati nella scheda di esecuzione.</p>
<?php if ($auth->can('interventi','modifica')): ?><form method="post" enctype="multipart/form-data" action="/app/interventi/<?= (int)$intervento['id'] ?>/allegati" class="d-flex flex-wrap gap-2 mb-3"><?= $csrf->field() ?><input class="form-control" style="max-width:420px" type="file" name="file" accept="image/jpeg,image/png,image/webp,application/pdf" required><button class="btn btn-outline-primary">Carica PDF o foto</button></form><?php endif ?>
<?php if (!$allegati): ?><p class="mb-0 text-secondary">Nessun allegato.</p><?php else: ?><ul class="list-group list-group-flush"><?php foreach($allegati as $a): ?><li class="list-group-item d-flex justify-content-between"><a href="/app/allegati/<?= (int)$a['id'] ?>"><?= e($a['nome_originale']) ?></a><small class="text-secondary"><?= number_format((int)$a['dimensione']/1024,0) ?> KB · <?= e($a['creato_il']) ?></small></li><?php endforeach ?></ul><?php endif ?></div></section>
