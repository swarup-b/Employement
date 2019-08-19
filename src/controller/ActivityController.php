<?php
require_once __DIR__ . '/../services/ActivityService.php';
require_once __DIR__ . '/../constants/StatusCode.php';
require_once __DIR__ . '/../model/FmUserModel.php';
use Interop\Container\ContainerInterface;

class ActivityController
{

    public $fmdb;
    public $settings;
    public $fmModel;
    public $layoutName;

    public function __construct(ContainerInterface $container)
    {
        $this->fmdb = $container->get('fmdb');
        $this->settings = $container->get('settings');
        $this->fmModel = $container->get('fmModel');
        $this->layoutName = "activity";
    }
//Create new Activity
    public function create($request, $response, $args)
    {
        $contactID = $args['contactID'];
        $activity = json_decode($request->getBody());
        if (empty($activity->date) || empty($activity->activities) || empty($contactID)) {
            return $response->withJSON(['error' => true, 'message' => 'Enter the required field.'],
                NOT_ACCEPTABLE);
        } else {
            $activityService = new ActivityService();
            $result = $activityService->createActivity($activity, $this->layoutName, $contactID, $this->fmdb, $this->fmModel);
            return $response->withJson($result);
        }

    }
//
    public function updateActivity($request, $response, $args)
    {
        $id = $args['id'];
        $activityValue = json_decode($request->getBody());
        if (empty($activityValue->date) || empty($activityValue->activities) || empty($id)) {
            return $response->withJSON(['error' => true, 'message' => 'Enter the required field.'],
                NOT_ACCEPTABLE);
        } else {
           $result = $this->fmModel ->  updateRecord($this->layoutName, $activityValue, $this->fmdb, $id);
            return $response->withJson($result);
        }



    }

    public function deleteActivity($request, $response, $args)
    {
        $id = $args['id'];
        
        if (empty($id)) {
            return $response->withJSON(['error' => true, 'message' => 'Enter the required field.'],
                NOT_ACCEPTABLE);
        } else {
            $fmModel = new FmModel();
            $result=$fmModel ->  deleteRecord($this->layoutName, $this->fmdb, $id);
            return $response->withJson($result);
        }
    }

    public function getAllActivity($request, $response, $args)
    {
        $id = $args['contactID'];
        
        if (empty($id)) {
            return $response->withJSON(['error' => true, 'message' => 'Enter the required field.'],
                NOT_ACCEPTABLE);
        } else {
            $fmModel = new FmModel($request, $response, $args);
            $fieldName=array('contactID' => $id); 
            $result= $fmModel -> findFmRecord($this->layoutName, $fieldName, $this->fmdb);
            return $response->withJson($result);
        }
    }
}
