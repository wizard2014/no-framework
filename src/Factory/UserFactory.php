<?php

declare(strict_types=1);

namespace App\Factory;

use App\Models\User;

class UserFactory
{
    public static function fromRequest(array $data): User
    {
        $user = new User();

        $user->setEmail($data['email']);
        $user->setName($data['name']);
        $user->setPassword($data['password']);

        return $user;
    }
}
