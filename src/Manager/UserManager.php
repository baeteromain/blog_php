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

    public function removeToken($email)
    {
        return $this->createQuery(
            '
        UPDATE user SET token = null
        WHERE email = :email',
            [
                'email' => $email,
            ]
        );
    }

    public function tokenForgotpwd($email, $token)
    {
        return $this->createQuery(
            '
        UPDATE user SET token = :token
        WHERE email = :email',
            [
                'email' => $email,
                'token' => $token,
            ]
        );
    }

    public function updatePassword($password, $email)
    {
        return $this->createQuery(
            '
            UPDATE user SET password = :password, valid = 1 
            WHERE email = :email',
            [
                'password' => password_hash($password, PASSWORD_BCRYPT),
                'email' => $email,
            ]
        );
    }

    public function emailConfirmation($email, $token)
    {
        $result = $this->createQuery(
            '
        SELECT COUNT(token)
        FROM user WHERE email = :email AND token = :token',
            [
                'email' => $email,
                'token' => $token,
            ]
        );

        $tokenMatch = $result->fetchColumn();
        if ($tokenMatch) {
            return false;
        }

        return true;
    }

    public function validEmail($email, $token)
    {
        return $this->createQuery(
            '
        UPDATE user SET valid = 1
        WHERE email = :email AND token = :token',
            [
                'email' => $email,
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
        if ($user) {
            $isPasswordValid = password_verify($password, $user->getPassword());
            if ($isPasswordValid) {
                return $user;
            }

            return false;
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
            return false;
        }

        return true;
    }

    public function checkEmail($email)
    {
        $result = $this->createQuery(
            '
            SELECT COUNT(email) 
            FROM user WHERE email = :email',
            [
                'email' => $email,
            ]
        );
        $isUnique = $result->fetchColumn();
        if ($isUnique) {
            return false;
        }

        return true;
    }
}
