<?php

namespace Fuel;

class Auth 
{
    private $userin;

    public function __construct($userin)
    {
        $this->userin = $userin;
    }

    public function __invoke($request, $response, $next)
    {
        // проверим наличие данных в cookies
        if ($this->userin->CheckCookie($request)) {
            
            // обновим сессию и куки
            $this->userin->saveInSession();
            $response = $this->userin->saveInCookie($response);

        // проверим данные в сессии
        } elseif ($this->userin->CheckSession()) {
            
            // обновим только сессию
            $this->userin->saveInSession();
            
        } else {
            // данные не найдены
            
            return $response->withRedirect('/login');
        }

        // передаем дальше
        $response = $next($request, $response);

        return $response;
    }
}
