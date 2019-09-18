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

/**
 * Contact Service
 *
 *
 * and one method(saveContactDetails)
 */
class ContactService
{
/**
 * Cretae new Activity
 *
 *
 *
 *
 * @param String $layoutName represents the clayout name
 *
 * @param Object $contactDetails represent the Request value to be updated
 *
 *  @param object $fmdb represent the db instance
 *
 * @return Array           return response array
 */
    public function saveContactDetails($layoutName, $contactDetails, $fmdb)
    {
        /**
         * Used to contain FmModel Instance
         *
         * @var Object
         */
        $contact = new FmModel();
        return $result = $contact->create($layoutName, $contactDetails, $fmdb);
    }
}
