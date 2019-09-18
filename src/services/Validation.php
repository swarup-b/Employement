<?php
namespace Src\Services;

/**
 * Validation
 *
 *
 * and two method(validateEmail , validatePassword)
 */
class Validation
{
    /**
     * validateEmail
     *
     *
     * @param String $email represent the email
     *
     *
     * @return Boolean           return boolean value
     */
    public function validateEmail(string $email)
    {

        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return true;
        }
        return false;
    }
/**
 * validateEmail
 *
 *
 * @param String $password represent the password
 *
 *
 * @return Boolean           return boolean value
 */
    public function validatePassword(string $password)
    {

        if (preg_match('/^(?=.*\d)(?=.*[A-Za-z])(?=.*[!@#$%])[0-9A-Za-z!@#$%]{8,}$/', $password)) {
            return true;
        }
        return false;
    }

}
