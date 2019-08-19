<?php
require_once __DIR__ . '/../services/ContactService.php';
require_once __DIR__ . '/../services/Validation.php';
require_once __DIR__ . '/../constants/StatusCode.php';
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

    public function createContact($request, $response)
    {
        $contactDetails = json_decode($request->getBody());
        $layoutName ='myContact';
        // Checking if any of the fields are empty
        if (empty($contactDetails->fullname) || empty($contactDetails->email) || empty($contactDetails->title) || empty($contactDetails->phone) || empty($contactDetails->dob)) {
            return $response->withJSON(['error' => true, 'message' => 'Enter the required field.'],
                NOT_ACCEPTABLE);

        } else {
            $contactService = new ContactService();
            $result = $contactService->saveContactDetails($layoutName , $contactDetails ,$this->fmdb);
            return $response->withJSON($result);
        }
    }
}
