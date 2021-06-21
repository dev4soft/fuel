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
        $id_user = $this->container->cookie->getCookieValue($request, 'id');
        $token = $this->container->cookie->getCookieValue($request, 'token');

        if ($id_user && $token) {
            // проверим токен
            // узнаем по id пользователя хэш пароля
            $ip = $request->getServerParam('REMOTE_ADDR');

            // заглушка
            $hash_pass = $this->container->cookie->getCookieValue($request, 'hash_pass');

            if (password_verify($ip . $hash_pass, $token)) {
                $response = $this->container->cookie->addCookie($response, 'id', $id_user, '10');
                $response = $this->container->cookie->addCookie($response, 'token', $token, '10');
                $this->container->session->auth = $id_user;
            } else {
                $this->container->session->delete('auth');
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
        return (int)$this->container->session->auth === 1;
    }

    /*
     * id_user
     * hash_pass хэш пароля пользователя (из базы)
     */
    public function login($id_user, $hash_pass)
    {
    }

}
