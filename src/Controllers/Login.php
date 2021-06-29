<?php

namespace Fuel\Controllers;

Class Login
{
    private $container;

    public function __construct($c)
    {
        $this->container = $c;
    }

    public function LoginForm($request, $response)
    {
        return $this
                ->container
                ->view
                ->render($response, 'login.php');
    }

    public function LoginCheck($request, $response)
    {
        $data = $request->getParsedBody();
        $login = $data['login'];
        $pass = $data['pass'];

        $validator = new \Fuel\Models\Login($this->container, $login);
        //$validator->updatePass($pass);
        
        if ($validator->CheckPass($pass)) {
            $token = password_hash($login . $validator->hash, PASSWORD_BCRYPT);
            $this->container->session->login = $login;
            $this->container->session->token = $token;

            $remember = isset($data['remember']) ? $data['remember'] : false;
            
            if ($remember) {
                $response = $this->container->cookie->addCookie($response, 'login', $login, '10');
                $response = $this->container->cookie->addCookie($response, 'token', $token, '10');
            }

            return $response->withRedirect('/');
        }

        return $response->withRedirect('/login');
    }

    public function LogOut($request, $response)
    {
        $this->container->session->delete('login');
        $this->container->session->delete('token');

        $response = $this->container->cookie->deleteCookie($response, 'login');
        $response = $this->container->cookie->deleteCookie($response, 'token');

        return $response->withRedirect('/login');
    }
}
