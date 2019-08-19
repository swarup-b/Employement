<?php

require_once __DIR__ . '/../services/FmUserService.php';
require_once __DIR__ . '/../services/Validation.php';
require_once __DIR__ . '/../constants/StatusCode.php';
use Interop\Container\ContainerInterface;

class FmUserController
{
    public $fmdb;
    public $settings;

    public function __construct(ContainerInterface $container)
    {
        $this->fmdb = $container->get('fmdb');
        $this->settings = $container->get('settings');
    }

    public function createUser($request, $response)
    {

        $values = json_decode($request->getBody());
        $validator = new Validation();
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

            $layout_name = 'userinfo';
            $userService = new UserService();
            $result = $userService->createNewUser($layout_name, $values, $this->fmdb);
            return $response->withJson($result);
        }

    }

    public function login($request, $response)
    {

        $values = json_decode($request->getBody());
        $validator = new Validation();
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

            $layout_name = 'userinfo';
            $userService = new UserService();
            $result = $userService->loginUser($layout_name, $values, $this->fmdb,$this->settings);
            return $response->withJson($result);
        }

    }

    public function deleteUser($request, $response, $args)
    {
        $layout_name = 'userinfo';
        $rec = $this->fmdb->getRecordById('userinfo', $args['id']);
        $rec->delete();

        if (FileMaker::isError($result)) {
            echo "Error: " . $result->getMessage() . "\n";
            exit;
        }
        return $response->withJson($values);
    }

    public function getInfo($request, $response)
    {

        $layout_name = 'userinfo';
        $findCommand = $this->fmdb->newFindAllCommand($layout_name);

        $result = $findCommand->execute();

        if (FileMaker::isError($result)) {
            echo "Error: " . $result->getMessage() . "\n";
            exit;}

        $records = $result->getRecords();

        $layout_object = $this->fmdb->getLayout($layout_name);
        $field_objects = $layout_object->getFields();
        $arr2 = array();
        $arr3 = array();
        $arr4 = array();

        foreach ($records as $record) {
            $recid = $record->getRecordId();
            foreach ($field_objects as $field_object) {
                $record = $this->fmdb->getRecordById($layout_name, $recid);
                $field_name = $field_object->getName();
                $field_value = $record->getField($field_name);
                $field_value = htmlspecialchars($field_value);
                $field_value = nl2br($field_value);
                $arr1 = array($field_name => $field_value);
                $arr2 = array_merge($arr2, $arr1);
                //echo  $field_name.': '.$field_value.'<br>';
            }
            $arr3 = array_merge($arr3, $arr2);
            echo json_encode($arr3);
        }
    }
}
