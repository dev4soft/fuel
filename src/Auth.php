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
        $validator = new \Fuel\Login($this->container);

        // проверим наличие данных в cookies
        if ($validator->CheckCookie($request)) {
            
            // обновим сессию и куки
            $validator->setSession();
            $response = $validator->setCookies($response);

        // проверим данные в сессии
        } elseif ($validator->CheckSession()) {
            
            // обновим только сессию
            $validator->setSession();
            
        } else {
            // данные не найдены
            
            return $response->withRedirect('/login');
        }

        // передаем дальше
        $response = $next($request, $response);

        return $response;
    }
}
