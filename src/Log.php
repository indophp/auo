<?php

namespace Indophp\Auo;

class Log
{
    protected $db;
    protected $aut;

    public function __construct()
    {
        $this->db=new Auobase();
        $this->aut=new Aut();
    }

    public function login($user,$pass)
    {
        $data=$this->db->aut_admin($user);
        $type='admin';

        if(!$data){
            $data=$this->db->aut_user($user);
            $type='user';
        }

        if(!$data){
            $data=$this->db->aut_member($user);
            $type='member';
        }

        if($data && password_verify($pass,$data['sandi'])){
            $this->aut->setUserSession($data,$type);

            $id=$this->aut->getUserId();

            $this->db->updateLastLogin($id,$type);
            $this->db->logLoginAttempt($user,true,$type,$id);

            return true;
        }

        $this->db->logLoginAttempt($user,false);
        return false;
    }

    public function logout()
    {
        $this->aut->clearSession();
    }
}
