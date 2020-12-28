<?php

namespace App\Auth\Hashing;

interface HasherInterface
{
    public function create(string $plain): string;

    public function check(string $plain, string $hash): bool;

    public function needRehash(string $hash): bool;
}
