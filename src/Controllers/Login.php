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

        $validator = new \Fuel\Login($this->container);

        $validator->getUserInfo($login);

        //$validator->updatePass($pass);
        
        if ($validator->CheckPass($pass)) {
            $validator->MakeToken();
            $validator->setSession();

            $remember = isset($data['remember']) ? $data['remember'] : false;
            
            if ($remember) {
                $response = $validator->setCookies($response);
            }

            return $response->withRedirect('/');
        }

        return $response->withRedirect('/login');
    }

    public function LogOut($request, $response)
    {
        $response = (new \Fuel\Login($this->container))->LogOut($response);

        return $response->withRedirect('/login');
    }
}
