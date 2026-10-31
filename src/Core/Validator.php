<?php
declare(strict_types=1);

namespace App\Core;

/**
 * Regole supportate: required, email, max:N, in:a,b,c, datetime, int, decimal.
 * I campi vuoti non obbligatori vengono saltati.
 */
final class Validator
{
    public static function validate(array $data, array $rules): array
    {
        $errors = [];
        foreach ($rules as $field => $ruleString) {
            $value = $data[$field] ?? '';
            foreach (explode('|', $ruleString) as $rule) {
                [$name, $arg] = array_pad(explode(':', $rule, 2), 2, null);
                if ($name !== 'required' && ($value === '' || $value === null)) {
                    continue;
                }
                $message = self::check($name, (string) $value, $arg);
                if ($message !== null) {
                    $errors[$field] = $message;
                    break;
                }
            }
        }
        return $errors;
    }

    private static function check(string $rule, string $value, ?string $arg): ?string
    {
        return match ($rule) {
            'required' => $value === '' ? 'Campo obbligatorio.' : null,
            'email'    => filter_var($value, FILTER_VALIDATE_EMAIL) ? null : 'Email non valida.',
            'max'      => mb_strlen($value) > (int) $arg ? "Massimo $arg caratteri." : null,
            'in'       => in_array($value, explode(',', (string) $arg), true) ? null : 'Valore non valido.',
            'datetime' => strtotime($value) === false ? 'Data/ora non valida.' : null,
            'int'      => ctype_digit($value) ? null : 'Deve essere un numero intero.',
            'decimal'  => is_numeric($value) ? null : 'Deve essere un numero.',
            default    => null,
        };
    }
}
