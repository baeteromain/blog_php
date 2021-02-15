<?php

namespace App\Manager;

use App\core\Database;
use App\Model\User;
use PDO;

class UserManager extends Database
{
    public function getUserById($id)
    {
        $query = $this->createQuery(
            '
            SELECT * FROM user
            WHERE id = :id
            ',
            [
                'id' => $id,
            ]
        );

        $query->setFetchMode(PDO::FETCH_CLASS, User::class);
        $user = $query->fetch();
        if ($user) {
            return $user;
        }

        return false;
    }

    public function getUsers()
    {
        $query = $this->createQuery(
            '
        SELECT user.id, user.username, user.email, role.name as role 
        FROM user 
        INNER JOIN role ON user.role_id = role.id 
        ORDER BY user.id DESC'
        );
        $query->setFetchMode(PDO::FETCH_CLASS, User::class);

        return $query->fetchAll();
    }

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

    public function upgradeRole($id, $role)
    {
        return $this->createQuery(
            '
         UPDATE user SET role_id = :role_id 
        WHERE id = :id',
            [
                'id' => $id,
                'role_id' => $role,
            ]
        );
    }

    public function updateRole($id, $role)
    {
        return $this->createQuery(
            '
         UPDATE user SET role_id = :role_id 
        WHERE id = :id',
            [
                'id' => $id,
                'role_id' => $role,
            ]
        );
    }

    public function deleteUser($id)
    {
        return $this->createQuery(
            '
        DELETE FROM user 
        WHERE id = :id',
            [
                'id' => $id,
            ]
        );
    }

    public function updateUser($id, $username, $email, $token = null, $valid = '1')
    {
        return $this->createQuery(
            '
        UPDATE user SET username = :username, email = :email, token = :token, valid = :valid 
        WHERE id = :id',
            [
                'id' => $id,
                'username' => $username,
                'email' => $email,
                'token' => $token,
                'valid' => $valid,
            ]
        );
    }

    // public function updateUsername($id, $username)
    // {
    //     return $this->createQuery(
    //         '
    //     UPDATE user SET username = :username
    //     WHERE id = :id',
    //         [
    //             'id' => $id,
    //             'username' => $username,
    //         ]
    //     );
    // }

    // public function updateEmail($id, $email, $token)
    // {
    //     return $this->createQuery(
    //         '
    //     UPDATE user SET email = :email, token = :token, valid = 0
    //     WHERE id = :id',
    //         [
    //             'id' => $id,
    //             'email' => $email,
    //             'token' => $token,
    //         ]
    //     );
    // }

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

    public function updatePassword($password, $email, $valid = '1')
    {
        return $this->createQuery(
            '
            UPDATE user SET password = :password, valid = :valid 
            WHERE email = :email',
            [
                'password' => password_hash($password, PASSWORD_BCRYPT),
                'email' => $email,
                'valid' => $valid,
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
            return true;
        }

        return false;
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
            return true;
        }

        return false;
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
            return true;
        }

        return false;
    }
}
