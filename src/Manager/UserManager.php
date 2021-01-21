<?php

namespace App\Manager;

use App\core\Database;
use App\Model\User;
use PDO;

class UserManager extends Database
{
    public function createUser($username, $email, $password, $role, $token)
    {
        return $this->createQuery(
            '
            INSERT INTO user (username, email, password, role_id, token)
            VALUES (:username, :email, :password, :role, :token)',
            [
                'username' => $username,
                'email' => $email,
                'password' => password_hash($password, PASSWORD_BCRYPT),
                'role' => $role,
                'token' => $token,
            ]
        );
    }

    public function login($username, $password)
    {
        $query = $this->createQuery(
            '
        SELECT *
        FROM user WHERE username = :username',
            [
                'username' => $username,
            ]
        );
        $query->setFetchMode(PDO::FETCH_CLASS, User::class);
        $user = $query->fetch();
        $isPasswordValid = password_verify($password, $user->getPassword());
        if (!empty($user) && (true === $isPasswordValid)) {
            return true;
        }

        return false;
    }

    public function checkUsername($username)
    {
        $result = $this->createQuery(
            '
            SELECT COUNT(username) 
            FROM user WHERE username = :username',
            [
                'username' => $username,
            ]
        );
        $isUnique = $result->fetchColumn();
        if ($isUnique) {
            return 'Le pseudo existe déjà';
        }
    }

    public function checkEmail($email)
    {
        $result = $this->createQuery(
            '
            SELECT COUNT(username) 
            FROM user WHERE email = :email',
            [
                'email' => $email,
            ]
        );
        $isUnique = $result->fetchColumn();
        if ($isUnique) {
            return 'Un compte avec cette adresse email existe déjà';
        }
    }
}
