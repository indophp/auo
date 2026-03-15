<?php

namespace Indophp\Auo;

class Oto
{
    protected $aut;

    public function __construct()
    {
        $this->aut = new Aut();
    }

    public function can(string $role): bool
    {
        $map = [
            'admin' => Aut::ROLE_ADMIN,
            'user' => Aut::ROLE_USER,
            'member' => Aut::ROLE_MEMBER
        ];

        return isset($map[$role]) && $this->aut->hasRole($map[$role]);
    }
}
