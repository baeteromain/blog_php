<?php

namespace App\core\Validation;

use App\Manager\UserManager;

class UserValidation
{
    private $errors = [];
    private $constraint;
    private $password;
    private $userManager;

    public function __construct()
    {
        $this->constraint = new Constraint();
        $this->userManager = new UserManager();
    }

    public function check($post)
    {
        foreach ($post as $key => $value) {
            $this->checkField($key, $value);
        }

        return $this->errors;
    }

    private function checkField($name, $value)
    {
        if ('username' === $name) {
            $error = $this->checkUsername($name, $value);
            $this->addError($name, $error);
        } elseif ('password' === $name) {
            $error = $this->checkPassword($name, $value);
            $this->addError($name, $error);
            $this->password = $value;
        } elseif ('confirm_password' === $name) {
            $error = $this->checkPasswordConfirm($name, $value, $this->password);
            $this->addError($name, $error);
        } elseif ('email' === $name) {
            $error = $this->checkEmail($name, $value);
            $this->addError($name, $error);
        }
    }

    private function addError($name, $error)
    {
        if ($error) {
            $this->errors += [
                $name => $error,
            ];
        }
    }

    private function checkUsername($name, $value)
    {
        if ($this->constraint->notBlank($name, $value)) {
            return $this->constraint->notBlank('username', $value);
        }
        if ($this->constraint->minLength($name, $value, 2)) {
            return $this->constraint->minLength('username', $value, 2);
        }
        if ($this->constraint->maxLength($name, $value, 255)) {
            return $this->constraint->maxLength('username', $value, 255);
        }
        if (!$this->userManager->checkUsername($value)) {
            return "Ce nom d'utilisateur exite déja ";
        }
    }

    private function checkPassword($name, $value)
    {
        if ($this->constraint->notBlank($name, $value)) {
            return $this->constraint->notBlank('password', $value);
        }
        if ($this->constraint->minLength($name, $value, 2)) {
            return $this->constraint->minLength('password', $value, 2);
        }
        if ($this->constraint->maxLength($name, $value, 255)) {
            return $this->constraint->maxLength('password', $value, 255);
        }
    }

    private function checkPasswordConfirm($name, $value, $password)
    {
        if ($this->constraint->notBlank($name, $value)) {
            return $this->constraint->notBlank('confirmez mot de passe', $value);
        }

        if ($this->constraint->matchPassword($value, $password)) {
            return $this->constraint->matchPassword($value, $password);
        }
    }

    private function checkEmail($name, $value)
    {
        if ($this->constraint->notBlank($name, $value)) {
            return $this->constraint->notBlank('email', $value);
        }
        if ($this->constraint->isEmail($name, $value)) {
            return $this->constraint->isEmail('email', $value);
        }
        if (!$this->userManager->checkEmail($value)) {
            return 'Cette addresse mail existe déja';
        }
    }
}