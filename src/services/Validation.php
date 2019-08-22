<?php
namespace Src\Services;

class Validation{

	 public function validateEmail(string $email){

        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return true;
        }
        return false;
    }

        public function validatePassword(string $password) {

        if (preg_match('/^(?=.*\d)(?=.*[A-Za-z])(?=.*[!@#$%])[0-9A-Za-z!@#$%]{8,}$/', $password)) {
            return true;
        }
        return false;
    }

    
}



  ?>