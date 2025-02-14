<?php

class Session
{
    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function set($key, $value)
    {
        $_SESSION[$key] = $value;
    }

    public function get($key)
    {
        return $_SESSION[$key] ?? null;
    }

    public function exists($key)
    {
        return isset($_SESSION[$key]);
    }

    public function regenerate()
    {
        session_regenerate_id(true);
    }

    public function isLoggedIn()
    {
        return isset($_SESSION['user_id']);
    }

    public function init()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function logout()
    {
        $this->destroy();
    }

    public function destroy()
    {
        session_unset();
        session_destroy();
    }

    public function checkLogin()
    {
        if (!isset($_SESSION['username'])) {
            header('Location: login.php');
            exit();
        }
    }
}

?>
