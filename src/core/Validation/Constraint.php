<?php

namespace App\core\Validation;

class Constraint
{
    public function notBlank($name, $value)
    {
        if (empty($value)) {
            return 'Le champ '.$name.' saisi est vide';
        }

        return null;
    }

    public function minLength($name, $value, $minSize)
    {
        if (strlen($value) < $minSize) {
            return 'Le champ '.$name.' doit contenir au moins '.$minSize.' caractères';
        }

        return null;
    }

    public function maxLength($name, $value, $maxSize)
    {
        if (strlen($value) > $maxSize) {
            return 'Le champ '.$name.' doit contenir au maximum '.$maxSize.' caractères';
        }

        return null;
    }

    public function isEmail($name, $value, $filter = FILTER_VALIDATE_EMAIL)
    {
        if (!filter_var($value, $filter)) {
            return 'Le champ '.$name.' n\'est pas une adresse email valide';
        }

        return null;
    }

    public function matchPassword($value, $toCheck)
    {
        if ($value !== $toCheck) {
            return 'Les mots de passe ne sont pas identiques';
        }

        return null;
    }

    public function validSlug($name, $value)
    {
        if (!preg_match('/^[a-z0-9]+(?:-[a-z0-9]+)*$/', $value)) {
            return 'Le champ '.$name.' n\'est pas un slug valide';
        }

        return null;
    }
}
