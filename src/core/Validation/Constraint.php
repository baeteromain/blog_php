<?php 
namespace App\core\Validation;

class Constraint
{
    public function notBlank($name, $value)
    {
        if(empty($value)) {
            return 'Le champ '.$name.' saisi est vide';
        }
        return null;
    }
    public function minLength($name, $value, $minSize)
    {
        if(strlen($value) < $minSize) {
            return 'Le champ '.$name.' doit contenir au moins '.$minSize.' caractères';
        }
        return null;
    }
    public function maxLength($name, $value, $maxSize)
    {
        if(strlen($value) > $maxSize) {
            return 'Le champ '.$name.' doit contenir au maximum '.$maxSize.' caractères';
        }
        return null;
    }

    public function isEmail($name, $value, $filter = FILTER_VALIDATE_EMAIL)
    {
        if(!filter_var($value, $filter)){
            return 'Le champ ' .$name. ' n\'est pas une adresse email valide';
        }
        return null;
    }

    public function matchPassword($name, $value, $toCheck)
    {
        if($value !== $toCheck){
            return 'Le champ ' .$name. ' n\'est pas identique au champ password';
        }
        return null;
    }
}