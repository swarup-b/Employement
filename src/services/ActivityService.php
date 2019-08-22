<?php
/**
 * Activity Service
 *
 * create , delete , update activity
 * Created date : 17/08/2019
 *
 * PHP version 7
 *
 * @author  Original Author <swarupb@mindfiresolutions.com>
 * @version <GIT: https://github.com/swarup-b/Employement>
 */
namespace Src\Services;

use Src\Model\FmModel;

require_once __DIR__ . '/../services/DecodeToken.php';

/**
 * Activity Service
 *
 * 
 * and two method(uploadDocument , viewDocument)
 */
class ActivityService
{
    /**
     * Update Create User
     *
     *
     *
     *
     * @param object $request  represents the current HTTP request received
     *                         by the web server
     * @param object $response represents the current HTTP response to be
     *                         returned to the client.
     *
     * @return object           return response object with JSON format
     */
    public function createActivity($activity, $layoutName, $contactID, $fmdb)
    {/**
     * Used to contain db instance
     *
     * @var Object
     */
        $requestValue = array(
            'contactID' => $contactID,
            'date' => $activity->date,
            'activities' => $activity->activities,
        );
        /**
         * Used to contain db instance
         *
         * @var Object
         */
        $fmModel = new FmModel();
        return $result = $fmModel->create($layoutName, $requestValue, $fmdb);
    }
    /**
     * Update Create User
     *
     *
     *
     *
     * @param object $request  represents the current HTTP request received
     *                         by the web server
     * @param object $response represents the current HTTP response to be
     *                         returned to the client.
     *
     * @return object           return response object with JSON format
     */
    public function deleteAct($layoutName, $fmdb, $id)
    {
        /**
         * Used to contain db instance
         *
         * @var Object
         */
        $recordID = getRecordId($layoutName, $fmdb, $id);
        if ($recordID) {
            $fmModel = new FmModel();
            return $result = $fmModel->deleteRecord($layoutName, $fmdb, $recordID);
        }
        return array("error" => "Record not found");
    }
    /**
     * Update Create User
     *
     *
     *
     *
     * @param object $request  represents the current HTTP request received
     *                         by the web server
     * @param object $response represents the current HTTP response to be
     *                         returned to the client.
     *
     * @return object           return response object with JSON format
     */
    public function update($layoutName, $activityValue, $fmdb, $id)
    {
        /**
         * Used to contain db instance
         *
         * @var Object
         */
        $recordID = getRecordId($layoutName, $fmdb, $id);
        if ($recordID) {
            $fmModel = new FmModel();
            return $result = $fmModel->updateRecord($layoutName, $activityValue, $fmdb, $recordID);
        }
        return array("error" => "Record not found");

    }
}
