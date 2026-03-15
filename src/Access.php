<?php

namespace Indophp\Auo;

class Access
{
    protected $aut;

    public function __construct()
    {
        $this->aut=new Aut();
    }

    public function check($rule)
    {
        if(!$this->aut->isLoggedIn()){
            header("Location:/");
            exit;
        }

        $oto=new Oto();

        if(!$oto->can($rule)){
            die("403 Forbidden");
        }
    }
}
