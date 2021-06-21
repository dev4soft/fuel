<?php

namespace Fuel;

class Cookie
{
    public function deleteCookie($response, $key)
    {
        $cookie =
            urlencode($key) . 
            '=' . 
            urlencode('deleted') . 
            '; expires=Thu, 01-Jan-1970 00:00:01 GMT; Max-Age=0; path=/; secure; httponly';
        $response = $response->withAddedHeader('Set-Cookie', $cookie);

        return $response;
    }
    
    public function addCookie($response, $cookieName, $cookieValue, $expiration)
    {
        $expiry = new \DateTimeImmutable('now + ' . $expiration . ' minutes');
        $cookie =
            urlencode($cookieName) . 
            '=' . 
            urlencode($cookieValue) . 
            '; expires=' . 
            $expiry->format(\DateTime::COOKIE) . 
            '; Max-Age=' .
            $expiration * 60 .
            '; path=/; secure; httponly';
        $response = $response->withAddedHeader('Set-Cookie', $cookie);

        return $response;
    }

    public function getCookieValue($request, $cookieName)
    {
        $cookies = $request->getCookieParams();
        return isset($cookies[$cookieName]) ? $cookies[$cookieName] : null;
    }
}
