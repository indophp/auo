<?php

namespace Indophp\Auo;

class Log
{
    protected $db;
    protected $aut;

    public function __construct()
    {
        $this->db = new Auobase();
        $this->aut = new Aut(); // pastikan class Aut sudah menyesuaikan UUID
    }

    public function login($user, $pass)
    {
        // cek admin
        $data = $this->db->aut_admin($user);
        $type = 'admin';

        // cek user
        if (!$data) {
            $data = $this->db->aut_user($user);
            $type = 'user';
        }

        // cek member
        if (!$data) {
            $data = $this->db->aut_member($user);
            $type = 'member';
        }

        // login berhasil
        if ($data && password_verify($pass, $data['sandi'])) {
            // set session
            $this->aut->setUserSession($data, $type);

            // ambil UUID user
            switch ($type) {
                case 'admin': $id = $data['aid']; break;
                case 'user':  $id = $data['uid']; break;
                case 'member':$id = $data['mid']; break;
            }

            // update last login
            $this->db->updateLastLogin($id, $type);

            // log login sukses
            $this->db->logLoginAttempt($user, true, $type, $id);

            return true;
        }

        // login gagal
        $this->db->logLoginAttempt($user, false);
        return false;
    }

    public function logout()
    {
        $this->aut->clearSession();
    }
}
