<?php

namespace src;

class session
{
    public function killSession()
    {
        $_SESSION = []; //Overwrites current session with an empty array (clears it)

        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(), //session cookie name
                '', //empty value
                time() - 42000,
                $params["path"],
                $params["domain"],
                $params["secure"],
                $params["httponly"]
            );
        }
        session_destroy(); //Destroys the session
    }
}