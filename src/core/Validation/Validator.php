<?php 
namespace App\core\Validation;

use Exception;
use App\core\Validation\Constraint;

class Validator extends Constraint{

    private $data;
    private $errors = [];
   
    public function __construct($data)
    {
        $this->data = $data;
    }

    public function validate($name)
    {
        if($name === 'register'){
            $this->validateUsername('username');
            $this->validateEmail('email');
            $this->validatePassword('password');
            $this->validateConfirmPassword('confirm_password');
            return $this->errors;
        } 
        if($name === 'login'){
            $this->validateUsername('username');
            $this->validatePassword('password');
            return $this->errors;
        } 
        
        throw new Exception('Impossible de faire la vérification');
         
    }

    private function validateUsername($post_key)
    {
        $val = trim($this->data[$post_key]);

        $errorBlank = $this->notBlank('" Nom d\'utilisateur "', $val);
        $errorMinLength = $this->minLength('" Nom d\'utilisateur "', $val, 3);
        $errorMaxLength = $this->maxLength('" Nom d\'utilisateur "', $val, 45);

        if($errorBlank){
            $this->addError($post_key, $errorBlank);
        }elseif($errorMinLength) {
            $this->addError($post_key, $errorMinLength);
        }elseif($errorMaxLength){
            $this->addError($post_key, $errorMaxLength);
        }
    }

    private function validateEmail($post_key)
    {
        $val = trim($this->data[$post_key]);

        $errorBlank = $this->notBlank('" Email "', $val);
        $errorIsEmail = $this->isEmail('" Email "', $val);

        if($errorBlank){
            $this->addError($post_key, $errorBlank);
        }elseif($errorIsEmail) {
            $this->addError($post_key, $errorIsEmail);
        }
    }

    private function validatePassword($post_key)
    {
        $val = $this->data[$post_key];

        $errorBlank = $this->notBlank('" Mot de passe "', $val);
        $errorMinLength = $this->minLength('" Mot de passe "', $val, 4);
        $errorMaxLength = $this->maxLength('" Mot de passe "', $val, 60);

        if($errorBlank){
            $this->addError($post_key, $errorBlank);
        }elseif($errorMinLength) {
            $this->addError($post_key, $errorMinLength);
        }elseif($errorMaxLength){
            $this->addError($post_key, $errorMaxLength);
        }

    }

    private function validateConfirmPassword($post_key)
    {
        $val = $this->data[$post_key];

        $errorBlank = $this->notBlank('" Confirmez le mot de passe "', $val);
        $errorMatch = $this->matchPassword('" Confirmez le mot de passe "', $val, $this->data['password']);

        if($errorBlank){
            $this->addError($post_key, $errorBlank);
        }elseif($errorMatch) {
            $this->addError($post_key, $errorMatch);
        }
    }

    private function addError($key, $val)
    {
        $this->errors[$key] = $val;
    }

}