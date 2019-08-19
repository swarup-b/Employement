<?php
use Slim\App;
use Slim\Http\Request;
use Slim\Http\Response;
require "controller/UserController.php";
require "controller/LoginController.php";
require "controller/FmUserController.php";
require "controller/ContactController.php";
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

//Getting loggin the user
$app->post(CREATE_CONTACT_API_END_POINT, 'ContactController:createContact');





  
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





   

   
   
    
   
   
   
