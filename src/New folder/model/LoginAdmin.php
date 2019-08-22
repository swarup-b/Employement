 <?php
 //namespace App\api\models;
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


/**
 * 
 */
class LoginAdmin 
{
	public function createAdmin($adminDetails,$db){
         $sth = $db->prepare("SELECT * FROM admin WHERE email = :email");
         $sth->bindParam("email",$adminDetails->email);
         $sth->execute();
       	 if($sth->fetchColumn()==0){
	        $createAdmin = "INSERT INTO admin (name,email,password) VALUES (:name,:email,:password)";
	        $stmt = $db->prepare($createAdmin);
	        $stmt->bindParam("name", $adminDetails->name);
	        $stmt->bindParam("email",$adminDetails->email);
	        $stmt->bindParam("password",$adminDetails->password);
	        $stmt->execute();
	        $input['id'] =$db->lastInsertId();
	        return $input;
         }else{
         	$data=["result"=>"Email already exist"];
         	 return $data;
         }
     }


	public function login( $adminDetails , $db){
		 $sth = $db->prepare("SELECT * FROM admin WHERE email = :email and password=:password");
         $sth->bindParam("email",$adminDetails->email);
         $sth->bindParam("password",$adminDetails->password);
         $sth->execute();
         if($sth->fetchColumn()==0){
           $data=["result"=>"Invalid email and password"];
            return $data;

         }else{
            $data=["result"=>"success"];
             return $data;
         }
	}
}