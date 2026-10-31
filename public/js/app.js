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

  const materialForm = document.querySelector('form[action$="/prodotti"]');
  if (materialForm) {
    const product = materialForm.querySelector('[name="prodotto_id"]');
    const lot = materialForm.querySelector('[data-lot-select]');
    const updateLot = () => {
      if (!product || !lot) return;
      const selected = product.selectedOptions[0];
      lot.required = selected?.dataset.lottiObbligatori === '1';
      [...lot.options].forEach((option) => {
        if (!option.value) return;
        option.hidden = option.dataset.prodotto !== product.value;
        if (option.hidden && option.selected) lot.value = '';
      });
    };
    product?.addEventListener('change', updateLot); updateLot();
  }

  // Filtra le sedi in base al cliente selezionato, evitando abbinamenti errati.
  document.querySelectorAll('[data-site-owner]').forEach((client) => {
    const form = client.closest('form');
    const site = form?.querySelector('[data-site-select]');
    if (!site) return;
    const updateSites = () => {
      [...site.options].forEach((option) => {
        if (!option.value) return;
        option.hidden = option.dataset.cliente !== client.value;
        if (option.hidden && option.selected) site.value = '';
      });
    };
    client.addEventListener('change', updateSites);
    updateSites();
  });

  document.querySelectorAll('[data-stock-type]').forEach((select) => select.addEventListener('change', () => {
    const label = document.querySelector('[data-stock-quantity-label]');
    const input = label?.querySelector('input');
    if (label && input) {
      label.firstChild.textContent = select.value === 'rettifica' ? 'Giacenza fisica rilevata' : 'Quantità';
      input.value = '';
    }
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

  document.querySelectorAll('[data-qr-code]').forEach((target) => {
    if (window.QRCode) new window.QRCode(target, { text:target.dataset.qrValue, width:220, height:220, correctLevel:window.QRCode.CorrectLevel.M });
  });

  const deviceCheck = document.querySelector('[data-device-check]');
  document.querySelectorAll('[data-use-location]').forEach((button) => button.addEventListener('click', () => {
    const status = deviceCheck?.querySelector('[data-location-status]');
    if (!navigator.geolocation) { if (status) status.textContent = 'La posizione non è disponibile su questo dispositivo.'; return; }
    if (status) status.textContent = 'Richiesta posizione in corso…';
    navigator.geolocation.getCurrentPosition((position) => {
      if (deviceCheck) {
        deviceCheck.querySelector('[name="latitudine"]').value = position.coords.latitude.toFixed(7);
        deviceCheck.querySelector('[name="longitudine"]').value = position.coords.longitude.toFixed(7);
      }
      if (status) status.textContent = 'Posizione aggiunta al controllo. Non viene salvata finché non invii il modulo.';
    }, () => { if (status) status.textContent = 'Non è stato possibile leggere la posizione. Puoi salvare il controllo senza coordinate.'; }, { enableHighAccuracy:true, timeout:10000, maximumAge:0 });
  }));

  const map = document.querySelector('#operationMap');
  const mapDataNode = document.querySelector('#mapData');
  if (map && mapDataNode && window.L) {
    try {
      const data = JSON.parse(mapDataNode.textContent);
      const leafletMap = L.map(map).setView([41.9, 12.5], 6);
      const layer = L.featureGroup().addTo(leafletMap);
      L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { maxZoom:19, attribution:'&copy; OpenStreetMap contributors' }).addTo(leafletMap);
      const filter = document.querySelector('[data-map-filter]');
      const addPopup = (lat, lng, label, description, url) => {
        const marker = L.marker([Number(lat), Number(lng)]).addTo(layer);
        const box = document.createElement('div');
        const title = document.createElement('strong'); title.textContent = label; box.append(title);
        const line = document.createElement('div'); line.textContent = description; box.append(line);
        if (url) { const link = document.createElement('a'); link.href = url; link.textContent = 'Apri scheda'; box.append(link); }
        marker.bindPopup(box);
      };
      const drawMap = () => {
        layer.clearLayers();
        const value = filter?.value || 'tutti';
        if (value === 'tutti' || value === 'sedi') (data.sedi || []).forEach((site) => addPopup(site.latitudine,site.longitudine,site.cliente,`${site.sede} · ${site.indirizzo || site.citta || ''}`,`/app/clienti/${site.cliente_id || ''}`));
        if (value !== 'sedi') (data.dispositivi || []).filter((item) => value === 'tutti' || item.stato === value).forEach((item) => addPopup(item.latitudine,item.longitudine,`${item.codice} · ${item.tipo}`,`${item.cliente} · ${item.sede} · ${item.posizione || ''}`,`/app/dispositivi/${item.id}`));
        if (layer.getLayers().length) leafletMap.fitBounds(layer.getBounds().pad(.12));
      };
      filter?.addEventListener('change', drawMap); drawMap();
    } catch (error) { map.textContent = 'Impossibile caricare la mappa. Puoi consultare l’elenco dei dispositivi.'; }
  }

  const startScan = document.querySelector('[data-start-scanner]');
  const stopScan = document.querySelector('[data-stop-scanner]');
  if (startScan) {
    const video = document.querySelector('#qrVideo');
    const status = document.querySelector('[data-scanner-status]');
    let stream = null; let scanning = false;
    const stop = () => { scanning = false; stream?.getTracks().forEach((track) => track.stop()); stream = null; if (video) video.srcObject = null; if (startScan) startScan.disabled = false; if (stopScan) stopScan.disabled = true; };
    stopScan?.addEventListener('click', stop);
    startScan.addEventListener('click', async () => {
      if (!('BarcodeDetector' in window) || !navigator.mediaDevices?.getUserMedia) { if (status) status.textContent = 'La scansione automatica non è supportata qui. Inserisci il codice nel campo qui sotto.'; return; }
      try {
        const detector = new BarcodeDetector({ formats:['qr_code'] });
        stream = await navigator.mediaDevices.getUserMedia({ video:{ facingMode:{ ideal:'environment' } }, audio:false });
        video.srcObject = stream; await video.play(); scanning = true; startScan.disabled = true; if (stopScan) stopScan.disabled = false;
        if (status) status.textContent = 'Inquadra il QR code. La fotocamera si fermerà dopo la lettura.';
        const scanFrame = async () => {
          if (!scanning) return;
          try { const codes = await detector.detect(video); if (codes.length) { const value=codes[0].rawValue; stop(); if (value.startsWith(window.location.origin+'/app/dispositivi/qr/')) window.location.assign(value); else if (status) status.textContent='QR letto, ma non è un collegamento dispositivo di questa applicazione.'; return; } } catch (_) { /* attende il fotogramma successivo */ }
          window.requestAnimationFrame(scanFrame);
        };
        scanFrame();
      } catch (_) { stop(); if (status) status.textContent = 'Fotocamera non disponibile o permesso negato. Inserisci il codice manualmente.'; }
    });
    window.addEventListener('pagehide', stop);
  }

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
