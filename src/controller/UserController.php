<?php 
include($_SERVER['DOCUMENT_ROOT'].'/EmployeeRegistration/src/model/CURDOperation.php');

require_once __DIR__ . '/../constants/endpoints.php';
use \Firebase\JWT\JWT;
use Interop\Container\ContainerInterface;

class UserController{
	public $db;
	public $settings;

	 public function __construct(ContainerInterface $container)
    {
        $this->db = $container->get('db');
        $this->settings = $container->get('settings');
    }

	public function create($request, $response){
		 $adminDetails = json_decode($request->getBody());
		if (empty($adminDetails->name) || empty($adminDetails->address) || empty($adminDetails->salary)) {
             return $response->withJson(['error' => true, 'message' => 'Name ,address or salary is empty.']);
        }else{
		 $createObj=new CURDUser();
		  return $response->withJson($createObj->createEmployee($adminDetails,$this->db));
		}
	}

	public function allEmployee($request, $response){
		 $allEmp=new CURDUser();
		 return $response->withJson($allEmp->getAllEmployee($this->db));
	}

	public function update($request, $response,$args){
		 $adminDetails = json_decode($request->getBody());
		if (empty($adminDetails->name) || empty($adminDetails->address) || empty($adminDetails->salary)) {
            return $response->withJson(['error' => true, 'message' => 'Name ,address or salary is empty.']);
        }else if (empty($args['id'])) {
        	return $response->withJson(['error' => true, 'message' => 'ID is required.']);
        }else{
		 $updateEmp=new CURDUser();
		 return $response->withJson($updateEmp->updatemployee($adminDetails,$args['id'],$this->db));
		}

	}
	public function delete($request, $response,$args){
		 $deleteEmp=new CURDUser();
		return $response->withJson($deleteEmp->deleteEmployee($args['id'],$this->db));
	}
}?>