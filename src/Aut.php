<?php

namespace Indophp\Auo;

class Aut
{
    const ROLE_ADMIN = 1;
    const ROLE_USER = 2;
    const ROLE_MEMBER = 3;

    protected $userData;

    public function __construct()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        $this->userData = $_SESSION ?? [];
    }

    public function setUserSession(array $data, string $type)
    {
        $_SESSION['login'] = true;
        $_SESSION['user_type'] = $type;
        $_SESSION['ha_id'] = $data['ha_id'] ?? null;

        switch ($type) {

            case 'admin':
                $_SESSION['ots'] = self::ROLE_ADMIN;
                $_SESSION['aid'] = $data['aid'];
                $_SESSION['nama'] = $data['nama'];
            break;

            case 'user':
                $_SESSION['ots'] = self::ROLE_USER;
                $_SESSION['uid'] = $data['uid'];
                $_SESSION['nama'] = $data['nama'];
            break;

            case 'member':
                $_SESSION['ots'] = self::ROLE_MEMBER;
                $_SESSION['mid'] = $data['mid'];
                $_SESSION['nama'] = $data['nama'];
            break;

        }

        $this->userData = $_SESSION;
    }

    public function isLoggedIn(): bool
    {
        return !empty($_SESSION['login']);
    }

    public function hasRole(int $role): bool
    {
        return ($_SESSION['ots'] ?? 0) === $role;
    }

    public function getUserId()
    {
        return $_SESSION['aid'] ?? $_SESSION['uid'] ?? $_SESSION['mid'] ?? null;
    }

    public function getUserDataByKey($key)
    {
        return $_SESSION[$key] ?? null;
    }

    public function clearSession()
    {
        session_destroy();
    }
}
