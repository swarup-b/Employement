<?php
require_once __DIR__ .'/../model/FmUserModel.php';

class  ContactService{

    public function saveContactDetails($layoutName , $contactDetails , $fmdb){
        $contact=new FmModel();
        return $result = $contact->create($layoutName , $contactDetails , $fmdb);
    }
}