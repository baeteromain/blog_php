<?php 
namespace App\Manager;

use PDO;
use App\Model\User;
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

    public function login($username, $password)
    {
        $query = $this->createQuery('
        SELECT *
        FROM user WHERE username = :username',
            [
                'username' => $username,
            ]
            );
        $query->setFetchMode(PDO::FETCH_CLASS, User::class);    
        $user = $query->fetch();
        $isPasswordValid =  password_verify($password, $user->getPassword());
        if (!empty($user) && $isPasswordValid === true) {
            return true;
        }
        return 'Le couple " Nom d\'utilisateur " et " Mot de passe " ne correspondent pas. ';
    }

    public function checkUsername($username)
    {

        $result = $this->createQuery('
            SELECT COUNT(username) 
            FROM user WHERE username = :username',
                [
                    'username' => $username,
                ]
        );
        $isUnique = $result->fetchColumn();
        if($isUnique){
            return 'Le pseudo existe déjà';
        }
    }

    public function checkEmail($email)
    {
        $result = $this->createQuery('
            SELECT COUNT(username) 
            FROM user WHERE email = :email',
                [
                    'email' => $email,
                ]
        );
        $isUnique = $result->fetchColumn();
        if($isUnique){
            return 'Un compte avec cette adresse email existe déjà';
        }
    }

}