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
use Src\Model\FmModel;
use Src\Services\ContactService;
use Src\Services\Payment;


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

    // $container['upload_directory'] = __DIR__ . '/uploads';
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
            $layout_name = $values->type;
            unset($values->type);
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
            $layout_name = $values->type;
            unset($values->type);
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
    /**
     * GetAll records
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
    public function getRecord($request, $response,$args){
        /**
         * Used to contain Container id
         *
         * @var Integer
         */
       // $id = decodeToken();
        /**
         * Used to contain Layout Name
         *
         * @var String
         */
        $layoutName = $args['layout'];
      //  if ($id) {
            $requestValue = array(
                'email' => "*",
            );
            $contact = new FmModel();
            $result = $contact->findFmRecord($layoutName, $requestValue, $this->fmdb);
            return $response->withJson($result);
        // } else {
        //     return $response->withJSON(['error' => true, 'message' => 'Unauthorized Access.'],
        //         UNAUTHORIZED_USER);
        // }
    }
    /**
     * Reset Password
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
    public function resetPassword($request,$response){
       $requestData = json_decode($request->getBody());
       // Checking if any of the fields are empty
        if (empty($requestData->email) || empty($requestData->type)) {
            return $response->withJSON(['error' => true, 'message' => 'Enter the required field.'],
                NOT_ACCEPTABLE);
        }
         $requestValue = array(
                'email' => $requestData->email,
            );
        $contact = new FmModel();
        $result = $contact->findFmRecord($requestData->type, $requestValue, $this->fmdb);
        if(is_string($result)){
            return $response->withJSON(['message' => $result]);
        }
        $recordId = $result[0]['recordId'];
        $userService = new UserService();
        $emailStatus = $userService->sendEmail($requestData->email);
        $responseObj =json_encode($emailStatus);
        print_r($emailStatus['recordId']=$recordId );
        //if(!is_string($emailStatus)){
          //  return $response->withJSON($emailStatus["recordId"]=$recordId);
        // }
        // return $response->withJSON($emailStatus);

    }
//     public function changePassword($request,$response,$args){
//          $requestData = json_decode($request->getBody());
//           if (empty($requestData->password) || empty($requestData->type)) {
//             return $response->withJSON(['error' => true, 'message' => 'Enter the required field.'],
//                 NOT_ACCEPTABLE);
//         }
//           $layoutName = $requestData->type
//           $requestValue = array(
//                 'password' => $requestData->password;
//             );
//            /**
//              * Used to contain FmModel Instance
//              *
//              * @var Object
//              */
//             $contact = new FmModel();
//             /**
//              * Used to contain Return object
//              *
//              * @var Object
//              */
//             $result = $contact->updateRecord($layoutName, $requestValue, $this->fmdb, $args['id']);
//             return $response->withJson($result);
// }
    /**
     * Upload image
     *
     *
     *
     *
     * @param object $request  represents the current HTTP request received
     *                         by the web server
     * @param object $response represents the current HTTP response to be
     *                         returned to the client.
     *
     *
     * @return object           return response object with JSON format
     */
    public function uploadImage($request,$response){
        /**
         * Used to contain Container path
         *
         * @var String
         */
           $directory='D:\xampp\htdocs\EmployeeRegistration\src\img\ ';
           /**
         * Used to contain file object
         *
         * @var object
         */
           $uploadedFiles = $request->getUploadedFiles();
        /**
         * Used to contain file
         *
         * @var 
         */
            $uploadedFile = $uploadedFiles['profilePic'];
        /**
         * Used to contain path
         *
         * @var String
         */
            $target_path = $directory . basename( $_FILES["profilePic"]["name"]);

        /**
         * Used to contain request data
         *
         * @var Object
         */
            $formData = (object)$request->getParsedBody();
        /**
         * Used to contain recordid id
         *
         * @var Integer
         */
            $recordId = $formData->recordId;
        /**
         * Used to contain Container id
         *
         * @var Integer
         */
            if ($uploadedFile->getError() === UPLOAD_ERR_OK) {
                 $filename = move_uploaded_file($uploadedFile->file,$target_path);
                 $newPath = "http://localhost/EmployeeRegistration/src/img/%20".basename( $_FILES["profilePic"]["name"]);
                 $updateDetails = array('profilePic' => $newPath);
                  $contact = new FmModel();
                  $result = $contact->updateRecord('allContact', $updateDetails, $this->fmdb, $recordId);
                 return $response->withJson($result);
            }
            return $response->withJson(['error' => 'Some Error Occured']);

                   
    }

   public  function newPayment($request,$response){
        $payment = new Payment();
        $value = json_decode($request->getBody());
        // $result = $payment->savePaymentdetails($value ,$this->fmdb );
         $result = $payment->makePayment($value ,$this->fmdb );
         // $result = $payment->getAccessToken();

         
         return $response->withJson($result);
    }
    public  function testPayment($request,$response){
        $payment = new Payment();
        $value = json_decode($request->getBody());
        // $result = $payment->savePaymentdetails($value ,$this->fmdb );
        // $result = $payment->makePayment($value ,$this->fmdb );
        $result = $payment->authorizedPayment();

         
        // return $response->withJson($result);
    }

}
