<?php
declare(strict_types=1);

/** Escape HTML. */
function e(mixed $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

/** Valore precedente di un campo form, già escapato. */
function v(array $data, string $key): string
{
    return e($data[$key] ?? '');
}

/** Messaggio di errore di un campo form. */
function field_error(array $errors, string $key): string
{
    return isset($errors[$key]) ? '<small class="error">' . e($errors[$key]) . '</small>' : '';
}

/** Formatta una data/ora SQL. */
function fmt_data(?string $value, string $format = 'd/m/Y H:i'): string
{
    return $value ? date($format, strtotime($value)) : '—';
}

/** Etichetta di un valore da una mappa chiave => etichetta. */
function lbl(array $map, ?string $key): string
{
    return e($map[$key] ?? ($key ?? '—'));
}

/** Elenco <option> da una mappa chiave => etichetta. */
function options(array $map, ?string $selected = null, bool $empty = false): string
{
    $html = $empty ? '<option value="">—</option>' : '';
    foreach ($map as $key => $label) {
        $sel = ((string) $key === (string) $selected) ? ' selected' : '';
        $html .= '<option value="' . e($key) . '"' . $sel . '>' . e($label) . '</option>';
    }
    return $html;
}
