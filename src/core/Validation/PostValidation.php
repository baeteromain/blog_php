<?php

namespace App\core\Validation;

use App\Manager\PostManager;

class PostValidation
{
    private $errors = [];

    /**
     * @var Constraint
     */
    private $constraint;
    /**
     * @var PostManager
     */
    private $postManager;


    public function __construct()
    {
        $this->constraint = new Constraint();
        $this->postManager = new PostManager();
    }

    public function check($post): array
    {
        foreach ($post as $key => $value) {
            $this->checkField($key, $value);
        }

        return $this->errors;
    }

    private function checkField($name, $value)
    {
        if ('title' === $name) {
            $error = $this->checkTitle($name, $value);
            $this->addError($name, $error);
        } elseif ('chapo' === $name) {
            $error = $this->checkChapo($name, $value);
            $this->addError($name, $error);
        } elseif ('content' === $name) {
            $error = $this->checkContent($name, $value);
            $this->addError($name, $error);
        } elseif ('slug' === $name) {
            $error = $this->checkSlug($name, $value);
            $this->addError($name, $error);
        } elseif ('category' === $name || 'id' === $name) {
            return null;
        } else {
            $this->addError('form_failed_post', 'Une erreur est survenue, merci de resaisir vos informations');
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

    private function checkTitle($name, $value)
    {
        if ($this->constraint->notBlank($name, $value)) {
            return $this->constraint->notBlank('titre', $value);
        }
        if ($this->constraint->minLength($name, $value, 2)) {
            return $this->constraint->minLength('titre', $value, 2);
        }
        if ($this->constraint->maxLength($name, $value, 255)) {
            return $this->constraint->maxLength('titre', $value, 255);
        }
        if ($this->postManager->checkTitleUnique($value)) {
            return "Ce titre d'article exite déja";
        }
    }

    private function checkChapo($name, $value)
    {
        if ($this->constraint->notBlank($name, $value)) {
            return $this->constraint->notBlank('chapo', $value);
        }
        if ($this->constraint->minLength($name, $value, 2)) {
            return $this->constraint->minLength('chapo', $value, 2);
        }
    }

    private function checkContent($name, $value)
    {
        if ($this->constraint->notBlank($name, $value)) {
            return $this->constraint->notBlank('contenu', $value);
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
        if ($this->postManager->checkSlugUnique($value)) {
            return 'Ce slug exite déja';
        }
    }
}
