<?php

declare(strict_types=1);

namespace App\Session;

use function array_merge;

class Flash
{
    private $session;
    private $messages;

    public function __construct(SessionStoreInterface $session)
    {
        $this->session = $session;

        $this->loadFlashMessagesIntoCache();

        $this->clear();
    }

    public function get(string $key)
    {
        if ($this->has($key)) {
            return $this->messages[$key];
        }

        return null;
    }

    public function has(string $key): bool
    {
        return isset($this->messages[$key]);
    }

    public function now(string $key, $value): void
    {
        $this->session->set('flash', array_merge(
            $this->session->get('flash') ?? [],
            [$key => $value]
        ));
    }

    private function getAll()
    {
        return $this->session->get('flash');
    }

    private function loadFlashMessagesIntoCache(): void
    {
        $this->messages = $this->getAll();
    }

    private function clear(): void
    {
        $this->session->clear('flash');
    }
}
