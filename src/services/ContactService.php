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
    /**
 * Upload Image
 *
 *  @param object $uploadedFile represent the file object
 *
 * @return String/array           return path or error message
 */
     public function uploadImage($uploadedFiles){
         /**
         * Used to contain path where the image will be stored
         *
         * @var String
         */
           $directory='D:\xampp\htdocs\EmployeeRegistration\src\img\ ';
            /**
         * Used to contain uploaded file object
         *
         * @var Object
         */
           $uploadedFile = $uploadedFiles['profilePic'];
            /**
         * Used to contain targeted path to store files
         *
         * @var String
         */
           $target_path = $directory . basename( $_FILES["profilePic"]["name"]);
         
            if ($uploadedFile->getError() === UPLOAD_ERR_OK) {
                 $filename = move_uploaded_file($uploadedFile->file,$target_path);
                 $newPath = "http://localhost/EmployeeRegistration/src/img/%20".basename( $_FILES["profilePic"]["name"]);
                 return $newPath;
            }
            return withJson(['error' => 'Some Error Occured']);
                   
    }
      /**
 * Upload Image
 *
 *  @param object $encodeFile represent the encoded file
 *
 * @return String return path
 */
    public function decodeImage($encodeFile){
        /** Used to contain type of the image
          *
          *
          *@var String
          */
            $type = explode(";", explode("/", $encodeFile)[1])[0];
        /** Used to contain the encoded image
          *
          *
          *@var String
          */
            $encodedFile = str_replace('data:image/'.$type.';base64,', '', $encodeFile);
        /** Used to contain the image
          *
          *
          *@var img
          */
            $imgData = base64_decode($encodedFile,true);
        /** Used to contain the path that the image will stored
          *
          *
          *@var String
          */
            $file = 'D:\xampp\htdocs\EmployeeRegistration\src\img\\'.uniqid().'.'.$type ;
        /** Used to contain the response message
          *
          *
          *@var object
          */
            $success = file_put_contents($file, $imgData);
            /** Used to contain type of the image
          *
          *
          *@var String
          */
            $path = str_replace('D:\xampp\htdocs', 'http://localhost', $file);
            return $path;
    }
}
