<?php

namespace App\Rules;

interface RuleInterface
{
    public function validate(string $field, string $value, array $params, array $fields);
}
