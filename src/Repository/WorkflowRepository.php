<?php
declare(strict_types=1);
namespace App\Repository;

/** Base tenant scoped repository per i moduli operativi dell'MVP. */
abstract class WorkflowRepository extends Repository
{
    public function records(): array
    {
        return $this->all('1=1', [], 'creato_il DESC');
    }
}
