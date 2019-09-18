<?php
/**
 * Contact Controller
 *
 * contact create , update , delte ,view
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
use Src\Services\ContactService;

require_once __DIR__ . '/../services/DecodeToken.php';
require_once __DIR__ . '/../constants/StatusCode.php';

/**
 * Contact controller
 *
 * Contain two property($fmdb,$settings) one constructor
 * and five method(createContact , getAllContact ,updateContact,getContactById,deleteContact)
 */

class ContactController
{
    /**
     * Used to contain db instance
     *
     * @var Object
     */
    public $fmdb;
    /**
     * Used to contain settings instance
     *
     * @var Object
     */
    public $settings;
    /**
     *  Initialize the FileMaker instance and get the settings
     *
     * @param object $container contain information related to db
     */
    public function __construct(ContainerInterface $container)
    {
        $this->fmdb = $container->get('fmdb');
        $this->settings = $container->get('settings');
    }
    /**
     * Creating Create Contact
     *
     *
     *
     *
     * @param object $request  represents the current HTTP request received
     *                         by the web server
     * @param object $response represents the current HTTP response to be
     *                         returned to the client.
     * @return return response object with JSON format
     */
    public function createContact($request, $response)
    {
        /* Used to contain  id
         *
         * @var Integer
         */
        $id = decodeToken();
        /*
         * Condition for Id
         */
        if ($id) {
            /**
             * Used to contain Request Body
             *
             * @var Object
             */
            $contactDetails = json_decode($request->getBody());
            $originalDate = $contactDetails->dob;
            $newDate = date("m-d-Y", strtotime($originalDate));
            $contactDetails->dob = $newDate;
            /**
             * Used to contain layout name
             *
             * @var String
             */
            $layoutName = 'allContact';
            /*
             * Condition For empty  field
             */
            if (empty($contactDetails->fullname) || empty($contactDetails->email) || empty($contactDetails->title) || empty($contactDetails->phone) || empty($contactDetails->dob)) {
                return $response->withJSON(['error' => true, 'message' => 'Enter the required field.'],
                    NOT_ACCEPTABLE);

            } else {
                /**
                 * Used to contain Container class Instance
                 *
                 * @var Object
                 */
                $contactService = new ContactService();
                /**
                 * Used to contain return of called function
                 *
                 * @var Object
                 */
                $result = $contactService->saveContactDetails($layoutName, $contactDetails, $this->fmdb);
                return $response->withJSON($result);
            }
        } else {
            return $response->withJSON(['error' => true, 'message' => 'Unauthorized Access.'],
                UNAUTHORIZED_USER);
        }
    }

    /**
     * Getting all Contact
     *
     *
     *
     *
     * @param object $request  represents the current HTTP request received
     *                         by the web server
     * @param object $response represents the current HTTP response to be
     *                         returned to the client.
     * @return   return response object with JSON format
     */
    public function getAllContacts($request, $response)
    {
        /**
         * Used to contain Container id
         *
         * @var Integer
         */
        $id = decodeToken();
        /**
         * Used to contain Layout Name
         *
         * @var String
         */
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

    /**
     * Ddelete Contacts
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

    public function deleteContact($request, $response, $args)
    {
        /*
         * Used to Store Layout Name
         * @var String

         */
        $layoutName = "allContact";
        /*
         * Used to Store Id of User
         * @var String

         */
        $id = decodeToken();
        /*
         *Condition for Id Present or not
         */
        if (!$id) {
            return $response->withJSON(['error' => true, 'message' => 'Unauthorized Access.'],
                UNAUTHORIZED_USER);

        } else if (empty($args['id'])) {
            return $response->withJSON(['error' => true, 'message' => 'Please provide an id.'],
                API_PARAM_REQUIRED);
        } else {
            /**
             * Used to contain FmModel class Instance
             *
             * @var Object
             */
            $contact = new FmModel();
            /**
             * Used to contain Method return object
             *
             * @var Object
             */
            $result = $contact->deleteRecord($layoutName, $this->fmdb, $args['id']);
            return $response->withJson($result);
        }
    }

