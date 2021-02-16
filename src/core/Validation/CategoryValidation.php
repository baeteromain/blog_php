<?php

namespace App\core\Validation;

class CategoryValidation
{
    private $errors = [];
    private $constraint;
    // private $get;

    public function __construct($get)
    {
        $this->constraint = new Constraint();
        // $this->get = $get;
    }

    public function check($get)
    {
        foreach ($get as $key => $value) {
            $this->checkField($key, $value);
        }

        return $this->errors;
    }

    private function checkField($name, $value)
    {
        if ('name' === $name) {
            $error = $this->checkname($name, $value);
            $this->addError($name, $error);
        } elseif ('slug' === $name) {
            $error = $this->checkSlug($name, $value);
            $this->addError($name, $error);
        } else {
            $this->addError('form_failed_register', 'Une erreur est survenue lors de votre inscription, merci de resaisir vos informations');
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

    private function checkname($name, $value)
    {
        if ($this->constraint->notBlank($name, $value)) {
            return $this->constraint->notBlank('nom', $value);
        }
        if ($this->constraint->minLength($name, $value, 2)) {
            return $this->constraint->minLength('nom', $value, 2);
        }
        if ($this->constraint->maxLength($name, $value, 255)) {
            return $this->constraint->maxLength('nom', $value, 30);
        }
        // if ($this->userManager->checkname($value)) {
        //     return "Ce nom d'utilisateur exite déja ";
        // }
    }

    private function checkSlug($name, $value)
    {
        if ($this->constraint->notBlank($name, $value)) {
            return $this->constraint->notBlank('slug', $value);
        }
        if ($this->constraint->minLength($name, $value, 2)) {
            return $this->constraint->minLength('slug', $value, 2);
        }
        if ($this->constraint->maxLength($name, $value, 255)) {
            return $this->constraint->maxLength('slug', $value, 255);
        }
        if ($this->constraint->validSlug($name, $value)) {
            return $this->constraint->validSlug('slug', $value);
        }
    }
}
