<?php

namespace Naventum\Framework\Illuminate\Support\Facades;

use App\Models\User;

class Auth
{
    private static $user;

    private static $username;

    private static $password;

    private static $sessionName = '___user___';

    public static function attempt(array $credentials = [])
    {
        $user = User::where(static::$username, $credentials['username'])->first();

        if ($user) {
            if (Hash::verify($credentials['password'], $user->{static::$password})) {

                session()->set(static::$sessionName, [
                    'id' => $user->id,
                    'username' => $user->{static::$username},
                ]);

                return true;
            }
        }

        return false;
    }

    public static function logout()
    {
        unset($_SESSION[static::$sessionName]);
    }

    public static function user()
    {
        return static::$user;
    }

    public static function start()
    {
        return session_start();
    }

    protected static function setUserFromDatabase()
    {
        static::setConfig();

        if (session()->get(static::$sessionName)) {
            static::$user = User::where('id', session()->get(static::$sessionName)['id'])->where(static::$username, session()->get(static::$sessionName)['username'])->first();

            return static::$user;
        }

        static::logout();

        return false;
    }

    private static function setConfig()
    {
        $config = config('auth');

        static::$username = $config->username;
        static::$password = $config->password;
    }
}
