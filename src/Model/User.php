<?php

namespace App\Model;

class User
{
    public function login($login)
    {
        $_SESSION['admin']['login'] = $login;
    }

    public function logout()
    {
        session_unset();
    }

    public function isGuest()
    {
        if (isset($_SESSION['admin']['login'])) {
            return false;
        }
        return true;
    }

    public function getLogin()
    {
        if (isset($_SESSION['admin']['login'])) {
            return $_SESSION['admin']['login'];
        }
        return null;
    }
}
