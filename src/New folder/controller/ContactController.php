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
namespace Src\Api\Controller;


require_once __DIR__ . '/../services/ContactService.php';
require_once __DIR__ . '/../services/Validation.php';
require_once __DIR__ . '/../services/DecodeToken.php';
require_once __DIR__ . '/../../constants/StatusCode.php';
require_once __DIR__ . '/../model/FmUserModel.php';
use Interop\Container\ContainerInterface;

class ContactController
{

    public $fmdb;
    public $settings;

    public function __construct(ContainerInterface $container)
    {
        $this->fmdb = $container->get('fmdb');
        $this->settings = $container->get('settings');
    }
//Crreating new Contacts
    public function createContact($request, $response)
    {
        //$tkn=$request->get_headers('Authorization');

        $id = decodeToken();

        if ($id) {
            $contactDetails = json_decode($request->getBody());
            $layoutName = 'myContact';
            // Checking if any of the fields are empty
            if (empty($contactDetails->fullname) || empty($contactDetails->email) || empty($contactDetails->title) || empty($contactDetails->phone) || empty($contactDetails->dob)) {
                return $response->withJSON(['error' => true, 'message' => 'Enter the required field.'],
                    NOT_ACCEPTABLE);

            } else {
                $contactService = new ContactService();
                $result = $contactService->saveContactDetails($layoutName, $contactDetails, $this->fmdb);
                return $response->withJSON($result);
            }
        } else {
            return $response->withJSON(['error' => true, 'message' => 'Unauthorized Access.'],
                UNAUTHORIZED_USER);
        }
    }

    //Getting all Contacts
    public function getAllContacts($request, $response)
    {

        $id = decodeToken();
        $layoutName = "allContact";

        if ($id) {
            $requestValue = array(
                'email' => "*",
            );
            $contact = new FmModel();
            $result = $contact->findFmRecord($layoutName, $requestValue, $this->fmdb);
            return $response->withJson($result);
        } else {
            return $response->withJSON(['error' => true, 'message' => 'Unauthorized Access.'],
                UNAUTHORIZED_USER);
        }

    }

    //Deleting contact according to id

    public function deleteContact($request, $response, $args)
    {
        $layoutName = "allContact";
        $id = decodeToken();
        if (!$id) {
            return $response->withJSON(['error' => true, 'message' => 'Unauthorized Access.'],
                UNAUTHORIZED_USER);

        } else if (empty($args['id'])) {
            return $response->withJSON(['error' => true, 'message' => 'Please provide an id.'],
                API_PARAM_REQUIRED);
        } else {
            $contact = new FmModel();
            $result = $contact->deleteRecord($layoutName, $this->fmdb, $args['id']);
           // $contact->deleteRecord($layoutName, $this->fmdb, $args['id']);
            return $response->withJson($result);
        }
    }

    //get  contact according to id
    public function getContactsById($request, $response ,$args)
    {

        $id = decodeToken();
        $layoutName = "allContact";

        if (!$id) {
            return $response->withJSON(['error' => true, 'message' => 'Unauthorized Access.'],
            UNAUTHORIZED_USER);
           
        } else if(empty($args['id'])){
            return $response->withJSON(['error' => true, 'message' => 'Please provide an id.'],
            API_PARAM_REQUIRED);
        }else {
            $requestValue = array(
                'contactID' => $args['id'],
            );
            $contact = new FmModel();
            $result = $contact->findFmRecord($layoutName, $requestValue, $this->fmdb);
            return $response->withJson($result);
        }

    }

    //update contatcts

    public function updateContact($request, $response ,$args){
        $id = decodeToken();
        $layoutName = "allContact";

        if (!$id) {
            return $response->withJSON(['error' => true, 'message' => 'Unauthorized Access.'],
            UNAUTHORIZED_USER);
           
        } else if(empty($args['id'])){
            return $response->withJSON(['error' => true, 'message' => 'Please provide an id.'],
            API_PARAM_REQUIRED);
        }else {
            $requestValue = json_decode($request->getBody());
            $contact = new FmModel();
            $result = $contact->updateRecord($layoutName, $requestValue, $this->fmdb,$args['id']);
            return $response->withJson($result);
        }


    }
}
