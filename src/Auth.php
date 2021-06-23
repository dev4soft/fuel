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
        $CookieSet = true;
        $login = $this->container->cookie->getCookieValue($request, 'login');
        $token = $this->container->cookie->getCookieValue($request, 'token');

        if (!($login && $token)) {
            $CookieSet = false;
            // если нет в куках, посмотрим в сессии
            $login = $this->container->session->login;
            $token = $this->container->session->token;
        }

        if ($login && $token) {
            $validator = new \Fuel\Models\Login($this->container, $login);

            if ($validator->CheckToken($token)) {
                // данные в сессии обновляются всегда
                $this->container->session->login = $login;
                $this->container->session->token = $token;

                if ($CookieSet) {
                    // cookie обновляется только если они существовали
                    $response = $this->container->cookie->addCookie($response, 'login', $login, '10');
                    $response = $this->container->cookie->addCookie($response, 'token', $token, '10');
                }

                // передаем дальше
                $response = $next($request, $response);

                return $response;
            }

            $this->container->session->delete('login');
            $this->container->session->delete('token');

            // todo
            // удалить cookies
        }

        return $response->withRedirect('/login');
    }
}
