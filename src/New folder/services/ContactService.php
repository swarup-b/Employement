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

require_once __DIR__ .'/../model/FmUserModel.php';

class  ContactService{

    public function saveContactDetails($layoutName , $contactDetails , $fmdb){
        $contact=new FmModel();
        return $result = $contact->create($layoutName , $contactDetails , $fmdb);
    }
}