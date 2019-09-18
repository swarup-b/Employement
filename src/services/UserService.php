<?php
/**
 * User Service
 *
 * User profile view and update
 * Created date : 17/08/2019
 *
 * PHP version 7
 *
 * @author  Original Author <swarupb@mindfiresolutions.com>
 * @version <GIT: https://github.com/swarup-b/Employement>
 */
namespace Src\Services;

use Src\Model\FmModel;
use \Firebase\JWT\JWT;

/**
 * User Service
 *
 *
 * and two method(createNewUser , loginUser)
 */
class UserService
{

    /**
     * createNewUSer
     *
     *
     * @param String $layoutName represent the current layout name
     *
     * @param object $values represent the user credentials
     *
     * @param object $fmdb represents db instance
     *
     * @return Array           return Array
     */
    public function createNewUser($layout_name, $values, $fmdb)
    {
        /**
         * Used to contain FmModel instance
         *
         * @var Object
         */
        $user = new FmModel();
        /**
         * Used to contain fieldsName
         *
         * @var Array
         */
        $fieldsName = array("email" => $values->email);
        /**
         * Used to contain result instance
         *
         * @var Object
         */
        $result = $user->findFmRecord($layout_name, $fieldsName, $fmdb);
        if (is_string($result)) {
            $create = $user->create($layout_name, $values, $fmdb);
            return $create;
        }
        return ["data" => "Email Already Exist"];

    }

/**
 * login user
 *
 *
 * @param String $layout_name represent the current layout name
 *
 *
 * @param object $data represent the login credentials
 *
 * @param object $fmdb represents db instance
 *
 * @param Object $settings represent the setting object
 * @return Array           return Array
 */
    public function loginUser($layout_name, $data, $fmdb, $settings)
    {
        /**
         * Used to contain FmModel instance
         *
         * @var Object
         */
        $user = new FmModel();
        /**
         * Used to contain requestValue
         *
         * @var Array
         */
        $requestValue = array(
            'email' => $data->email,
            'password' => $data->password,
        );
        /**
         * Used to contain fieldsName
         *
         * @var Array
         */
        $fieldsName = ["email" => $data->email, "password" => $data->password];
        /**
         * Used to contain result instance
         *
         * @var Object
         */
        $result = $user->findFmRecord($layout_name, $requestValue, $fmdb);
        if (is_string($result)) {
            return ["error" => "Invalid email or password"];
        }
        /**
         * Used to contain recordId
         *
         * @var Integer
         */
        $recordId = array_column($result, 'recordId');
        /**
         * Used to contain token
         *
         * @var String
         */
        $token = JWT::encode(['id' => $recordId[0]], $settings['jwt']['secret'], "HS256");
        return array("token" => $token);
    }
}
