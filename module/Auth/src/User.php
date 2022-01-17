<?php

namespace Auth;

class User implements \JsonSerializable
{
    private string $name = 'YourName';

    private string $email = 'your@email.address';

    public function getName(): string
    {
        return $this->name;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function jsonSerialize(): array
    {
        return [
            'email' => $this->getEmail(),
            'name'  => $this->getName(),
        ];
    }
}