<?php
/**
 * Contact Service
 *
 * save contact record
 * Created date : 17/08/2019
 *
 * PHP version 7
 *
 * @author  Original Author <swarupb@mindfiresolutions.com>
 * @version <GIT: https://github.com/swarup-b/Employement>
 */
namespace Src\Services;
use Src\Model\FmModel;


class  ContactService{

    public function saveContactDetails($layoutName , $contactDetails , $fmdb){
        $contact=new FmModel();
        return $result = $contact->create($layoutName , $contactDetails , $fmdb);
    }
}