<?php
/**
 * User Controller
 *
 * User Create and login
 * Created date : 17/08/2019
 *
 * PHP version 7
 *
 * @author  Original Author <swarupb@mindfiresolutions.com>
 * @version <GIT: https://github.com/swarup-b/Employement>
 */
namespace Src\Controller;

use Interop\Container\ContainerInterface;
use Src\Services\UserService;
use Src\Services\Validation;

require_once __DIR__ . '/../constants/StatusCode.php';

/**
 * FmUser controller
 *
 * Contain two property($fmdb,$settings) one constructor
 * and two method(uploadDocument , viewDocument)
 */

class FmUserController
{/**
 * Used to contain db instance
 *
 * @var Object
 */
    public $fmdb;
    /**
     * Used to contain settings instance
     *
     * @var Object
     */
    public $settings;
/**
 *  Initialize the FileMaker instance and get the settings
 *
 * @param object $container contain information related to db
 */
    public function __construct(ContainerInterface $container)
    {
        $this->fmdb = $container->get('fmdb');
        $this->settings = $container->get('settings');
    }
    /**
     * Update Create User
     *
     *
     *
     *
     * @param object $request  represents the current HTTP request received
     *                         by the web server
     * @param object $response represents the current HTTP response to be
     *                         returned to the client.
     *
     * @return object           return response object with JSON format
     */
    public function createUser($request, $response)
    {
        /**
         * Used to contain RequestBody
         *
         * @var Object
         */
        $values = json_decode($request->getBody());
        /**
         * Used to contain Validation instance
         *
         * @var Object
         */
        $validator = new Validation();
        /**
         * Used to return Statement
         *
         * @var Boolean
         */
        $validateEmail = $validator->validateEmail($values->email);

        // Checking if any of the fields are empty
        if (empty($values->name) || empty($values->email) || empty($values->password) || empty($values->gender)) {
            return $response->withJSON(['error' => true, 'message' => 'Enter the required field.'],
                NOT_ACCEPTABLE);

        }
        //Validating the email

        else if (!$validateEmail) {
            return $response->withJSON(['error' => true, 'message' => 'Enter valid Email.'], INVALID_CREDINTIAL);
        }
        //Creating a new record
        else {
            /**
             * Used to contain Layout Name
             *
             * @var String
             */
            $layout_name = 'Employee';
            /**
             * Used to contain UserService instance
             *
             * @var Object
             */
            $userService = new UserService();
            /**
             * Used to contain return ststement
             *
             * @var Object
             */
            $result = $userService->createNewUser($layout_name, $values, $this->fmdb);
            return $response->withJson($result);
        }

    }
    /**
     * Login User
     *
     *
     *
     *
     * @param object $request  represents the current HTTP request received
     *                         by the web server
     * @param object $response represents the current HTTP response to be
     *                         returned to the client.
     * @param array  $args     store the values send through url
     *
     * @return object           return response object with JSON format
     */
    public function login($request, $response)
    {/**
     * Used to contain RequestBody
     *
     * @var Object
     */
        $values = json_decode($request->getBody());
        /**
         * Used to contain Validation instance
         *
         * @var Object
         */
        $validator = new Validation();
        /**
         * Used to contain Return Value
         *
         * @var Boolean
         */
        $validateEmail = $validator->validateEmail($values->email);

        // Checking if any of the fields are empty
        if (empty($values->email) || empty($values->password)) {
            return $response->withJSON(['error' => true, 'message' => 'Enter the required field.'],
                NOT_ACCEPTABLE);

        }
        //Validating the email

        else if (!$validateEmail) {
            return $response->withJSON(['error' => true, 'message' => 'Enter valid Email.'], INVALID_CREDINTIAL);
        }
        //login the user
        else {
            /**
             * Used to contain Layout Name
             *
             * @var Object
             */
            $layout_name = 'Employee';
            /**
             * Used to contain UserService instance
             *
             * @var Object
             */
            $userService = new UserService();
            /**
             * Used to contain return statement
             *
             * @var Object
             */
            $result = $userService->loginUser($layout_name, $values, $this->fmdb, $this->settings);
            return $response->withJson($result);
        }

    }

}
