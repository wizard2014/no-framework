<?php

declare(strict_types=1);

namespace App\Rules;

use App\Models\User;
use Doctrine\ORM\EntityManager;

class Exists implements RuleInterface
{
    private $entityManager;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function validate(string $field, string $value, array $params, array $fields): bool
    {
        /** @var User|null $result */
        $result = $this->entityManager->getRepository($params[0])->findOneBy([$field => $value]);

        return $result === null;
    }
}
