<?php 
namespace App\Manager;

use App\core\Database;

class UserManager extends Database{

    public function createUser($username, $email, $password, $role){

        return $this->createQuery('
            INSERT INTO user (username, email, password, role_id)
            VALUES (:username, :email, :password, :role)',
                [
                    'username' => $username,
                    'email' => $email,
                    'password' => password_hash($password, PASSWORD_BCRYPT),
                    'role' => $role,
                ]
        );
    }

}