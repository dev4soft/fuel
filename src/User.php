<?php

namespace Fuel;

Class User
{
    private $db;


    public function __construct($db)
    {
        $this->db = $db;
    }


    public function hashPass($login)
    {
        $query = 'select hash from clients where login = :login';

        return $this
            ->db
            ->getValue($query, ['login' => $login]);
    }


    public function hashUpdate($login, $hash)
    {
        $query = 'update clients set hash = :hash where login = :login';

        return $this
            ->db
            ->updateData($query, ['login' => $login, 'hash' => $hash]);
    }
}
