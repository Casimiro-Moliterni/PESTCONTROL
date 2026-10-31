(() => {
  'use strict';

  const currentPath = window.location.pathname.replace(/\/$/, '') || '/app';
  document.querySelectorAll('.navbar .nav-link, .navbar .dropdown-item').forEach((link) => {
    if (link.getAttribute('href') === '#') return;
    const href = new URL(link.href, window.location.origin).pathname.replace(/\/$/, '');
    if (href === currentPath || (href !== '/app' && currentPath.startsWith(`${href}/`))) {
      link.classList.add('active');
      link.setAttribute('aria-current', 'page');
      link.closest('.dropdown')?.querySelector('.dropdown-toggle')?.classList.add('active');
    }
  });

  // Richiesta di conferma per azioni che archiviano o annullano dati.
  document.querySelectorAll('[data-confirm]').forEach((el) => {
    el.addEventListener('click', (event) => {
      if (!window.confirm(el.dataset.confirm || 'Confermi questa operazione?')) event.preventDefault();
    });
  });

  // Ricerca immediata nelle tabelle già caricate, senza sostituire i filtri server.
  document.querySelectorAll('[data-live-filter]').forEach((input) => {
    const target = document.querySelector(input.dataset.liveFilter);
    if (!target) return;
    const rows = [...target.querySelectorAll('tbody tr')];
    input.addEventListener('input', () => {
      const query = input.value.trim().toLocaleLowerCase('it');
      rows.forEach((row) => { row.hidden = query !== '' && !row.textContent.toLocaleLowerCase('it').includes(query); });
    });
  });

  // Evidenzia subito il primo campo obbligatorio mancante.
  document.querySelectorAll('form').forEach((form) => {
    form.addEventListener('submit', (event) => {
      if (!form.checkValidity()) {
        event.preventDefault();
        form.classList.add('was-validated');
        form.querySelector(':invalid')?.focus();
      }
    });
  });

  // Anteprima importo preventivo: aiuta a controllare imponibile, IVA e sconto.
  const amountForm = document.querySelector('[data-quote-calculator]');
  if (amountForm) {
    const updateTotal = () => {
      const base = Number(amountForm.querySelector('[name="imponibile"]')?.value || 0);
      const iva = Number(amountForm.querySelector('[name="iva"]')?.value || 0);
      const sconto = Number(amountForm.querySelector('[name="sconto"]')?.value || 0);
      const total = Math.max(0, base - sconto) * (1 + iva / 100);
      const output = amountForm.querySelector('[data-quote-total]');
      if (output) output.textContent = new Intl.NumberFormat('it-IT', { style:'currency', currency:'EUR' }).format(total);
    };
    amountForm.querySelectorAll('[name="imponibile"],[name="iva"],[name="sconto"]').forEach((field) => field.addEventListener('input', updateTotal));
    updateTotal();
  }

  // Mostra il nome del file scelto prima dell'invio.
  document.querySelectorAll('input[type="file"]').forEach((input) => input.addEventListener('change', () => {
    const hint = input.closest('form')?.querySelector('[data-file-name]');
    if (hint) hint.textContent = input.files?.[0]?.name || 'Nessun file selezionato';
  }));

  document.querySelectorAll('[data-toggle-password]').forEach((button) => button.addEventListener('click', () => {
    const field = document.querySelector(button.dataset.togglePassword);
    if (!field) return;
    const visible = field.type === 'password';
    field.type = visible ? 'text' : 'password';
    button.textContent = visible ? 'Nascondi' : 'Mostra';
    button.setAttribute('aria-label', visible ? 'Nascondi password' : 'Mostra password');
  }));

  document.querySelectorAll('[data-check-all]').forEach((master) => master.addEventListener('change', () => {
    document.querySelectorAll(`[data-permission-module="${CSS.escape(master.dataset.checkAll)}"]`).forEach((item) => { item.checked = master.checked; });
  }));

  document.querySelectorAll('[data-character-count]').forEach((form) => {
    const field = form.querySelector('[maxlength]');
    const output = form.querySelector('[data-char-count]');
    if (!field || !output) return;
    const update = () => { output.textContent = `${field.value.length} / ${field.maxLength}`; };
    field.addEventListener('input', update);
    update();
  });

  document.querySelectorAll('[data-calendar-step]').forEach((button) => button.addEventListener('click', () => {
    const dateInput = document.querySelector('[name="data"]');
    if (!dateInput?.value) return;
    const date = new Date(`${dateInput.value}T12:00:00`);
    const view = new URLSearchParams(window.location.search).get('vista') || 'settimana';
    const amount = Number(button.dataset.calendarStep || 0);
    if (view === 'mese') date.setMonth(date.getMonth() + amount);
    else date.setDate(date.getDate() + amount * (view === 'giorno' ? 1 : 7));
    dateInput.value = `${date.getFullYear()}-${String(date.getMonth()+1).padStart(2,'0')}-${String(date.getDate()).padStart(2,'0')}`;
    dateInput.form?.requestSubmit();
  }));

  // Suggerimento pratico quando si pianifica un intervento per un operatore.
  const scheduleForm = document.querySelector('[data-intervention-form]');
  if (scheduleForm) {
    const date = scheduleForm.querySelector('[name="pianificato_il"]');
    const operators = scheduleForm.querySelectorAll('[name="operatori[]"]');
    const hint = scheduleForm.querySelector('[data-schedule-hint]');
    const updateHint = () => {
      if (hint) hint.textContent = [...operators].some((item) => item.checked) && !date?.value
        ? 'Hai assegnato un operatore: aggiungi data e ora per inserirlo in calendario.' : '';
    };
    operators.forEach((item) => item.addEventListener('change', updateHint));
    date?.addEventListener('input', updateHint);
  }
})();
