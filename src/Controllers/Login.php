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

        if ($login == $pass) {
            
            $id_user = 1;
            $this->container->session->auth = $id_user;

            $remember = isset($data['remember']) ? $data['remember'] : false;
            
            if ($remember) {
                $ip = $request->getServerParam('REMOTE_ADDR');
                $hash_pass = password_hash("123456", PASSWORD_DEFAULT);
                $token = password_hash($ip . $hash_pass, PASSWORD_DEFAULT);

                $res = $this->container->cookie->addCookie($res, 'id', $id_user, '10');
                $res = $this->container->cookie->addCookie($res, 'token', $token, '10');

                // заглушка
                $res = $this->container->cookie->addCookie($res, 'hash_pass', $hash_pass, '10');
            }

            return $response->withRedirect('/');
        }

        return $response->withRedirect('/login');
    }

    public function LogOut($request, $response)
    {
        $this->container->session->delete('auth');

        $response = $this->container->cookie->deleteCookie($response, 'id');
        $response = $this->container->cookie->deleteCookie($response, 'token');

        return $response->withRedirect('/login');
    }
}
