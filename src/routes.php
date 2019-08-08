<?php
use Slim\App;
use Slim\Http\Request;
use Slim\Http\Response;
require "controller/UserController.php";
require "controller/LoginController.php";
require_once __DIR__ . '/constants/endpoints.php';

   
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
   

   
   
    
   
   
   
