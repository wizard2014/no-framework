<?php

declare(strict_types=1);

namespace App\Models;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="users")
 */
class User
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @var int
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=45)
     * @var string
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=45)
     * @var string
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=45)
     * @var string
     */
    private $password;

    /**
     * @ORM\Column(name="remember_token", type="string", length=45, nullable=true)
     * @var string
     */
    private $rememberToken;

    /**
     * @ORM\Column(name="remember_identifier", type="string", length=45, nullable=true)
     * @var string
     */
    private $rememberIdentifier;

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getRememberToken(): ?string
    {
        return $this->rememberToken;
    }

    public function setRememberToken(string $rememberToken): self
    {
        $this->rememberToken = $rememberToken;

        return $this;
    }

    public function getRememberIdentifier(): ?string
    {
        return $this->rememberIdentifier;
    }

    public function setRememberIdentifier(string $rememberIdentifier): self
    {
        $this->rememberIdentifier = $rememberIdentifier;

        return $this;
    }
}
