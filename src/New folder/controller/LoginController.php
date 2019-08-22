<?php
/**
 * User Profile Controller
 *
 * User profile view and update
 * Created date : 17/08/2019
 *
 * PHP version 7
 *
 * @author  Original Author <swarupb@mindfiresolutions.com>
 * @version <GIT: https://github.com/swarup-b/Employement>
 */
namespace App\api\controllers;
include($_SERVER['DOCUMENT_ROOT'].'/EmployeeRegistration/src/api/model/LoginAdmin.php');
require_once __DIR__ . '/../../constants/endpoints.php';
use \Firebase\JWT\JWT;
use Interop\Container\ContainerInterface;




class LoginController{

	public $db;
	public $settings;

	 public function __construct(ContainerInterface $container)
    {
        $this->db = $container->get('db');
        $this->settings = $container->get('settings');
    }

	public function create($request, $response){
		$adminDetails = json_decode($request->getBody());
		if (empty($adminDetails->name) || empty($adminDetails->email) || empty($adminDetails->password)) {
			  return $response->withJson(['error' => true, 'message' => 'Name ,Email or Password is empty.']);
		}else{
			$createObj=new LoginAdmin();
			  return $response->withJson($createObj->createAdmin($adminDetails,$this->db));
		}
	}




	public function login($request,$response){
		$adminDetails = json_decode($request->getBody());
		if (empty($adminDetails->email) || empty($adminDetails->password)) {
			  return $response->withJson(['error' => true, 'message' => 'Name ,Email or Password is empty.']);
		}else{
			$obj= new LoginAdmin();
			  return $response->withJson($obj->login( $adminDetails , $this->db));
		}
	}

}