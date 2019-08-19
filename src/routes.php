<?php
use Slim\App;
use Slim\Http\Request;
use Slim\Http\Response;
require "controller/UserController.php";
require "controller/LoginController.php";
require "controller/FmUserController.php";
require "controller/ContactController.php";
require "controller/ActivityController.php";
require_once __DIR__ . '/constants/endpoints.php';


//Filemaker Database 

//Getting all user informations
$app->get(USER_TEST_API_END_POINT, 'FmUserController:getInfo');

//Creating new User
$app->post(USER_CREATE_API_END_POINT, 'FmUserController:createUser');

//Deleting the user
$app->delete(USER_DELETE_API_END_POINT, 'FmUserController:deleteUser');

//Getting loggin the user
$app->post(LOGIN_USER_API_END_POINT, 'FmUserController:login');



//Creating new contacts
$app->post(CREATE_CONTACT_API_END_POINT, 'ContactController:createContact');

//Get all contacts
$app->get(GET_ALL_CONTACT_API_END_POINT, 'ContactController:getAllContacts');

//Get all contacts
$app->delete(DELETE_CONTACT_API_END_POINT, 'ContactController:deleteContact');

//Get all contacts
$app->put(UPDATE_CONTACT_API_END_POINT, 'ContactController:updateContact');

//Get all contacts
$app->get(GET_CONTACT_BY_ID_API_END_POINT, 'ContactController:getContactsById');



//Get all Activity
$app->get(GET_ACTIVITY_API_END_POINT, 'ActivityController:getAllActivity');

//update contacts
$app->put(UPDATE_ACTIVITY_API_END_POINT, 'ActivityController:updateActivity');

//Delete contacts
$app->delete(DELETE_ACTIVITY_API_END_POINT, 'ActivityController:deleteActivity');

//Create contacts
$app->post(CREATE_ACTIVITY_API_END_POINT, 'ActivityController:create');





  
// get all Employee

$app->get(ALL_EMPLOYEE_API_END_POINT, 'UserController:allEmployee');

// Add a new employee

$app->post(CREATE_EMPLOYEE_API_END_POINT, 'UserController:create');
   
// Update admin with given id

$app->put(UPDATE_EMPLOYEE_API_END_POINT, 'UserController:update');
   
// DELETE a admin with given id
$app->delete(DELETE_EMPLOYEE_API_END_POINT, 'UserController:delete');
   
//Login admin

$app->post(USER_LOGIN_API_END_POINT, 'LoginController:login');
  
 //create admin

$app->post(USER_REGISTER_API_END_POINT, 'LoginController:create');





   

   
   
    
   
   
   