    /**
     * Get Contact Base upon Id
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
    public function getContactsById($request, $response, $args)
    {
        /**
         * Used to contain Docode Id
         *
         * @var Integer
         */
        $id = decodeToken();
        /**
         * Used to contain Layout Name
         *
         * @var String
         */
        $layoutName = "allContact";
        /*
         *Condition for Id empty or not
         */
        if (!$id) {
            return $response->withJSON(['error' => true, 'message' => 'Unauthorized Access.'],
                UNAUTHORIZED_USER);

        } else if (empty($args['id'])) {
            return $response->withJSON(['error' => true, 'message' => 'Please provide an id.'],
                API_PARAM_REQUIRED);
        } else {
            $requestValue = array(
                'contactID' => $args['id'],
            );
            /**
             * Used to contain FmModel Class Instace
             *
             * @var Object
             */
            $contact = new FmModel();
            /**
             * Used to contain Return value
             *
             * @var Object
             */
            $result = $contact->findFmRecord($layoutName, $requestValue, $this->fmdb);
            return $response->withJson($result);
            // $find = $this->fmdb->newFindAnyCommand($layoutName);
            // $find->addFindCriterion('contactID', '*');
            // $per_page = 5;
            // $start = 1;
            // $find->setRange($start, $per_page);

            // $result = $find->execute();

            // $record_count = $result->getFoundSetCount();

            // if ($this->fmdb::isError($result)) {
            //     echo $result->intl_get_error_message();
            // }

            // $records = $result->getRecords();
            // return $response->withJson($result);

        }
    }

    /**
     * Update Contact Details
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
    public function updateContact($request, $response, $args)
    {
        /**
         * Used to contain Decode Id
         *
         * @var Integer
         */
        $id = decodeToken();
        /**
         * Used to contain Layout Name
         *
         * @var String
         */
        $layoutName = "Contact";
        /*
         *Condition for Id empty or not
         */
        if (!$id) {
            return $response->withJSON(['error' => true, 'message' => 'Unauthorized Access.'],
                UNAUTHORIZED_USER);

        } else if (empty($args['id'])) {
            return $response->withJSON(['error' => true, 'message' => 'Please provide an id.'],
                API_PARAM_REQUIRED);
        } else {
            /*
             * Used to contain Request Body
             *
             * @var Object
             */
            $requestValue = json_decode($request->getBody());
            if ($requestValue->recordId) {
                unset($requestValue->recordId);
            }
            $originalDate = $requestValue->dob;
            $newDate = date("m-d-Y", strtotime($originalDate));
            $requestValue->dob = $newDate;
            /**
             * Used to contain FmModel Instance
             *
             * @var Object
             */
            $contact = new FmModel();
            /**
             * Used to contain Return functionn
             *
             * @var Object
             */
            $result = $contact->updateRecord($layoutName, $requestValue, $this->fmdb, $args['id']);
            return $response->withJson($result);
        }

    }
    public function getRecordOnRange($request, $response)
    {
        $find = $this->fmdb->newFindAnyCommand('ContactList');
        $find->addFindCriterion('contactID', '*');
        $per_page = 5;
        $start =1;
        $find->setRange($start,$per_page);
       
        $result = $find->execute();
        $recs = $result->getRecords();

        $count = 0;
        foreach ($recs as $rec) {
            $field = $rec->getFields();
            $res['recordId'] = $rec->getRecordID();
            foreach ($field as $field_name) {
                if($field_name == 'dob'){
                    $date =  $rec->getField($field_name);
                    $newDate = date("Y-m-d", strtotime($date));
                    $res[$field_name] = $newDate;
                }else{
                $res[$field_name] = $rec->getField($field_name);
                }
            }
            $response1[$count] = $res;
            $count++;
        }
        print_r($response1);
      
    }
}
