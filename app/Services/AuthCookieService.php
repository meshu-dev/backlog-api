<?php

namespace App\Services;

use Illuminate\Http\Request;

class AuthCookieService
{
    protected $cookieName;

    public function __construct()
    {
        $this->cookieName = env('AUTH_COOKIE_NAME');
    }

    public function create($token)
    {
        $cookieExpiry = env('AUTH_COOKIE_EXPIRY');
        $cookieDomain = env('AUTH_COOKIE_DOMAIN');

        $cookie = cookie(
            $this->cookieName,
            $token,
            $cookieExpiry,
            '/',
            $cookieDomain,
            false,
            true,
            false
        );

        return $cookie;
    }

    public function getToken(Request $request)
    {
        if ($request->hasCookie($this->cookieName) === true) {
            $token = $request->cookie($this->cookieName);
            return $token;
        }
        return null;
    }
}
