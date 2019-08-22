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

require_once __DIR__ . '/../model/FmUserModel.php';
require_once __DIR__ . '/../services/DecodeToken.php';
class ActivityService
{
    public function createActivity($activity, $layoutName, $contactID, $fmdb, $fmModel)
    {
        $requestValue = array(
            'contactID' => $contactID,
            'date' => $activity->date,
            'activities' => $activity->activities,
        );

        return $result = $fmModel->create($layoutName, $requestValue, $fmdb);
    }

    public function deleteAct($layoutName, $fmdb, $id)
    {
        
        $recordID = getRecordId($layoutName, $fmdb, $id);
        if($recordID){
            $fmModel = new FmModel();
            return $result = $fmModel->deleteRecord($layoutName, $fmdb, $recordID);
        }
        return array("error" => "Record not found");
    }

    public function update($layoutName, $activityValue, $fmdb, $id){
        $recordID = getRecordId($layoutName, $fmdb, $id);
        if($recordID){
            $fmModel = new FmModel();
            return $result = $fmModel->updateRecord($layoutName, $activityValue, $fmdb, $recordID);;
        }
        return array("error" => "Record not found");

    }
}
