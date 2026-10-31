<?php
declare(strict_types=1);

namespace App\Repository;

final class ClienteRepository extends Repository
{
    protected string $table = 'clienti';
    protected array $fillable = [
        'tipo', 'denominazione', 'codice_fiscale', 'partita_iva', 'email', 'telefono', 'pec',
        'indirizzo', 'citta', 'cap', 'provincia', 'stato', 'etichetta', 'note',
    ];

    public function search(?string $q, ?string $stato): array
    {
        $where = '1=1';
        $params = [];
        if ($q) {
            $where .= ' AND (denominazione LIKE :q1 OR email LIKE :q2 OR telefono LIKE :q3 OR partita_iva LIKE :q4)';
            foreach (['q1', 'q2', 'q3', 'q4'] as $p) {
                $params[$p] = "%$q%";
            }
        }
        if ($stato) {
            $where .= ' AND stato = :stato';
            $params['stato'] = $stato;
        }
        return $this->all($where, $params, 'denominazione ASC');
    }
}
