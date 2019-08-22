<?php
/**
 * Activity Controller
 *
 * User profile view and update
 * Created date : 17/08/2019
 *
 * PHP version 7
 *
 * @author  Original Author <swarupb@mindfiresolutions.com>
 * @version <GIT: https://github.com/swarup-b/Employement>

 */
namespace Src\Controller;

use Interop\Container\ContainerInterface;
use Src\Model\FmModel;
use Src\Services\ActivityService;

require_once __DIR__ . '/../constants/StatusCode.php';

/**
 * Activity controller
 *
 * Contain three property($fmdb,$settings,$layoutName) one constructor
 * and two method(uploadDocument , viewDocument)
 */

class ActivityController
{
    /**
     * Used to contain db instance
     *
     * @var Object
     */
    public $fmdb;
    /**
     * Used to contain setting instance
     *
     * @var Object
     */
    public $settings;
    /**
     * Used to contain layout name
     *
     * @var Object
     */
    public $layoutName;
    /**
     *  Initialize the FileMaker instance and get the settings
     *
     * @param object $container contain information related to db
     */
    public function __construct(ContainerInterface $container)
    {
        $this->fmdb = $container->get('fmdb');
        $this->settings = $container->get('settings');
        $this->layoutName = "activity";
    }

    /**
     * Creating new Activity
     *
     *
     *
     *
     * @param object $request  represents the current HTTP request received
     *                         by the web server
     * @param object $response represents the current HTTP response to be
     *                         returned to the client.
     * @param array  $args     store the values send through url
     *
     * @return object           return response object with JSON format
     */
    public function create($request, $response, $args)
    {
        /**
         * Used to contain id of the Contact
         *
         * @var Integer
         */
        $contactID = $args['contactID'];
        /**
         * Used to contain request body
         *
         * @var Object
         */
        $activity = json_decode($request->getBody());
        /*
         * Condition for filed is empty or not.
         *
         */
        if (empty($activity->date) || empty($activity->activities) || empty($contactID)) {
            return $response->withJSON(['error' => true, 'message' => 'Enter the required field.'],
                NOT_ACCEPTABLE);
        } else {
            /**
             * ActivityService instance
             *
             * @var Object
             */
            $activityService = new ActivityService();
            /**
             * Used to get the result of the function called
             *
             * @var Object
             */
            $result = $activityService->createActivity($activity, $this->layoutName, $contactID, $this->fmdb);
            return $response->withJson($result);
        }

    }
    /**
     * Updating Activity
     *
     *
     *
     *
     * @param object $request  represents the current HTTP request received
     *                         by the web server
     * @param object $response represents the current HTTP response to be
     *                         returned to the client.
     * @param array  $args     store the values send through url
     *
     * @return object           return response object with JSON format
     */
    public function updateActivity($request, $response, $args)
    {
        $id = $args['id'];
        $activityValue = json_decode($request->getBody());
        if (empty($activityValue->date) || empty($activityValue->activities) || empty($id)) {
            return $response->withJSON(['error' => true, 'message' => 'Enter the required field.'],
                NOT_ACCEPTABLE);
        } else {
            $activityService = new ActivityService();
            $result = $activityService->update($this->layoutName, $activityValue, $this->fmdb, $id);
            return $response->withJson($result);
        }

    }

    /**
     * Creating new Activity
     *
     *
     *
     *
     * @param object $request  represents the current HTTP request received
     *                         by the web server
     * @param object $response represents the current HTTP response to be
     *                         returned to the client.
     * @param array  $args     store the values send through url
     *
     * @return object           return response object with JSON format
     */
    public function deleteActivity($request, $response, $args)
    {/**
     * Used to contain id of the Activity
     *
     * @var Integer
     */
        $id = $args['id'];
        /*
         * Condition for empty id
         */
        if (empty($id)) {
            return $response->withJSON(['error' => true, 'message' => 'Enter the required field.'],
                NOT_ACCEPTABLE);
        } else {
            /* *
             *  Activity Service instance
             * @var object
             */
            $activityService = new ActivityService();

            $result = $activityService->deleteAct($this->layoutName, $this->fmdb, $id);
            return $response->withJson($result);
        }
    }

    /**
     * Get All Activity on basis of contactID
     *
     *
     *
     *
     * @param object $request  represents the current HTTP request received
     *                         by the web server
     * @param object $response represents the current HTTP response to be
     *                         returned to the client.
     * @param array  $args     store the values send through url
     *
     * @return object           return response object with JSON format
     */
    public function getAllActivity($request, $response, $args)
    {
        /* Used to contain id of the Contacts
         *
         * @var Object
         */
        $id = $args['contactID'];
        /*
         * Condition for empty id
         */
        if (empty($id)) {
            return $response->withJSON(['error' => true, 'message' => 'Enter the required field.'],
                NOT_ACCEPTABLE);
        } else {
            /* *
             *  FmModel Class Instance
             * @var object
             */
            $fmModel = new FmModel();
            $fieldName = array('contactID' => $id);
            $result = $fmModel->findFmRecord($this->layoutName, $fieldName, $this->fmdb);
            return $response->withJson($result);
        }
    }
}
