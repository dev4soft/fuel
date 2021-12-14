<?php

namespace Fuel\Controllers;

Class LoginForm
{
    private $userin;
    private $view;

    public function __construct($c)
    {
        $this->userin = $c['userin'];
        $this->view = $c['view'];
    }

    public function LoginForm($request, $response)
    {
        return $this
                ->view
                ->render($response, 'login.php');
    }

    public function LoginCheck($request, $response)
    {
        $data = $request->getParsedBody();
        $login = $data['login'];
        $pass = $data['pass'];

        //$this->userin->updatePass($login, $pass);

        if (!$this->userin->checkPass($login, $pass)) {

            return $response->withRedirect('/login');
        }

        if (isset($data['remember'])) {

            $this->userin->saveInCookie($response);
        }

        $this->userin->saveInSession();

        return $response->withRedirect('/');
    }

    public function LogOut($request, $response)
    {
        $response = ($this->userin)->LogOut($response);

        return $response->withRedirect('/login');
    }
}
