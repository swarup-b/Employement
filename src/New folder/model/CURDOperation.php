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

namespace App\api\models;


class CURDUser{
	public function createEmployee($adminDetails,$db){
	        $createAdmin = "INSERT INTO employees (name, address, salary,admin_id) VALUES (:name,:address,:salary,:admin_id)";
	        $stmt = $db->prepare($createAdmin);
	        $stmt->bindParam("name", $adminDetails->name);
	        $stmt->bindParam("address",$adminDetails->address);
	        $stmt->bindParam("salary",$adminDetails->salary);
	         $stmt->bindParam("admin_id",$adminDetails->admin_id);
	        $stmt->execute();
	        $input['id'] =$db->lastInsertId();
	        return $input;


	}
	public function updatemployee($adminDetails,$id,$db){
		$sql = "UPDATE employees SET name=:name,address=:address,salary=:salary WHERE id=:id";
        $stmt = $db->prepare($sql);
        $stmt->bindParam("id", $id);
        $stmt->bindParam("name", $adminDetails->name);
        $stmt->bindParam("address",$adminDetails->address);
        $stmt->bindParam("salary",$adminDetails->salary);
        $stmt->execute();
        $admin['id'] = $id;
        return $admin;

	}
	public function getAllEmployee($db){
		 $sth = $db->prepare("SELECT * FROM employees ");
         $sth->execute();
         $allAdmin = $sth->fetchAll();
        return $allAdmin;

	}public function deleteEmployee($id,$db){
		 $sth = $db->prepare("DELETE FROM employees WHERE id=:id");
        $sth->bindParam("id",$id);
        $sth->execute();
        $data=["result"=>"successfully deleted"];
        return $data;

	}


}