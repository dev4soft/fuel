<?php

namespace Fuel;

Class Login
{
    private $container;
    private $id_client = 0;
    private $hash = '';
    private $login = '';
    private $token = '';

    public function __construct($c)
    {
        $this->container = $c;
    }

    public function setSession()
    {
        $this->container->session->login = $this->login;
        $this->container->session->token = $this->token;
    }

    public function setCookies($response)
    {
        $response = $this->container->cookie->addCookie($response, 'login', $this->login, '10');
        $response = $this->container->cookie->addCookie($response, 'token', $this->token, '10');

        return $response;
    }

    public function MakeToken()
    {
        $this->token = password_hash($this->login . $this->hash, PASSWORD_BCRYPT);
    }

    public function getUserInfo($login)
    {
        $query = 'select id_client, hash from clients where login = :login';

        $userInfo = $this
            ->container
            ->db
            ->getRow($query, ['login' => $login]);

        if ($userInfo) {
            $this->id_client = $userInfo['id_client'];
            $this->hash = $userInfo['hash'];
            $this->login = $login;

            return true;
        }

        $this->id_client = '';
        $this->hash = '';
        $this->login = '';
        $this->token = '';

        return false;
    }

    private function CheckUser($login, $token)
    {
        if (!$this->getUserInfo($login)) {

            return false;
        }
            
        if (!$this->CheckToken($token)) {

            return false;
        }

        $this->token = $token;
        
        return true;
    }

    public function CheckCookie($request)
    {
        $login = $this->container->cookie->getCookieValue($request, 'login');
        $token = $this->container->cookie->getCookieValue($request, 'token');

        return $this->CheckUser($login, $token);
    }

    public function CheckSession()
    {
        $login = $this->container->session->login;
        $token = $this->container->session->token;

        return $this->CheckUser($login, $token);
    }

    public function CheckPass($pass)
    {
        return password_verify($pass, $this->hash);
    }

    public function CheckToken($token)
    {
        return password_verify($this->login . $this->hash, $token);
    }

    public function LogOut($response)
    {
        $this->container->session->delete('login');
        $this->container->session->delete('token');

        $response = $this->container->cookie->deleteCookie($response, 'login');
        $response = $this->container->cookie->deleteCookie($response, 'token');

        return $response;
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

