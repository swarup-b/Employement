<?php
require_once __DIR__ . '/../model/FmUserModel.php';
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
}
