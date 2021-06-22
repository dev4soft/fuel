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

        $validator = new \Fuel\Models\Login($this->container);
        if ($validator->CheckPass($login, $pass)) {
            $id_client = $validator->id_client;
            $this->container->session->id_client = $id_client;

            $remember = isset($data['remember']) ? $data['remember'] : false;
            
            if ($remember) {
                $token = password_hash($id_client . $validator->hash, PASSWORD_BCRYPT);

                $response = $this->container->cookie->addCookie($response, 'id', $id_client, '10');
                $response = $this->container->cookie->addCookie($response, 'token', $token, '10');
            }

            return $response->withRedirect('/');
        }

        return $response->withRedirect('/login');
    }

    public function LogOut($request, $response)
    {
        $this->container->session->delete('id_client');

        $response = $this->container->cookie->deleteCookie($response, 'id');
        $response = $this->container->cookie->deleteCookie($response, 'token');

        return $response->withRedirect('/login');
    }
}
