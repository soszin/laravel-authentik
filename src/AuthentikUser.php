<?php

namespace Soszin\LaravelAuthentik;

class AuthentikUser
{
    protected string $fullName;
    protected string $email;
    protected string $accessToken;
    protected string $refreshToken;
    protected int $expiresIn;
    protected array $groups;

    public function __construct()
    {

    }

    public function setEmail(string $email): self
    {
        $this->email = $email;
        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setFullName(string $fullName): self
    {
        $this->fullName = $fullName;
        return $this;
    }

    public function getFullName(): string
    {
        return $this->fullName;
    }

    public function getFirstName(): ?string
    {
        $fullName = explode(' ', $this->getFullName());
        return $fullName[0] ?? null;
    }

    public function getLastName(): ?string
    {
        $fullName = explode(' ', $this->getFullName());
        return $fullName[1] ?? null;
    }

    public function setAccessToken(string $accessToken): self
    {
        $this->accessToken = $accessToken;
        return $this;
    }

    public function getAccessToken(): string
    {
        return $this->accessToken;
    }

    public function setRefreshToken(string $refreshToken): self
    {
        $this->refreshToken = $refreshToken;
        return $this;
    }

    public function getRefreshToken(): string
    {
        return $this->refreshToken;
    }

    public function setExpiresIn(int $expiresIn): self
    {
        $this->expiresIn = $expiresIn;
        return $this;
    }

    public function getExpiresIn(): int
    {
        return $this->expiresIn;
    }

    public function setGroups(array $groups): self
    {
        $this->groups = $groups;
        return $this;
    }

    /**
     * @return array<int, string>
     */
    public function getGroups(): array
    {
        return $this->groups ?? [];
    }


}
