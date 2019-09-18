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
 * and three method(create ,delete update)
 */
class ActivityService
{
    /**
     * Cretae new Activity
     *
     *
     *
     *
     * @param object $activity  represents the requestbody
     *                         by the web server
     * @param String $layoutName represents the clayout name
     *
     * @param integer $contactID represent the id
     *
     *  @param object $fmdb represent the db instance
     *
     * @return Array           return response array
     */
    public function createActivity($activity, $layoutName, $contactID, $fmdb)
    {/**
     * Used to contain requestValue
     *
     * @var Array
     */
        $requestValue = array(
            'contactID' => $contactID,
            'date' => $activity->date,
            'activities' => $activity->activities,
        );
        /**
         * Used to contain FmModel instance
         *
         * @var Object
         */
        $fmModel = new FmModel();
        return $result = $fmModel->create($layoutName, $requestValue, $fmdb);
    }
    /**
     * Delete Activity
     *
     *
     *
     *
     * @param String $layoutName represent the current layout name
     *
     * @param object $fmdb represents db instance
     *
     * @param Object $id represent the requested record to delete
     * @return Array           return response Array
     */
    public function deleteAct($layoutName, $fmdb, $id)
    {
        /**
         * Used to contain db instance
         *
         * @var Integer
         */
        $recordID = getRecordId($layoutName, $fmdb, $id);
        if ($recordID) {
            $fmModel = new FmModel();
            return $result = $fmModel->deleteRecord($layoutName, $fmdb, $recordID);
        }
        return array("error" => "Record not found");
    }
    /**
     * Update Activity
     *
     *
     *
     *
     * @param object $activityValue  represents the requestbody
     *                         by the web server
     * @param String $layoutName represents the clayout name
     *
     * @param integer $id represent the record to be update
     *
     *  @param object $fmdb represent the db instance
     *
     * @return Array           return response array
     */
    public function update($layoutName, $activityValue, $fmdb, $id)
    {
        /**
         * Used to contain record Id
         *
         * @var Integer
         */
        $recordID = getRecordId($layoutName, $fmdb, $id);
        if ($recordID) {
            $fmModel = new FmModel();
            return $result = $fmModel->updateRecord($layoutName, $activityValue, $fmdb, $recordID);
        }
        return array("error" => "Record not found");

    }
}
