<?php

namespace Fuel;

Class UserIn
{
    private const LOGIN = 'login';
    private const TOKEN = 'token';
    private const LIFETIMECOOKIE = '10';

    private $login = '';
    private $hash = '';
    private $token = '';

    private $session;
    private $cookie;
    private $user;

    public function __construct($session, $cookie, $user)
    {
        $this->session = $session;
        $this->cookie = $cookie;
        $this->user = $user;
    }


    public function saveInSession()
    {
        $this->session->login = $this->login;
        $this->session->token = $this->token;
    }


    public function saveInCookie($response)
    {
        $response = $this->cookie->addCookie($response, self::LOGIN, $this->login, self::LIFETIMECOOKIE);
        $response = $this->cookie->addCookie($response, self::TOKEN, $this->token, self::LIFETIMECOOKIE);

        return $response;
    }


    public function MakeToken()
    {
        $this->token = password_hash($this->login . $this->hash, PASSWORD_BCRYPT);
    }


    private function CheckUser($login, $token)
    {
        $hash = $this->user->hashPass($login);

        if (!password_verify($login . $hash, $token)) {
            return false;
        }

        $this->login = $login;
        $this->hash = $hash;
        $this->token = $token;

        return true;
    }


    public function checkPass($login, $pass)
    {
        $hash = $this->user->hashPass($login);
        
        if (!password_verify($pass, $hash)) {
            return false;
        }

        $this->login = $login;
        $this->hash = $hash;

        $this->MakeToken();

        return true;
    }


    public function CheckCookie($request)
    {
        $login = $this->cookie->getCookieValue($request, self::LOGIN);
        $token = $this->cookie->getCookieValue($request, self::TOKEN);

        return $this->CheckUser($login, $token);
    }


    public function CheckSession()
    {
        $login = $this->session->login;
        $token = $this->session->token;

        return $this->CheckUser($login, $token);
    }


    public function LogOut($response)
    {
        $this->session->delete(self::LOGIN);
        $this->session->delete(self::TOKEN);

        $response = $this->cookie->deleteCookie($response, self::LOGIN);
        $response = $this->cookie->deleteCookie($response, self::TOKEN);

        return $response;
    }


    public function UpdatePass($login, $pass)
    {
        $hash = password_hash($pass, PASSWORD_BCRYPT);

        $this->user->hashUpdate($login, $hash);
    }
}

