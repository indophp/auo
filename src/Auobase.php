<?php

namespace Indophp\Auo;

class Auobase
{
    protected $pdo;

    public function __construct()
    {
        $this->pdo = new \PDO(
            "mysql:host=localhost;dbname=db;charset=utf8mb4",
            "user",
            "pass",
            [\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION]
        );
    }

    public function aut_admin($user)
    {
        $sql = "SELECT * FROM tb_admin 
                WHERE user=:u OR email=:u OR nik=:u LIMIT 1";

        $q = $this->pdo->prepare($sql);
        $q->execute(['u'=>$user]);
        return $q->fetch(\PDO::FETCH_ASSOC);
    }

    public function aut_user($user)
    {
        $sql = "SELECT * FROM tb_user 
                WHERE uid=:u OR email=:u LIMIT 1";

        $q = $this->pdo->prepare($sql);
        $q->execute(['u'=>$user]);
        return $q->fetch(\PDO::FETCH_ASSOC);
    }

    public function aut_member($user)
    {
        $sql = "SELECT * FROM tb_member 
                WHERE mid=:u OR email=:u LIMIT 1";

        $q = $this->pdo->prepare($sql);
        $q->execute(['u'=>$user]);
        return $q->fetch(\PDO::FETCH_ASSOC);
    }

    public function updateLastLogin($id,$type)
    {
        $map = [
            'admin'=>['tb_admin','aid'],
            'user'=>['tb_user','uid'],
            'member'=>['tb_member','mid']
        ];

        if(!isset($map[$type])) return;

        [$table,$field] = $map[$type];

        $sql="UPDATE $table SET last_login=NOW() WHERE $field=:id";

        $q=$this->pdo->prepare($sql);
        $q->execute(['id'=>$id]);
    }

    public function logLoginAttempt($user,$success,$type=null,$id=null)
    {
        $sql="INSERT INTO log_auo 
        (username,success,user_type,user_id,created_at)
        VALUES(:u,:s,:t,:i,NOW())";

        $q=$this->pdo->prepare($sql);
        $q->execute([
            'u'=>$user,
            's'=>$success?1:0,
            't'=>$type,
            'i'=>$id
        ]);
    }
}
