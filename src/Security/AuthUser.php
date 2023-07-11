<?php

namespace App\Security;

use Symfony\Component\Security\Core\User\UserInterface;

class AuthUser implements UserInterface
{
    private string $username;

    /** @var string[] */
    private array $roles;

    public function __construct(array $credentials)
    {
        $this->username = $credentials['username'];
        $this->roles = array_unique(array_merge($credentials['roles'] ?? [], ['ROLE_USER']));
    }

    /**
     * @return string[]
     */
    public function getRoles(): array
    {
        return $this->roles;
    }

    public function getPassword(): string
    {
        return '';
    }

    public function eraseCredentials(): void
    {
    }

    public function getUserIdentifier(): string
    {
        return $this->username;
    }
}
