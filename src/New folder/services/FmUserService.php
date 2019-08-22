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
namespace App\api\services;



use \Firebase\JWT\JWT;
require_once __DIR__ .'/../model/FmUserModel.php';
class UserService{


	public function createNewUser($layout_name,$values, $fmdb){

		    $user = new FmModel();
			$fieldsName=array("email"=>$values->email );
			$result = $user->findFmRecord($layout_name,$fieldsName, $fmdb);
			if (is_string($result)) { 
				$create = $user->create($layout_name,$values, $fmdb);
				return $create;
			}
			return ["data"=>"Email Already Exist"]; 
		
	}


	public function loginUser($layout_name,$data, $fmdb,$settings){
		$user = new FmModel();
		 $requestValue=array(
            'email'=>$data->email,
            'password'=>$data->password
        );
		$fieldsName=["email"=>$data->email , "password" => $data->password];
			$result = $user->findFmRecord($layout_name,$requestValue, $fmdb);
			if (is_string($result)) { 
				return ["error"=>"Invalid email or password"];
			}
			$recordId = array_column($result, 'recordId');
			$token = JWT::encode(['id' => $recordId[0]], $settings['jwt']['secret'], "HS256");
			return array("token"=>$token);
	}
}