<?php
/**
 * functions for decodetoken and getrecordbyid
 *
 *
 * Created date : 17/08/2019
 *
 * PHP version 7
 *
 * @author  Original Author <swarupb@mindfiresolutions.com>
 * @version <GIT: https://github.com/swarup-b/Employement>
 */
use \Firebase\JWT\JWT;
/**
 * Decode Token
 *
 *
 *
 *
 * @param no parameter
 *
 * @return Integer           return current user login Id
 */
function decodeToken()
{
    try {
        /**
         * Used to contain headers Instance
         *
         * @var Object
         */
        $headers = apache_request_headers();
        /**
         * Used to contain token
         *
         * @var String
         */
        $token = $headers['Authorization'];
        /**
         * Used to contain decode token object
         *
         * @var Object
         */
        $decoded = JWT::decode($token, "supersecretkeymylogindemo", array('HS256'));
        return $decoded->id;
    } catch (Exception $e) { // Also tried JwtException
        echo "";
    }
}
/**
 * getRecordId using any filed value
 *
 *
 * @param String $layoutName represent the current layout name
 *
 * @param object $fmdb represents db instance
 *
 * @param Object $id represent the requested record
 * @return Integer           return recordId
 */
function getRecordId($layoutName, $fmdb, $id)
{
    try {
        /**
         * Used to contain decode token object
         *
         * @var Object
         */
        $findCommand = $fmdb->newFindCommand($layoutName);
        /**
         * Used to contain findCommand instance
         *
         * @var Object
         */
        $findCommand->addFindCriterion('id', $id);
        /**
         * Used to contain db execute object
         *
         * @var Object
         */
        $result = $findCommand->execute();
        /**
         * Used to contain record object
         *
         * @var Object
         */
        $recs = $result->getRecords();
        foreach ($recs as $rec) {
            $recordID = $rec->getRecordID();
        }
        return $recordID;
    } catch (Exception $e) {
        echo "";
    }

}
