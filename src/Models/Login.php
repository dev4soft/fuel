<?php

namespace Fuel\Models;

Class Login
{
    private $container;
    public $id_client;
    public $hash;

    public function __construct($c)
    {
        $this->container = $c;
    }

    public function CheckPass($login, $pass)
    {

        //$hash_pass = password_hash('!Q2w3e', PASSWORD_DEFAULT);
        //$query = 'update clients set hash = :hash where id_client = 1';
        //$this->container->db->updateData($query, ['hash' => $hash_pass]);

        $query = 'select id_client, hash from clients where login = :login';

        $idClientHash = $this
            ->container
            ->db
            ->getRow($query, ['login' => $login]);
        
        if (!$idClientHash) {
            return false;
        }
        error_log($idClientHash['hash']);

        if (!password_verify($pass, $idClientHash['hash'])) {
            return false;
        }

        error_log("pass test");

        $this->id_client = $idClientHash['id_client'];
        $this->hash = $idClientHash['hash'];

        return true;
    }

    public function CheckToken($id_client, $token)
    {
        //password_verify($id_client . $hash_pass, $token);

        $query = 'select login, hash from clients where id_client = :id_client';
        $userInfo = $this
            ->container
            ->db
            ->getRow($query, ['id_client' => $id_client]);

        if (!$userInfo) {
            return false;
        }

        if (!password_verify($id_client . $userInfo['hash'], $token)) {
            return false;
        }

        $this->id_client = $id_client;
        $this->hash = $userInfo['hash'];

        return true;
    }
}

