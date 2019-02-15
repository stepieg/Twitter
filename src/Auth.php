<?php
class Auth
{
    public static function user($conn, $userEmail, $userPassword)
    {
        $user = User::loadUserByEmail($conn, $userEmail);
        if (null === $user) {
            return NULl;
        }
        var_dump($user);
        if (password_verify($userPassword, $user->getHashPass())) {
            var_dump('ok');
            return $user;
        }
        return NULL;
    }
}