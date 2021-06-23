<?php

namespace Fuel\Models;

Class Login
{
    private $container;
    public $id_client;
    public $hash;
    public $login;

    public function __construct($c, $login)
    {
        $this->container = $c;

        $query = 'select id_client, hash from clients where login = :login';

        $userInfo = $this
            ->container
            ->db
            ->getRow($query, ['login' => $login]);

        if ($userInfo) {
            $this->id_client = $userInfo['id_client'];
            $this->hash = $userInfo['hash'];
            $this->login = $login;
        } else {
            $this->id_client = '';
            $this->hash = '';
            $this->login = '';
        }
    }

    public function CheckPass($pass)
    {
        return password_verify($pass, $this->hash);
    }

    public function CheckToken($token)
    {
        return password_verify($this->login . $this->hash, $token);
    }

    public function UpdatePass($pass)
    {
        $hash = password_hash($pass, PASSWORD_BCRYPT);
        $query = 'update clients set hash = :hash where login = :login';

        $result = $this
            ->container
            ->db
            ->updateData($query, ['login' => $this->login, 'hash' => $hash]);

        if ($result) {
            $this->hash = $hash;
        }
    }
}

