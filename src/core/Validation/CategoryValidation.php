<?php

namespace App\core\Validation;

use App\Manager\CategoryManager;

class CategoryValidation
{
    private $errors = [];

    /**
     * @var Constraint
     */
    private $constraint;
    /**
     * @var CategoryManager
     */
    private $categoryManager;

    public function __construct()
    {
        $this->constraint = new Constraint();
        $this->categoryManager = new CategoryManager();
    }

    public function check($get): array
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

            return $this->addError($name, $error);
        }
        if ('slug' === $name) {
            $error = $this->checkSlug($name, $value);

            return $this->addError($name, $error);
        }
        if ('id' === $name) {
            return null;
        }

        return $this->addError('form_failed_update_category', 'Une erreur est survenue, merci de resaisir vos informations');
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
        if ($this->categoryManager->checkNameUnique($value)) {
            return 'Ce nom de catégorie exite déja ';
        }
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
        if ($this->categoryManager->checkSlugUnique($value)) {
            return 'Ce slug exite déja ';
        }
    }
}
