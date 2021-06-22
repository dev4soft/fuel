<?php

namespace Fuel;

class Auth 
{
    private $container;

    public function __construct($c)
    {
        $this->container = $c;
    }

    public function __invoke($request, $response, $next)
    {
        // проверяем наличие сохраненной cookies
        $id_client = $this->container->cookie->getCookieValue($request, 'id');
        $token = $this->container->cookie->getCookieValue($request, 'token');

        if ($id_client && $token) {
            $login = new \Fuel\Models\Login($this->container);

            if ($login->CheckToken($id_client, $token)) {
                $response = $this->container->cookie->addCookie($response, 'id', $id_client, '10');
                $response = $this->container->cookie->addCookie($response, 'token', $token, '10');
                $this->container->session->id_client = $id_client;
            } else {
                $this->container->session->delete('id_client');
                // todo
                // удалить cookies
            }
        }

        if ($this->isAuthorized()) {
            $response = $next($request, $response);

            return $response;
        }

        return $response->withRedirect('/login');
    }

    public function isAuthorized()
    {
        return (int)$this->container->session->id_client === 1;
    }
}
